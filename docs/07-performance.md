# Notas de Performance

Sem requisito de Redis/cache (por restrição de hospedagem compartilhada), a estratégia adotada foi:

## O que já está otimizado

- **Cache estático por requisição**: `Settings::get()` e `i18n` carregam uma vez por request (cache em memória estática), não uma query por chamada.
- **Índices no banco**: todas as FKs (auto-indexadas pelo InnoDB), mais índices explícitos em `audit_logs(action, created_at)`, `blog_posts(status, published_at)`, `login_attempts(identifier, created_at)`, `rate_limit_hits(rate_key, window_start)`, `menus(location, sort_order)`, `donation_methods/paypal_accounts(country_scope, sort_order)`.
- **PWA / Service Worker**: cache-first para CSS/JS/imagens reduz requisições repetidas em visitas subsequentes.
- **Reencode de imagens no upload**: imagens enviadas pelo painel são recomprimidas (JPEG qualidade 85, PNG nível 6), o que tende a reduzir o tamanho de arquivo comparado ao upload original.

## Gargalos conhecidos (aceitáveis para o volume esperado, registrados para o futuro)

1. **Sem cache de página/opcode HTTP** — cada visita reexecuta todas as queries da página (settings, menus, conteúdo). Para o tráfego esperado de um site institucional de ONG, isso não deve ser perceptível; se o tráfego crescer, a opção mais simples sem infraestrutura nova é cache em arquivo (`storage/cache/`, já reservado) para Settings/Menus com invalidação no save.
2. **N+1 em `UserController::index`** — uma query de perfis por usuário listado. Irrelevante para listas de até algumas dezenas de usuários administrativos (caso real desta ONG); seria um problema apenas com centenas de contas.
3. **`blog.publishedPaginated()`** roda 2 queries (lista + count) por página — padrão aceitável para paginação simples, sem necessidade de otimizar agora.
4. **Galeria de imagens de projetos** não tem lazy-loading nem geração de thumbnail separada — serve a imagem original em tamanho real no grid. Para poucas imagens por projeto (uso esperado) não é um problema; se a galeria crescer muito, vale gerar thumbnails no upload (a mesma rotina de reencode já existente poderia ser estendida).

## Recomendação de monitoramento pós-deploy

Sem ferramenta de APM nesta fase (fora do escopo/restrições). Recomenda-se acompanhar manualmente, via cPanel, o tempo de resposta e o uso de CPU/memória do plano de hospedagem nas primeiras semanas após o lançamento, e revisar esta lista se o tráfego real superar o estimado.
