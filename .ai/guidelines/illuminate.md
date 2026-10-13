# Illuminate — Guideline

> Container manual — não é `laravel/framework`.

## Container
- `bootstrap/app.php` retorna `Container` — lê `config/*.php` → `ConfigRepository`, boota `Capsule` + `Dispatcher`, bind `Session` + `FlashService`, cria `Router` + `UrlGenerator` (re-bind com request em `public/index.php`).
- `console` faz setup próprio inline — `Container` + `Capsule` + `Config` sem `Events/Router/UrlGenerator/Whoops`.
- `Facade::setFacadeApplication($container)` para `Route` alias.

## Eloquent / Capsule
```php
$capsule = new Capsule($container);
$capsule->addConnection($config->get('database.connections.mysql'));
$capsule->setEventDispatcher($events);
$capsule->setAsGlobal(); $capsule->bootEloquent();
Model::setConnectionResolver($capsule->getDatabaseManager());
```

## Boas práticas
- **Sempre `with()`** — evitar N+1. `with(['turma','disciplina'])`.
- **Scopes** — `GlobalScope` para futuro `escola_id` multi-tenant. Validar `exists:turmas,id,escola_id,{$id}` anti IDOR.
- **Não usar `DB::table` facade** — Capsule é `$capsule->getDatabaseManager()` ou `Model::query()`.
- **Transacções** — `Capsule::transaction(fn() => ...)` para `matricular+gerarPropinas`, etc.
- **Bindings** — `singleton` para `Session`/`FlashService`, `instance` para `config`/`router`/`url`/`db`.
