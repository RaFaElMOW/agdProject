# Runbook de Deploy — Hospedagem Compartilhada cPanel (sem SSH)

## Pré-requisitos no cPanel

- PHP 8.0+ selecionado em "MultiPHP Manager" (idealmente 8.2, igual ao ambiente testado).
- Extensões PHP habilitadas: `pdo_mysql`, `gd`, `fileinfo`, `mbstring`, `openssl`, `json` (todas padrão na maioria dos planos; `gd` às vezes precisa ser ativada manualmente em "Select PHP Version" → "Extensions").
- Um banco de dados MySQL criado + usuário com todas as permissões nesse banco (via "MySQL Databases").
- AutoSSL/Let's Encrypt ativado para o domínio (necessário para `Secure` cookies e HSTS funcionarem corretamente).

## Passo 1 — Build local (sempre fora do servidor)

```
composer install --no-dev --optimize-autoloader
```

Isso gera a pasta `vendor/` completa. **Não existe Composer no servidor** — a pasta `vendor/` precisa ser enviada já pronta.

## Passo 2 — Preparar o `.env` de produção

Copie `.env.example` para `.env` e preencha:

- `APP_ENV=production`, `APP_DEBUG=false` (crítico — nunca deixar `true` em produção, expõe stack trace).
- `APP_URL` com o domínio real.
- `DB_*` com as credenciais do banco criado no cPanel.
- `JWT_SECRET` e `MIGRATE_TOKEN`: gerar valores aleatórios novos (não reaproveitar os de desenvolvimento). Sugestão: `php -r "echo bin2hex(random_bytes(32));"` localmente.
- `ADMIN_EMAIL`/`ADMIN_PASSWORD`: **defina uma senha forte aqui antes do primeiro deploy.** Se deixar `ADMIN_PASSWORD` vazio, o sistema gera uma senha aleatória e a devolve uma única vez na resposta do `tools/migrate.php` — funciona, mas isso é o único dado realmente sensível que esse endpoint pode expor, e fica sujeito a quem chegar primeiro na URL após o deploy. Predefinir a senha aqui elimina esse risco por completo.

## Passo 3 — Upload via File Manager / FTP

Enviar **todo o conteúdo do projeto** (incluindo `vendor/`, excluindo `.git/`, `node_modules/` se houver, e qualquer coisa do `.gitignore`) para a raiz pública do domínio (`public_html/` ou a pasta do domínio/subdomínio configurado).

## Passo 4 — Rodar as migrations + seed iniciais (via HTTP, sem SSH)

O endpoint só aceita `POST` (de propósito — o token nunca aparece em log de acesso ou histórico do navegador dessa forma). Como não dá para simplesmente abrir a URL no navegador, use uma das duas opções abaixo a partir do **seu computador** (não precisa de terminal no servidor, só no seu):

**Opção A — `curl` (um comando, qualquer terminal local — Windows/Mac/Linux):**
```
curl -X POST https://seu-dominio.com/tools/migrate.php -d "token=SEU_MIGRATE_TOKEN" -d "seed=1"
```

**Opção B — arquivo HTML local, sem terminal:** salve isto como `migrate.html` no seu computador e abra no navegador:
```html
<form method="post" action="https://seu-dominio.com/tools/migrate.php">
  <input name="token" value="SEU_MIGRATE_TOKEN">
  <input name="seed" value="1">
  <button type="submit">Rodar migrations</button>
</form>
```

Em ambos os casos:
1. A resposta é um JSON com `migrations_executed`. Se você não predefiniu `ADMIN_PASSWORD` no `.env` (passo 2), vem também `seed.admin.password_shown_once` — copie imediatamente, não aparece de novo. Se predefiniu, esse campo vem vazio.
2. Faça login em `https://seu-dominio.com/admin/login` com essa senha e troque-a (o sistema já força a troca no primeiro acesso).
3. Para usos futuros (uma migration nova de uma próxima fase), repita a Opção A ou B — o endpoint é reaproveitável indefinidamente; depois que o primeiro admin existir, ele passa a exigir sua sessão autenticada também, então abra o painel logado no mesmo navegador antes de usar a Opção B.

### Importei o `.sql` direto via phpMyAdmin em vez de usar o passo acima — funciona?

Sim. Importe os arquivos de `database/migrations/` **na ordem numérica** pela aba SQL/Importar do phpMyAdmin. Depois disso, rode a Opção A/B acima com `seed=1` mesmo assim — ele detecta que as tabelas já existem (inclusive as duas migrations que são `ALTER TABLE`, que normalmente dariam erro de "coluna duplicada" se rodadas de novo) e marca como aplicadas sem travar, seguindo direto para o seed (roles, permissões, configurações, admin inicial).

## Passo 5 — Desativar o acesso a `tools/migrate.php`

Depois do primeiro deploy, **apague o arquivo `tools/migrate.php`** (ou esvazie `MIGRATE_TOKEN` no `.env`) — ele só deve voltar a existir/ter token quando uma nova fase trouxer migrations novas. Reativar é só re-enviar o arquivo via FTP quando precisar.

## Passo 6 — Configurar o primeiro conteúdo real

No painel (`/admin`), preencher:
- **Configurações**: nome do site, logo, cores, redes sociais, contato, SEO, GA/GTM.
- **Doações → Contas PayPal**: cadastrar o e-mail/ID comercial PayPal real (nacional e/ou internacional) — sem isso o botão "Doar Online" mostra "indisponível".
- **Menus**: confirmar que o menu seedado bate com a navegação desejada.
- **Conteúdo, Equipe, Depoimentos, Projetos, etc.**: o conteúdo original do template continua aparecendo como fallback até cada item ser cadastrado pelo painel.

## Diferenças entre o ambiente de teste (XAMPP local) e produção

| Aspecto | Local (testado) | Produção (cPanel) |
|---|---|---|
| Caminho base | `/agdProject` (subpasta) | `/` (raiz do domínio) — o código já trata os dois casos automaticamente via `BasePath`/`SCRIPT_NAME` |
| HTTPS | HTTP puro | HTTPS via AutoSSL — `Secure` nos cookies e HSTS passam a ativar automaticamente |
| PHP | CLI ZTS Windows | Geralmente NTS Linux — sem impacto, nenhum código depende de threading |
| Migrations | Via `tools/migrate.php` com sessão local | Mesmo mecanismo — é o único caminho possível sem SSH |

## Rollback

Como não há SSH, rollback de código = re-enviar a versão anterior via FTP. Rollback de banco = restaurar backup do MySQL feito antes do deploy (cPanel → phpMyAdmin → Exportar, antes de cada deploy que rode migrations novas).
