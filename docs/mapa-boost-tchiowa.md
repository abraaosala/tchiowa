# Mapa — Boost Tchiowa (sem Laravel puro)

> **Base:** `salaab/framework` — boilerplate em `D:\Tecnologia\Full\tchiowa` — origem do **Makanda SIGE** e de todos os projetos Salaab.
> **Objetivo:** trazer o modelo **Laravel Boost 12.x (2025/2026)** para o **Tchiowa** sem `laravel/framework`.
> Stack real: `PHP ^8.1` + `Illuminate ^10.x` soltos + `eftec/bladeone ^4.9` + `Phinx ^0.13` + `Vite + Tailwind + Alpine` — `php console` (não `artisan`).

> Leia isto em 10 min. Decida o que aprova. O scaffold vem depois em 1 PR no `tchiowa` e replica nos filhos (ex: `schooll/makanda`).

---

## 1. Por que `boost-tchiowa` e não `boost-makanda`?

```
tchiowa (salaab/framework)         ← boilerplate, 1x
   ├── schooll → Makanda SIGE      ← app filha  (pauta INADE, presença)
   ├── outro-projeto X             ← app filha
   └── outro-projeto Y             ← app filha
```

O Boost pertence ao **boilerplate**, não à app filha. Se ficar só no Makanda, cada projeto novo copia na mão. Em `tchiowa`, todo `composer create-project salaab/framework novo` já nasce com Boost.

**Regra:** `tchiowa` entrega `boost-tchiowa`. Filhos só adicionam **guidelines/skills de domínio** (ex: `pauta-inade.md` fica no Makanda, não no tchiowa).

---

## 2. Onde estamos (tchiowa hoje)

```
tchiowa/
├── console                         # Symfony Console, não artisan — Application('Tchiowa Console','1.0.0')
├── bootstrap/app.php               # Container + Config + Capsule/Eloquent + Router + UrlGenerator manuais
├── app/Helpers/helpers.php         # env(), view(), session(), flash(), redirect() (autoload files)
├── app/Console/                    # DatabaseManager, PhinxRunner, StubService, DatabaseExporter
├── app/Console/Commands/           # Serve, make:*, migrate, db:*, seed:run, etc.
├── config/*.php                    # app.php, database.php (mysql only), view.php
├── database/migrations/            # Phinx (version_order: creation) — delegates to vendor/bin/phinx
├── resources/views/                # BladeOne MODE_AUTO (eftec/bladeone) + Alpine
├── public/assets                   # Vite emptyOutDir:true (efémero — não commitar logos)
├── storage/framework/views/        # cache BladeOne (gravável)
├── vite.config.js + tailwind.config.js + resources/js/app.js
└── AGENTS.md (gitignored)          # instruções do agent — será complementado por .ai/guidelines
```

**Comandos (`php console list`):** `serve`, `make:controller|model|service|middleware|repository|view|migration|seed`, `migrate`, `rollback`, `status`, `breakpoint`, `db:create|drop|export`, `seed:run`.

**Não temos:** `laravel/framework`, `artisan`, `ServiceProvider` auto-discovery, `boost:install`.

**Logo:** `composer require laravel/boost --dev` **não funciona**. Copiamos o **padrão**, não o pacote.

---

## 3. O que o Laravel Boost faz (12.x) — 1 página

```
┌─────────────────────────────────────────────────────────┐
│  php artisan boost:install                              │
│  ├── gera AGENTS.md / CLAUDE.md / .mcp.json / boost.json│
│  ├── instala Guidelines (.ai/guidelines/*.md)           │
│  ├── instala Skills (.ai/skills/*/SKILL.md)             │
│  └── registra MCP (php artisan boost:mcp)               │
└─────────────────────────────────────────────────────────┘
                          │
          ┌───────────────┼────────────────┐
          ▼               ▼                ▼
   Guidelines         Skills            MCP Server (stdio)
   (upfront)         (on-demand)       9 tools
   sempre            só quando         Search Docs (17k docs)
                     precisa           + DB + Logs + Schema
```

