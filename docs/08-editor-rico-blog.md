# Editor Rico do Blog (Quill) — Implementação e Análise de Segurança

## O que foi implementado

O campo "Conteúdo" do formulário de post (`/admin/blog/novo` e `/admin/blog/{id}/editar`) passou de um `<textarea>` simples para um editor visual estilo Word, usando **Quill.js 1.3.6** (MIT, sem dependência de nuvem/API key, hospedado localmente em `admin/assets/quill/`, carregado só nessa tela do painel).

**Barra de ferramentas** (deliberadamente restrita): títulos H2–H4, negrito, itálico, sublinhado, tachado, lista numerada/com marcadores, citação, link, imagem (por URL), limpar formatação. Não há cor de texto, cor de fundo, alinhamento ou fonte — essas opções dependem de `style`/`class` inline, que o sanitizador remove de qualquer forma, então omiti-las da barra evita "perder formatação silenciosamente" e mantém a lista de permissões do sanitizador pequena e auditável.

## Arquitetura da sanitização

```
Admin digita no Quill → JS copia o HTML do editor para um <textarea> oculto
→ POST normal do formulário → App\Support\Sanitizer::richText() (HTMLPurifier)
→ valor sanitizado é o que é gravado no banco e exibido no site público
```

**Ponto central**: o editor no navegador é só conveniência de UX. Ele **não é o limite de segurança** — qualquer um pode pular o JavaScript inteiramente e enviar HTML arbitrário direto pro endpoint (como um atacante de fato faria). Por isso toda a validação real está no `App\Support\Sanitizer`, que roda no servidor, de forma independente do que o editor permite ou não na interface.

Allowlist atual (`app/Support/Sanitizer.php`):
```
p, br, strong, em, b, i, u, s, ul, ol, li, a[href], img[src|alt], h2, h3, h4, blockquote, span
```
- Nenhum elemento tem `style` ou `class` permitido — isso por si só elimina toda uma categoria de ataque (CSS-based XSS, exfiltração via seletor CSS, `background:url(javascript:...)`) sem precisar de uma allowlist de propriedades CSS.
- `URI.AllowedSchemes` restrito a `http`, `https`, `mailto` — link com `javascript:`, `data:`, `vbscript:` etc. nunca sobrevive.
- `HTML.TargetBlank` + `TargetNoopener` + `TargetNoreferrer` ativos — todo link vira `target="_blank" rel="noopener noreferrer"` automaticamente (mitiga "reverse tabnabbing").

## Testes realizados

### 1. Navegador real (Playwright/Chromium headless)
- Login → `/admin/blog/novo` → barra de ferramentas renderiza (13 controles, todos clicáveis sem erro de JavaScript).
- Formatação aplicada via a API real do Quill (mesmo caminho de código que um clique real na barra de ferramentas usa): título H2, negrito, lista com marcadores — todos sobreviveram corretamente até a página pública.
- Injeção de `<img src=x onerror="window.__xssFired=true">` e `<script>` direto no DOM do editor (simulando um paste malicioso que ignora a barra de ferramentas) → confirmado que `window.__xssFired` **nunca** ficou `true` numa renderização real de navegador da página pública. Essa é a prova mais forte possível: não foi só checagem de string, foi execução real de JavaScript que nunca disparou.
- Screenshot do formulário capturado e revisado visualmente.

### 2. Bateria de 14 payloads direto no backend (sem passar pelo editor)
| Payload | Resultado |
|---|---|
| `<script>` (literal, maiúsculas, via entidade HTML) | Removido/neutralizado nos 3 casos |
| `<a href="javascript:alert(1)">` | Atributo `href` removido, link sobra sem destino |
| `<svg onload=...>` | Tag inteira removida |
| `<style>...javascript:...</style>` | Tag inteira removida |
| `<iframe src="javascript:...">` | Tag inteira removida |
| `<object>` / `<embed>` | Ambas removidas |
| `<form>` / `<input>` | Ambas removidas |
| `<meta http-equiv="refresh" ...>` (redirect) | Removido |
| `<img src="data:text/html,...">` | Tag inteira removida (esquema `data:` não permitido) |
| `style="background:url(javascript:...)"` em `<p>` | Atributo removido, texto do parágrafo mantido |
| Tentativa de "escape" de atributo via aspas em texto puro | Inofensivo — confirmação de que um sanitizador baseado em parser de DOM (não regex/string) é estruturalmente imune a esse truque, já que texto dentro de um nó nunca pode "voltar" a se tornar atributo de uma tag já fechada |
| 2 payloads legítimos (formatação completa, link real) | Preservados corretamente, incluindo `target="_blank" rel="noopener noreferrer"` automático |

**Resultado: 0 dos 12 payloads maliciosos sobreviveu; os 2 payloads legítimos foram preservados sem perda de formatação.**

## Mudanças correlatas

- **CSP do painel** (`SecurityHeadersMiddleware`): `img-src` passou a aceitar `https:` (qualquer host), além de `'self'` e `data:`, só para o preview ao vivo de uma imagem externa inserida pelo botão de imagem do editor. Isso é restrito ao contexto autenticado do `/admin` — o site público não tem CSP ainda (fora do escopo desta mudança) e não é afetado.
- **`s` (tachado)** adicionado à allowlist do sanitizador — único tag novo introduzido; todo o resto da allowlist já existia.

## Riscos residuais identificados (nenhum corrigível só com sanitização de HTML)

1. **Confiança no `password_hash`/sessão do admin continua sendo o limite real de quem pode chegar a esse formulário.** Um editor rico não aumenta nem reduz esse risco — RBAC (`blog.manage`) já gate-keepa quem chega na tela.
2. **Imagem por URL não baixa/valida o conteúdo remoto** — um admin pode colar a URL de uma imagem que depois desapareça ou mude de conteúdo no host de origem (hotlinking). Não é uma falha de segurança da aplicação (o `<img>` em si não pode executar JS), mas é um risco de confiabilidade de conteúdo a registrar.
3. **`<img src>` com URL relativa inválida (ex.: `src="x"`) sobrevive ao sanitizador** — gera só um ícone de imagem quebrada na página, sem risco de segurança, mas é um lembrete de que o sanitizador valida sintaxe/esquema, não se a imagem existe ou é apropriada.
4. **HTMLPurifier em si é uma dependência de terceiros** — já consta em `docs/04-cves.md` sem advisories conhecidas na versão atual (v4.19.0); deve continuar sendo revisado a cada `composer audit`.

## Conclusão

Nenhuma falha de segurança explorável foi encontrada no editor rico nem na sanitização que o acompanha. O desenho (sanitização server-side independente do editor, parser de DOM em vez de regex, allowlist mínima sem `style`/`class`) é a abordagem correta e já vinha sendo seguida desde a Fase 2 — esta mudança ampliou a allowlist em exatamente um item (`s`) e adicionou uma interface melhor sobre o mesmo limite de segurança que já existia.
