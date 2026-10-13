# BladeOne — Guideline

- **Motor:** `eftec/bladeone ^4.9` — não é Laravel Blade. `App\Core\View\BladeFactory::make(): BladeOne` com `MODE_AUTO`, cache `storage/framework/views` auto-criado (deve ser gravável).
- **Render:** `view('nome', $data)` → `blade()->run('nome', $data)` → HTML. Helpers `blade()`, `view()`, `session()`, `flash()`, `old()`, `redirect()` em `app/Helpers/helpers.php` (autoload `files`).
- **Globals partilhadas:** `session`, `flash`, `errors` (`flash()->getError()`), `success`.
- **HTTP:** `public/index.php` → `session_start()` obrigatório → `bootstrap/app.php` → `Request::capture()` → `$router->dispatch()` → aceita `Symfony Response`, `Psr Response` (via `HttpFoundationFactory`) ou `string`.
- **Layouts:** `resources/views/layouts/*.blade.php` + `resources/views/components/*.blade.php`.
- **Nunca** usar `@livewire`, `@volt` ou diretivas Livewire — não existem no BladeOne.