| Conceito | Quando carrega | Tamanho | Exemplo Boost |
|---|---|---|---|
| **Guidelines** | arranque do agent, sempre | 30–60 linhas | `core`, `laravel 12.x`, `pint`, `pest 4.x` |
| **Skills** | sob demanda, quando faz a tarefa | 150–300 linhas | `livewire-development`, `pest-testing` |
| **MCP** | via stdio `php artisan boost:mcp` | 9 tools | `Search Docs`, `Database Query`, `Read Logs` |
| **Docs API** | via tool `Search Docs` | 17k docs com embeddings | Filament, Inertia, Livewire, Pest, Tailwind |

**Regra de ouro:** Guidelines = fundação curta (sempre). Skills = detalhe longo só quando precisa. Não poluir contexto.

---

## 4. Mapa de adaptação — Boost → Tchiowa

| Boost puro | Tchiowa (`boost-tchiowa`) | Como fica |
|---|---|---|
| `php artisan boost:install` | `php console tchiowa:boost` | Comando novo — gera `.ai/` + `.mcp.json` + `tchiowa.json` |
| `php artisan boost:mcp` | `node mcp/tchiowa/dist/index.js` **ou** `php console tchiowa:mcp` | Servidor MCP stdio |
| `laravel/framework 12.x` | `illuminate/* ^10.x` soltos | Guideline `illuminate.md` explica `Capsule` manual |
| `Blade` (Laravel) | `eftec/bladeone ^4.9` | Guideline `bladeone.md` (`MODE_AUTO`, `view()`, `helpers.php`) |
| `migrate` (Artisan) | `Phinx ^0.13` | Guideline `phinx.md` (`creation` order, additive) |
| `pest` / `pint` | `pest ^2.0` + `laravel/pint` (a adicionar) | Guidelines `pest.md` + `pint.md` |
| `AGENTS.md` gerado | `AGENTS.md` já existe (hoje gitignored) | Append em `.ai/guidelines/core.md` + `opencode.json: instructions` |
| `boost.json` | `tchiowa.json` | `{ "version":"1.0.0", "guidelines":[...], "skills":[...] }` |
| `Search Docs` (hosted Laravel) | `Context7 MCP` (já instruído) | `resolve-library-id` + `query-docs` |
| `.mcp.json` + `.ai/` no `.gitignore` | **COMMITAR** no tchiowa | Não temos `boost:update` ainda — commita |
| Domínio específico (ex: pauta) | **Fica nos filhos** | `tchiowa` só leva genérico; `makanda` adiciona `pauta-inade` |

---

## 5. Estrutura alvo (igual ao Boost, genérica)

```
.ai/
  guidelines/
    core.md              # stack, comandos, proibições — SEMPRE
    php-strict.md        # declare(strict_types=1), PSR-12, Pint
    illuminate.md        # Container manual, Capsule, with(), Scopes
    bladeone.md          # BladeOne MODE_AUTO, session/flash, helpers
    phinx.md             # migrações additive, creation order
    frontend.md          # Alpine x-data, Vite emptyOutDir, Tailwind
    pest.md              # pest ^2.0, estrutura tests/
  skills/
    crud-tchiowa/SKILL.md        # make:controller + make:model + routes/web.php
    frontend-tchiowa/SKILL.md    # resources/views + Alpine + Vite
    phinx-migration/SKILL.md     # criar migration Phinx additive

mcp/tchiowa/
  package.json
  tsconfig.json
  src/index.ts           # 4 tools mínimas

.mcp.json                # { mcpServers: { tchiowa-boost, context7 } }
tchiowa.json             # { version, guidelines, skills }
opencode.json            # { instructions: ["AGENTS.md",".ai/guidelines/core.md"], mcp:{...} }
pint.json                # { "preset":"laravel" }
phpstan.neon             # level 5, paths app/
```

