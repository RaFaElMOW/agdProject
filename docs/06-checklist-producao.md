# Checklist de Homologação e Produção

## Antes de ir ao ar

- [ ] `.env` em produção tem `APP_ENV=production` e `APP_DEBUG=false`
- [ ] `JWT_SECRET` e `MIGRATE_TOKEN` são valores novos, gerados para produção (não os de desenvolvimento)
- [ ] AutoSSL/HTTPS ativo e funcionando no domínio
- [ ] `vendor/` enviado completo (resolvido localmente via `composer install --no-dev`)
- [ ] Migrations + seed executados uma vez via `tools/migrate.php`
- [ ] Senha do primeiro admin copiada e trocada com sucesso
- [ ] `tools/migrate.php` removido ou com `MIGRATE_TOKEN` vazio após o deploy inicial
- [ ] Extensão `gd` confirmada ativa no PHP do cPanel (necessária para upload de imagens)
- [ ] Backup do banco de dados feito antes do primeiro deploy

## Conteúdo institucional

- [ ] Nome do site, logo, favicon, cores configurados em Configurações
- [ ] Redes sociais, telefone/WhatsApp, e-mail de contato confirmados (substituem os valores reais já migrados como padrão)
- [ ] Menus de cabeçalho/rodapé revisados
- [ ] Conta(s) PayPal real(is) cadastrada(s) em Doações → Contas PayPal (nacional e/ou internacional) — **sem isso a doação online não funciona**
- [ ] Métodos de doação nacional/internacional (bancos, PIX, etc.) revisados — os dados migrados são os reais do site anterior, mas vale confirmar
- [ ] Pelo menos um usuário Administrator além do gerado automaticamente, para não haver ponto único de acesso

## Segurança

- [ ] Cookie de sessão com `Secure=true` confirmado em produção (inspecionar via DevTools do navegador, aba Application/Cookies)
- [ ] Headers de segurança presentes (`X-Frame-Options`, `X-Content-Type-Options`, etc.) — testar com `curl -I https://seu-dominio.com`
- [ ] Acesso direto a `/app/`, `/database/`, `/vendor/`, `/storage/` retorna 403
- [ ] `.env` não é acessível via navegador (`https://seu-dominio.com/.env` deve dar 403)
- [ ] Login com senha errada 5x bloqueia a conta corretamente
- [ ] `composer audit` rodado sem advisories antes do deploy

## Funcional (smoke test pós-deploy)

- [ ] Home, About, Quem Somos, Doar, Apadrinhar, Projetos, Livros, Mídia, Blog, Contato — todas abrem sem erro
- [ ] Login admin funciona; CRUD de pelo menos um módulo testado (criar/editar/excluir)
- [ ] Modal de doação abre e, com uma conta PayPal cadastrada, redireciona corretamente
- [ ] Formulário de contato envia e aparece em Mensagens no painel
- [ ] Um post de blog publicado aparece em `/blog/titulo-do-post` (URL amigável)
- [ ] Comentário de blog enviado fica pendente e só aparece após aprovação no painel
- [ ] PWA: manifest e ícones carregam (`/manifest.json`, `/icons/icon-192.png`); instalação testada em ao menos um celular Android e um iPhone

## Pós-deploy (rotina)

- [ ] Revisar `audit_logs` periodicamente (painel → Auditoria)
- [ ] Revisar mensagens de contato e comentários pendentes regularmente
- [ ] Backup do banco de dados agendado (cPanel costuma ter backup automático — confirmar)
- [ ] Repetir `composer audit` a cada 3–6 meses
