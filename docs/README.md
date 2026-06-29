# Documentação Final — Portal AGD Niger

Documentação produzida ao final da Fase 4 (hardening, pentest, CVEs e documentação), cobrindo as Fases 0–4 do projeto de modernização do portal.

| Documento | Conteúdo |
|---|---|
| [01-arquitetura-e-banco.md](01-arquitetura-e-banco.md) | Estrutura de pastas, padrão MVC, DER completo de todas as 31 tabelas, RBAC |
| [02-seguranca-owasp.md](02-seguranca-owasp.md) | Cobertura OWASP Top 10/ASVS, hardening de headers/cookies, limitações conhecidas |
| [03-pentest.md](03-pentest.md) | Pentest simulado real (não só teórico) — SQLi, XSS, CSRF, IDOR, upload bypass, path traversal, open redirect, SSRF, session hijacking, com evidências |
| [04-cves.md](04-cves.md) | Auditoria de CVEs das 3 dependências vendored (nenhuma encontrada) |
| [05-deploy-cpanel.md](05-deploy-cpanel.md) | Passo a passo de deploy em hospedagem compartilhada sem SSH |
| [06-checklist-producao.md](06-checklist-producao.md) | Checklist de homologação e go-live |
| [07-performance.md](07-performance.md) | Gargalos conhecidos e recomendações |

O documento de arquitetura **proposta** (aprovado antes da Fase 0) está fora deste repositório, em `C:\Users\rafae\.claude\plans\reactive-crunching-crystal.md` — os documentos aqui descrevem o que foi **efetivamente construído e testado**.

## Resumo executivo

- **4 fases entregues**: Fundação/segurança → Painel/Configurações/Conteúdo institucional → 7 módulos de conteúdo (Equipe, Depoimentos, Projetos, Apadrinhamento, Livros, Mídia, Blog) + contato → Doações unificadas + PWA → Hardening/Pentest/Documentação.
- **31 tabelas**, RBAC com 4 perfis e 16 permissões, CSRF + rate limiting + lockout + auditoria completa.
- **Pentest simulado sem achados exploráveis** nos 12 vetores testados (ver `03-pentest.md`).
- **Zero CVEs conhecidas** nas dependências vendored.
- **2 pendências que dependem do cliente, não de código**: (1) e-mail/ID PayPal real precisa ser cadastrado no painel para a doação online funcionar; (2) revisar se `firebase/php-jwt` e `phpmailer` devem ser mantidos vendored sem uso ou removidos até serem necessários.