**Filhos** (ex: Makanda) adicionam por cima:
```
.ai/guidelines/pauta-inade.md
.ai/guidelines/presenca.md
.ai/skills/pauta-inade/SKILL.md
.ai/skills/presenca-publica/SKILL.md
```

---

## 6. Guidelines — o que cada uma contém (30–60 linhas)

### 6.1 `core.md` — obrigatória, toda resposta usa
- Stack + `php console serve --host=0.0.0.0 --port=8000` + `php console migrate`/`seed:run` + `npm run dev`
- DB: MySQL only, `DB_DATABASE ~ /^[A-Za-z0-9_]+$/`, `storage/framework/views` gravável
- Proibido: `response()` helper → `new Response($html,$code)`, assets em `public/assets`
- Helpers: `env()` com guard, `view()` via `BladeFactory::make(MODE_AUTO)`, `session_start()` antes de `bootstrap/app.php`

### 6.2 `php-strict.md`
`declare(strict_types=1);` obrigatório, Pint `laravel`, type-hints em tudo, nunca `mixed` sem motivo, `composer validate` com `platform` pin.

### 6.3 `illuminate.md`
`bootstrap/app.php` manual vs `console` inline, `Capsule` + `Dispatcher`, `with()` anti N+1, `GlobalScope` padrão para futuro `escola_id`, `exists:turmas,id,escola_id,{$id}` anti IDOR.

### 6.4 `bladeone.md`
`BladeFactory::make(MODE_AUTO)`, helpers `view()`/`env()`/`session()`/`flash()` com guard, `session_start()` antes de bootstrap, cache `storage/framework/views/*.bladec`, `facade Route` alias.

### 6.5 `phinx.md`
`version_order: creation`, `phinx.php` lê `$_ENV` independente, migrations `database/migrations/`, seeds `database/seeds/`, nunca DROP em tabelas com dados, `change()` additive, `phinx create` via `make:migration`.

### 6.6 `frontend.md`
Alpine `x-data="{q:''}" + data-search + x-show + x-cloak`, forms `max-w-[640px] mx-auto`, `public/*` (logos) nunca em `public/assets` (Vite `emptyOutDir:true`), `resources/js/app.js` → `public/assets`, Tailwind `3.4`.

### 6.7 `pest.md`
`pestphp/pest ^2.0` + `fakerphp/faker`, sem `phpunit.xml` ainda — criar ao ativar skill, `pest --parallel`.

---

## 7. Skills — quando o agent ativa (genéricas do tchiowa)

| Skill | Gatilho | O que entrega |
|---|---|---|
| `crud-tchiowa` | `make:controller`, `make:model`, `routes/web.php`, `app/Http/Controllers` | Scaffold Controller+Model+Repository+Service+route com `AuthMiddleware` |
| `frontend-tchiowa` | `resources/views/**`, `resources/js/app.js`, `tailwind.config.js` | Paleta, `frontend-ui-engineering` + `frontend-design` + `browser-testing-with-devtools` |
| `phinx-migration` | `database/migrations`, `make:migration`, `phinx.php` | Template Phinx additive, `creation` order, FK `CASCADE` |

**Skills de domínio ficam nos filhos** (ex: Makanda): `pauta-inade`, `presenca-publica`, `secretaria`, `cofre`.

**Formato `SKILL.md` (Agent Skills spec):**
```md
---
name: crud-tchiowa
description: Cria CRUD no padrão Tchiowa — controller, model, rotas, views BladeOne — use ao criar entidade
---

# CRUD Tchiowa — Skill

## Quando usar
Ao criar `Turma`, `Aluno`, `Disciplina`, etc. — `make:controller` + `make:model`.

## Passos
1. `php console make:controller Nome`
2. `php console make:model Nome`
3. `routes/web.php` → `$router->get('/nomes', [NomeController::class,'index'])`
4. `resources/views/nomes/index.blade.php` com pesquisa Alpine
...
```

---

## 8. MCP Server — 9 tools do Boost adaptadas (genéricas)

