# Auditoria de Dependências e CVEs

**Ferramenta**: `composer audit` (consulta a base de advisories do Packagist) em 28/06/2026.
**Resultado**: **nenhuma advisory de segurança encontrada** nas versões instaladas.

## Dependências PHP (Composer)

| Pacote | Versão instalada | Uso real no código | CVEs conhecidas (versão atual) |
|---|---|---|---|
| `ezyang/htmlpurifier` | v4.19.0 | ✅ Em uso (`App\Support\Sanitizer`, sanitização do conteúdo do blog) | Nenhuma — versões `<4.2.0` tinham advisories antigas, não aplicável |
| `firebase/php-jwt` | v7.1.0 | ⚠️ Vendored, **não utilizado** (reservado para futura API) | Nenhuma — versões `<7.0.0` tinham uma advisory que motivou a escolha da v7.x já na Fase 0 |
| `phpmailer/phpmailer` | v7.1.1 | ⚠️ Vendored, **não utilizado** (reset de senha é manual hoje) | Nenhuma conhecida para esta versão |

**Recomendação**: como `firebase/php-jwt` e `phpmailer/phpmailer` não têm nenhum código que os invoque ainda, eles aumentam a superfície de ataque (mais código no `vendor/`) sem benefício funcional atual. Duas opções: (a) remover do `composer.json` até serem realmente necessários (API JWT / e-mails transacionais), ou (b) mantê-los e simplesmente rodar `composer audit` periodicamente — o custo de mantê-los é baixo, mas (a) é mais alinhado ao princípio de minimizar dependências não usadas.

## Runtime

| Componente | Versão usada no ambiente de teste | Observação |
|---|---|---|
| PHP | 8.2.12 | Compatível com o requisito do projeto (PHP 8+). Build local é ZTS (thread-safe) Windows — em cPanel normalmente será NTS Linux; comportamento idêntico para este código (não há nada thread-dependente). |
| MariaDB | 10.4.32 | Compatível com MySQL 5.7+/8.0 e MariaDB 10.x — nenhuma feature usada (JSON, ENUM, FK) exclusiva de uma versão específica. |
| Apache + mod_rewrite | XAMPP local | cPanel padrão também usa Apache + mod_rewrite; `.htaccess` já testado localmente nesse mesmo empilhamento. |

## Processo recomendado para manter isso atualizado

1. Antes de cada deploy em produção, rodar `composer audit` localmente (não há `composer` no servidor, então isso é sempre um passo do processo de build, não do deploy).
2. Revisar `composer.lock` a cada 3–6 meses mesmo sem mudanças de código, já que novas CVEs podem ser descobertas em versões já instaladas.
