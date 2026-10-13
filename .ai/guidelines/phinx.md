# Phinx — Guideline

- **Runner:** `vendor/bin/phinx` via `App\Console\PhinxRunner::run()` + `DatabaseManager` (PDO MySQL sem DB selecionado). `phinx.php` na raiz lê `$_ENV` independente (fallback `DB_DATABASE=logistics`, `env=development`).
- **Ordem:** `version_order: creation` em `phinx.php` — prefixo `YYYYMMDDHHMMSS` define ordem.
- **Pastas:** `database/migrations/`, `database/seeds/` — criadas por `make:migration`/`make:seed` → `vendor/bin/phinx create`.
- **Regra:** migrações **additive** — nunca `DROP`/`removeColumn` em tabelas com dados sem migração de dados. `change()` preferível, `up()/down()` quando irreversível.
- **Validação:** `DB_DATABASE ~ /^[A-Za-z0-9_]+$/` em `DatabaseManager::assertValidName` — falha se nome inválido.
- **Comandos:** `migrate` pergunta `Y/n` se `DB_DATABASE` não existe; `php vendor/bin/phinx <action>` também funciona.

## Template
```php
declare(strict_types=1);
use Phinx\Migration\AbstractMigration;
final class CreateXTable extends AbstractMigration {
  public function change(): void {
    $this->table('xs', ['collation'=>'utf8mb4_unicode_ci'])
      ->addColumn('nome','string',['limit'=>120])
      ->addColumn('created_at','datetime',['null'=>true])
      ->addColumn('updated_at','datetime',['null'=>true])
      ->create();
  }
}
```
