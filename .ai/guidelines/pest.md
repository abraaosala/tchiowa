# Pest — Guideline

- **Deps:** `pestphp/pest ^2.0` + `fakerphp/faker` em `require-dev`. Sem `phpunit.xml`/`tests/` por padrão (só stubs em `vendor/`). Criar `phpunit.xml` + `tests/` ao activar esta guideline.
- **Run:** `./vendor/bin/pest` ou `php vendor/bin/pest --parallel`. `pint.json` preset `laravel` deve estar verde antes.
- **Estrutura:** `tests/Feature` (HTTP/routing) + `tests/Unit` (Domain/Services) + `tests/Pest.php` com `uses(TestCase::class)->in('Feature')`.
- **Factories:** usar `fakerphp/faker` para dados — não hardcodar.
