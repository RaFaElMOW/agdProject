# Arquitetura e Banco de Dados — Portal AGD Niger

> Documento de referência da implementação. A arquitetura proposta original está em `C:\Users\rafae\.claude\plans\reactive-crunching-crystal.md` (aprovada antes da Fase 0); este documento descreve o que foi efetivamente construído nas Fases 0–3.

## 1. Visão geral

Portal institucional (template "Welfare"/Colorlib) evoluído para uma plataforma administrável, mantendo 100% do HTML/CSS/JS público original. Stack: PHP 8.2, MySQL/MariaDB 10.4, sem framework de terceiros — MVC próprio e leve, compatível com hospedagem compartilhada cPanel sem SSH.

```
/agdProject (webroot)
├── *.php                    páginas públicas (mantidas no lugar — "thin view controllers")
├── admin/index.php          front controller único do painel (/admin/*)
├── donate-redirect.php      endpoint público de redirecionamento ao PayPal
├── .htaccess                rewrite + hardening + headers
├── manifest.json, sw.js     PWA
├── icons/                   ícones do PWA
├── app/
│   ├── Core/                Router, Database, Request, Response, View, MigrationRunner
│   ├── Middleware/           SecurityHeaders, Csrf, SessionAuth, Rbac, RateLimit
│   ├── Controllers/Admin/    1 controller por módulo do painel
│   ├── Repositories/         única camada que toca SQL (PDO + prepared statements)
│   ├── Services/             AuthService, AuditService, RateLimiterService, UploadService,
│   │                         DonationService
│   └── Support/              helpers (e, csrf_field, Settings, Auth, Slug, Sanitizer,
│                              VideoUrl, ImageUploadHelper, MenuRenderer, BasePath...)
├── resources/views/
│   ├── admin/                telas do painel
│   ├── site/ (n/a)           — público continua nos .php da raiz, não migrado para views/
│   └── partials/             fontes únicas reusadas (donation-modal, donation-cta,
│                              sponsorship-card, book-card)
├── database/
│   ├── migrations/           32 arquivos .sql numerados, idempotentes via migrations_log
│   └── seeds/                dados reais (não fictícios) extraídos do conteúdo original
├── storage/logs/, cache/     bloqueados via .htaccess
├── uploads/                  imagens enviadas pelo painel (PHP execution desabilitado)
├── tools/migrate.php         executor de migrations via HTTP (sem SSH), token + sessão admin
└── vendor/                   firebase/php-jwt, phpmailer, ezyang/htmlpurifier (resolvidos localmente)
```

**Padrão MVC**: Controller → Service (regra de negócio) → Repository (SQL) → View (escapa saída por padrão via `e()`).

**Importante sobre JWT**: a arquitetura prevê um modelo híbrido (sessão para o painel + JWT para uma futura camada de API). A biblioteca `firebase/php-jwt` está vendored, mas **nenhuma rota `/api` foi implementada** nas Fases 0–3 — não havia necessidade funcional ainda. Isso significa que o JWT está pronto para uso futuro, mas não há hoje nenhum endpoint que o utilize. Documentado para evitar a falsa impressão de que existe uma API JWT em produção.

---

## 2. Modelo de dados (DER) — todas as tabelas

### Autenticação, RBAC, Auditoria (Fase 0)
| Tabela | Campos-chave | Relacionamento |
|---|---|---|
| `users` | id, name, email(unique), password_hash, status, must_change_password, token_version, failed_attempts, locked_until, mfa_secret/mfa_enabled (reservado) | auto-FK `created_by` |
| `roles` | id, name, slug | — |
| `permissions` | id, name, slug, module | — |
| `role_permissions` | role_id, permission_id | N:N |
| `user_roles` | user_id, role_id | N:N |
| `password_resets` | id, user_id, token_hash, expires_at, used_at | 1:N users |
| `refresh_tokens` | id, user_id, token_hash, expires_at, revoked_at, ip, user_agent | 1:N users (reservado p/ futura API) |
| `audit_logs` | id, user_id(null), action, entity_type, entity_id, ip, metadata(JSON), created_at | N:1 users |
| `login_attempts` | identifier, success, ip, user_agent, created_at | — |
| `rate_limit_hits` | rate_key, window_start, hit_count (UNIQUE rate_key+window_start) | — |
| `migrations_log` | migration(unique), executed_at | controle interno |

### Configuração / Conteúdo institucional (Fase 1)
| Tabela | Uso |
|---|---|
| `settings` | EAV (setting_key PK, setting_value, setting_group) |
| `menus` | location(header/footer), label, url, parent_id(self-FK), sort_order, target_blank |
| `site_content` | content_key(unique) + data(JSON) — usado por About/Quem Somos |

### Conteúdo (Fase 2)
| Tabela | Campos principais |
|---|---|
| `team_members` | name, role, photo, bio, redes sociais, sort_order, active |
| `testimonials` | name, role, photo, text, youtube_url, sort_order, active |
| `projects` | name, slug(unique), description, banner, status, external_link, sort_order |
| `project_gallery` | project_id→projects(CASCADE), image_path, sort_order |
| `sponsorship_cards` | title, description, value, currency, image, icon, cta_link, sort_order, status |
| `books` | title, author, description, cover, link, price, currency, format, sort_order, status |
| `media_items` | type(image/video), title, url_or_path, thumbnail, category, sort_order, active |
| `blog_categories` / `blog_tags` | name, slug(unique) |
| `blog_posts` | title, slug(unique), excerpt, content, banner, author_id→users(SET NULL), category_id→blog_categories(SET NULL), status, published_at, meta_title/description, og_image |
| `blog_post_tags` | post_id+tag_id (PK composta, ambos CASCADE) |
| `blog_comments` | post_id(CASCADE), parent_id(self-FK CASCADE), author_name/email, content, status, ip |
| `contact_messages` | name, email, subject_option, message, ip, status |

### Doações (Fase 3)
| Tabela | Uso |
|---|---|
| `donation_methods` | country_scope(national/international), method_type, label, details(texto livre), sort_order, active |
| `paypal_accounts` | label, currency, paypal_business_id, country_scope, sort_order, active |
| `donation_preset_amounts` | currency, amount, sort_order, active |

Todas as FKs com `ON DELETE` explícito (CASCADE para filhos diretos, SET NULL para referências cujo "dono" pode ser removido sem invalidar o registro histórico — ex: post de blog sobrevive à exclusão do autor).

## 3. RBAC implementado

4 perfis seedados (editáveis): **Administrator** (todas as 16 permissões), **Editor** (conteúdo + blog), **Moderador** (comentários/depoimentos/contato), **Visualizador** (nenhuma permissão de escrita — apenas dashboard). 16 permissões granulares por módulo, todas as rotas administrativas (exceto login/logout/troca-de-senha/dashboard) protegidas por `RbacMiddleware`.