| # | Boost original | Tchiowa | Descrição |
|---|---|---|---|
| 1 | `Application Info` | `tchiowa_info` | `php -v`, `illuminate/*` versions, lista models em `app/Infrastructure/Persistence/Eloquent` |
| 2 | `Database Schema` | `tchiowa_schema` | `SHOW TABLES` + `DESCRIBE <tabela>` via Capsule |
| 3 | `Database Query` | `tchiowa_query` | `Capsule::select()` read-only (bloqueia DROP/DELETE sem `--write`) |
| 4 | `Database Connections` | `tchiowa_db` | Lê `config/database.php` + `phinx.php` + `.env` DB_* |
| 5 | `Last Error` | `tchiowa_last_error` | `tail storage/logs/*.log` última exceção |
| 6 | `Read Log Entries` | `tchiowa_logs` | `tail -n 50 storage/logs/tchiowa.log` |
| 7 | `Search Docs` | `context7` | `resolve-library-id` + `query-docs` (Illuminate, BladeOne, Phinx, Vite, Alpine) |
| 8 | `Get Absolute URL` | `tchiowa_url` | `/alunos` → `http://127.0.0.1:8000/alunos` |
| 9 | `Browser Logs` | `browser-testing-with-devtools` skill | Já tens — não precisa tool MCP |

**Transporte:** `stdio` — `command: node` + `args: ["mcp/tchiowa/dist/index.js"]` (ou `php console tchiowa:mcp` se preferir PHP puro).

**Mínimo viável (4 tools):** `tchiowa_info` + `tchiowa_schema` + `tchiowa_query` (read-only) + `tchiowa_logs`. O resto é Fase 2.

**Implementação mínima (Node):**
```ts
// mcp/tchiowa/src/index.ts
import { Server } from "@modelcontextprotocol/sdk/server/index.js"
import { StdioServerTransport } from "@modelcontextprotocol/sdk/server/stdio.js"
import { execSync } from "node:child_process"

const server = new Server({ name: "tchiowa-boost", version: "1.0.0" })
server.setRequestHandler("tools/list", async () => ({
  tools: [
    { name: "tchiowa_info", description: "PHP/Illuminate/BladeOne/Phinx versions + models" },
    { name: "tchiowa_schema", description: "SHOW TABLES + schema" },
    { name: "tchiowa_query", description: "SELECT read-only via Capsule" },
    { name: "tchiowa_logs", description: "tail logs" },
  ]
}))
server.setRequestHandler("tools/call", async (req) => {
  if(req.params.name==="tchiowa_info") return { content: [{ type:"text", text: execSync("php -v").toString() }] }
})
await server.connect(new StdioServerTransport())
```

---

## 9. Configuração — igual ao Boost

**`.mcp.json`**
```json
{
  "mcpServers": {
    "tchiowa-boost": { "command": "node", "args": ["mcp/tchiowa/dist/index.js"] },
    "context7": { "type": "remote", "url": "https://mcp.context7.com/mcp" }
  }
}
```

**`opencode.json`**
```json
{
  "$schema": "https://opencode.ai/config.json",
  "instructions": ["AGENTS.md", ".ai/guidelines/core.md", ".ai/guidelines/php-strict.md"],
  "mcp": {
    "tchiowa-boost": { "type": "local", "command": ["node", "mcp/tchiowa/dist/index.js"], "enabled": true },
    "context7": { "type": "remote", "url": "https://mcp.context7.com/mcp" }
  }
}
```

**`pint.json`**
```json
{ "preset": "laravel" }
```

**`phpstan.neon`**
```neon
parameters:
  level: 5
  paths: [app]
```

**`tchiowa.json` (equiv. boost.json)**
```json
{ "version": "1.0.0", "guidelines": ["core","php-strict","illuminate","bladeone","phinx","frontend","pest"], "skills": ["crud-tchiowa","frontend-tchiowa","phinx-migration"] }
```

---

## 10. Fluxo de instalação (3 comandos)

