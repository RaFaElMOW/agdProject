# Segurança — Cobertura OWASP Top 10 / ASVS e Hardening Aplicado

## OWASP Top 10 (2021) — situação por categoria

| # | Categoria | Mitigação implementada | Status |
|---|---|---|---|
| A01 | Broken Access Control | RBAC granular (16 permissões, 4 perfis) em toda rota administrativa exceto login/dashboard; testado com perfil Moderador — 403 em 5/5 rotas não autorizadas | ✅ Coberto |
| A02 | Cryptographic Failures | `password_hash()` BCRYPT custo padrão; nunca senha em texto puro; cookies `HttpOnly`; `Secure` condicionado a HTTPS detectado; HSTS quando HTTPS ativo | ✅ Coberto |
| A03 | Injection (SQL/XSS) | 100% das queries via PDO prepared statements (nenhuma concatenação de SQL no código); saída sempre escapada via `e()`; conteúdo rico do blog sanitizado via HTMLPurifier | ✅ Coberto (testado) |
| A04 | Insecure Design | Lockout progressivo, rate limiting, CSRF, princípio do menor privilégio no RBAC | ✅ Coberto |
| A05 | Security Misconfiguration | `.htaccess` nega acesso a `/app /database /storage /vendor /resources`, dotfiles, `composer.json/lock`; `display_errors` condicionado a `APP_DEBUG` | ✅ Coberto |
| A06 | Vulnerable Components | `composer audit` sem advisories nas 3 dependências vendored (ver `04-cves.md`) | ✅ Coberto |
| A07 | Identification & Auth Failures | Lockout após 5 tentativas, mensagens de erro genéricas (não revelam se a conta existe/está bloqueada), `session_regenerate_id()` no login, logout global via `token_version` | ✅ Coberto (testado) |
| A08 | Software & Data Integrity | Upload com validação real de MIME (`finfo`, não Content-Type do cliente) + reencode via GD; conteúdo de blog sanitizado antes de persistir | ✅ Coberto (testado) |
| A09 | Security Logging & Monitoring | `audit_logs` registra login/logout/CRUD/acesso negado; confirmado nos testes (6 `access_denied`, 6 `login_failed` capturados) | ✅ Coberto (testado) |
| A10 | Server-Side Request Forgery | Vídeos do módulo Mídia validados contra allowlist de host (YouTube/Vimeo) antes de aceitar a URL — testado com host malicioso, rejeitado | ✅ Coberto (testado) |

## Vetores específicos pedidos no escopo original

| Vetor | Mitigação | Evidência |
|---|---|---|
| CSRF | Token sincronizador em toda mutação do painel | Testado: POST sem token → 403 |
| XSS | `e()` por padrão + HTMLPurifier no blog | Testado: `<script>` em nome de membro da equipe → escapado, não executa |
| SQL Injection | PDO prepared statements | Testado: 4 payloads classicos no login e em slugs → sem bypass, sem erro de SQL exposto |
| SSRF | Allowlist de host para vídeos | Testado: host arbitrário rejeitado antes de chegar ao banco |
| RCE | Sem `eval`/`exec`/`system`/`unserialize` de dados externos no código | Revisão de código |
| LFI/RFI | Nenhum `include()`/`require()` com caminho vindo de input do usuário | Revisão de código |
| Clickjacking | `X-Frame-Options: SAMEORIGIN` + `frame-ancestors 'self'` (CSP do painel) | Header verificado |
| Open Redirect | Allowlist de caminho interno (`UrlAllowlist`) generalizada do padrão já usado em `set-language.php` | Testado: 4 payloads (`http://evil...`, `//evil...`, `javascript:...`) → todos caem no fallback seguro |
| Directory Traversal | Apache nunca resolve `../` fora do docroot; rotas de slug fazem lookup parametrizado (não `include` por path) | Testado: payloads de traversal em `slug` → redirecionamento seguro, sem erro |
| Upload malicioso | MIME real via `finfo`, extensão controlada, nome aleatório, reencode via GD | Testado: arquivo `.php` renomeado para `.jpg` com `Content-Type: image/jpeg` forjado → rejeitado, nada gravado em disco |

## Hardening de headers (site-wide via `.htaccess` + CSP específica do painel via middleware)

`X-Content-Type-Options: nosniff`, `X-Frame-Options: SAMEORIGIN`, `Referrer-Policy: strict-origin-when-cross-origin`, `Permissions-Policy` restritiva, `HSTS` condicionado a HTTPS, `Content-Security-Policy` (escopo `/admin`).

## Cookies

`HttpOnly` sempre; `Secure` automático quando `$_SERVER['HTTPS']` detectado; `SameSite=Lax`.

## `tools/migrate.php` — modelo de segurança

Endpoint HTTP reaproveitado em toda fase futura (não é descartável após o primeiro deploy). Controles:

- Token de 256 bits (`MIGRATE_TOKEN`), comparação em tempo constante (`hash_equals`) — força bruta é inviável.
- Aceita o token via `POST` (corpo da requisição, não fica no log de acesso do Apache nem no histórico do navegador) ou `GET` (mantido por conveniência).
- Rate limit de 10 requisições/minuto por IP, aplicado **antes** da checagem de token (tentativas com token errado também contam).
- Depois que o primeiro usuário existe, o token por si só não basta mais — exige sessão de admin autenticado com `users.manage`. Ou seja: em todo uso recorrente (a partir do segundo), mesmo que o token vazasse, sozinho ele não executa nada.
- O único dado sensível que esse endpoint pode devolver é a senha gerada do primeiro admin, e só na primeira chamada bem-sucedida (antes de existir qualquer usuário) — mitigado predefinindo `ADMIN_PASSWORD` no `.env` antes do primeiro deploy (ver runbook).
- Tolerante a importação manual de SQL via phpMyAdmin: se uma tabela/coluna já existir sem registro em `migrations_log`, o runner detecta e marca como aplicada em vez de travar com erro.

## Limitações conhecidas / aceitas (registradas, não escondidas)

1. **JWT não está em uso** — vendored para uma futura API, sem endpoint ativo hoje. Nenhum risco atual, mas também nenhuma cobertura de pentest "manipulação de JWT" é aplicável (não há o que atacar).
2. **PHPMailer vendored mas não utilizado** — reset de senha hoje é feito pelo admin gerando uma nova senha diretamente no painel (sem e-mail). É superfície de dependência sem benefício funcional atual; considerar remover ou ativar em fase futura.
3. **Acordeão de métodos de doação em `donate.php` não tem fallback estático** caso o banco fique indisponível (diferente do padrão usado nos outros módulos) — risco baixo (a tabela é seedada por padrão), mas vale registrar.
4. **Sem MFA implementado** — campo `mfa_secret`/`mfa_enabled` já existe no schema de `users`, pronto para ativação futura (TOTP), mas não construído nesta fase.
5. **Rate limiting é por IP**, não por conta — em IPv4 compartilhado (NAT/proxy corporativo) usuários legítimos atrás do mesmo IP podem ser throttled juntos. Aceitável para o volume esperado deste site.
