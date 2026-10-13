# Core — Tchiowa Base

> Guideline sempre carregada. Stack, comandos e proibições do boilerplate `salaab/framework`.

## Stack
- **PHP `^8.4`** — `declare(strict_types=1);` obrigatório em todo ficheiro.
- **Illuminate `^10.x` soltos** — `illuminate/container`, `database` (Eloquent/Capsule), `routing`, `validation`, `config`, `events`, `filesystem`, `http`, `pagination`, `view`. Sem `laravel/framework`.
- **BladeOne `^4.9` (`eftec/bladeone`)** — `MODE_AUTO`, cache `storage/framework/views` (gravável).
- **Phinx `^0.13` (robmorgan/phinx)** — `version_order: creation`, `phinx.php` lê `$_ENV` independente.
- **Vite `^8.1` + Tailwind `^3.4` + Alpine `^3.15`** — `npm run dev` (`vite build --watch`) via `php console serve`.

## Comandos
```bash
php console serve [--host=127.0.0.1] [--port=8000]  # PHP -S + Vite watch (se package.json existir)
php console list                                    # lista comandos
php console make:controller|model|service|middleware|repository|view <Nome>
php console make:migration <nome>                   # vendor/bin/phinx create
php console make:seed <nome>                        # seed:create
php console migrate [--seed] [--target=] [--dry-run] [--fake]  # phinx migrate (auto-cria DB MySQL)
php console migrate:status | rollback | breakpoint
php console db:create | db:drop | db:export [-o file.sql]
php console seed:run | db:seed
```

## Proibições
- **Nunca `response()` helper** — não existe. Usar `new \Symfony\Component\HttpFoundation\Response($html, $code)` + `view()->run()`.
- **Nunca lógica de domínio duplicada** — fonte única é `app/Domain/...` (ex: `CalculoPauta` nos filhos).
- **Nunca assets em `public/assets`** — Vite `emptyOutDir:true` apaga tudo. Logos/favicons em `public/`, uploads em `storage/`.
- **MySQL only** — `DatabaseManager::assertValidName` exige `DB_DATABASE ~ /^[A-Za-z0-9_]+$/`.

## Helpers (`app/Helpers/helpers.php` — autoload `files`)
`env($key, $default)` com guard `function_exists` + coerção `"true"/"false"/"null"/"empty"`. `view($name,$data)` → `BladeFactory::make(MODE_AUTO)->run()`. `session()`, `flash()`, `old()`, `redirect()`. `session_start()` obrigatório antes de `bootstrap/app.php` em `public/index.php`.

## Config
`config/*.php` → `Illuminate\Config\Repository` no `Container`. `config/database.php` só `mysql`, `config/view.php` paths + `storage/framework/views`, `config/app.php` `name`/`debug` via `env()`.

## Before UI
Antes de mexer em `resources/views`, `resources/js`, `resources/css`, `tailwind.config.js`: carregar `frontend-ui-engineering` + `frontend-design` e validar com `browser-testing-with-devtools`.