```bash
# 1. Estrutura (no tchiowa)
mkdir -p .ai/guidelines .ai/skills/crud-tchiowa mcp/tchiowa/src

# 2. MCP
cd mcp/tchiowa && npm init -y && npm i @modelcontextprotocol/sdk zod
npm i -D typescript @types/node && npx tsc --init && npx tsc && cd ../..

# 3. Validar
opencode mcp list          # deve listar tchiowa-boost + context7
./vendor/bin/pint --test   # deve passar
```

Filho (Makanda) herda: `composer create-project salaab/framework makanda` já vem com `.ai/` + `.mcp.json` + `tchiowa.json`. Só adiciona `.ai/guidelines/pauta-inade.md`.

---

## 11. Roadmap incremental (não fazer tudo de uma vez)

| # | Tarefa | Entrega | Tempo | Onde |
|---|---|---|---|---|
| T-B1 | `.ai/guidelines/core.md + php-strict.md + illuminate.md` + `opencode.json` | 3 guidelines + instructions | 30 min | `tchiowa` |
| T-B2 | `pint.json` + `phpstan.neon` + `composer require laravel/pint --dev` | `pint --test` verde | 20 min | `tchiowa` |
| T-B3 | Scaffold `mcp/tchiowa` com `tchiowa_info` + `tchiowa_schema` | `node dist/index.js` lista tools | 45 min | `tchiowa` |
| T-B4 | Skills `crud-tchiowa` + `phinx-migration` (`SKILL.md`) | 2 skills com frontmatter | 30 min | `tchiowa` |
| T-B5 | Ligar `context7` como `Search Docs` oficial + `tchiowa_query` read-only | `resolve-library-id` funciona | 20 min | `tchiowa` |
| T-B6 | `tchiowa_logs` + `tchiowa_url` + docs | MCP completo (9 tools) | 30 min | `tchiowa` |
| T-B7 | Filho: `pauta-inade` + `presenca-publica` no Makanda | Domínio Makanda | 30 min | `schooll` |

**Gate:** `opencode mcp list` mostra `tchiowa-boost` + `context7`, `pint --test` passa, agent ao gerar CRUD não quebra `Container` manual.

---

## 12. Decisões para você examinar

- [ ] **Aprova a estrutura** `.ai/guidelines` + `.ai/skills` + `mcp/tchiowa` no `tchiowa`?
- [ ] **Pint preset:** `laravel` ok ou `psr12`?
- [ ] **PHPStan level:** `5` ok ou `8`?
- [ ] **MCP em Node (recomendado, igual Boost) ou PHP puro (`php console tchiowa:mcp`)?**
- [ ] **`.ai/` commita ou `.gitignore`?** (recomendo **commitar** no tchiowa — filho herda)
- [ ] **`AGENTS.md` hoje gitignored** — mantém ou commita `.ai/guidelines/core.md` como substituto versionado?
- [ ] **Faz T-B1 a T-B3 já no próximo PR no `tchiowa`?** (1h30 total)

> Diga "faz T-B1" ou "faz tudo tchiowa" que eu entrego em 1 PR no `tchiowa` e replico o essencial no `schooll`.

---

## 13. Referências

- Boost oficial: `https://laravel.com/docs/12.x/boost` (17k docs, 9 tools, Guidelines vs Skills)
- Repo Boost: `https://github.com/laravel/boost` (MIT, `composer require laravel/boost --dev`)
- Context7 MCP: `https://mcp.context7.com/mcp` (equivalente ao `Search Docs` do Boost)
- Agent Skills spec: `https://agentskills.io/what-are-skills`
- Tchiowa: `AGENTS.md`, `console`, `bootstrap/app.php`, `phinx.php`
- Makanda (filho): `schooll` — `docs/mapa-boost-makanda.md` (legado) → agora consome `boost-tchiowa`

*Salaab Framework — Tchiowa base. Boost Tchiowa — Gestão integrada. Tecnologia de Cabinda para Angola.*
