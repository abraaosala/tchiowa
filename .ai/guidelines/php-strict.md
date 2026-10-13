# PHP Strict — Guideline

> Sempre carregada com `core.md`.

- `declare(strict_types=1);` na primeira linha de todo ficheiro PHP.
- **PSR-12** — validado por **Laravel Pint** (`preset: laravel`). Nunca commitar sem `pint --test` verde.
- **Type-hints em tudo** — params, return, propriedades. Nunca `mixed` sem motivo documentado (`@param mixed` + comentário).
- **PHP `^8.1` mínimo** — usar `readonly`, `enum`, `match`, `named arguments` quando clarifica.
- **`composer validate` deve passar** — `config.platform` pin se necessário para compat `8.1` vs `8.5`.
- **Exceptions > retornos silenciosos** — `RuntimeException` para erro fatal de config/DB (ver `bootstrap/app.php`).
- **Helpers com guard** — `if (!function_exists('env'))` — definido em `console` e `bootstrap/app.php` e `helpers.php`.
- **Nunca `$_POST`/`$_GET` direto** — usar `Illuminate\Http\Request` + `Illuminate\Validation\Validator`.
