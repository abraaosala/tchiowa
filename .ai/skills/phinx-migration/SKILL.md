---
name: phinx-migration
description: Cria migration Phinx additive no padrão Tchiowa. Use ao criar/alterar database/migrations
---

# Phinx Migration — Skill

## Quando usar
Ao criar `database/migrations/*` via `php console make:migration`.

## Regras
- `version_order: creation` — prefixo `YYYYMMDDHHMMSS` define ordem.
- `change()` preferível; `up()/down()` só se irreversível.
- `collation utf8mb4_unicode_ci`, `addColumn('created_at','datetime',['null'=>true])` sempre.
- Additive — nunca `DROP`/`removeColumn` em tabela com dados sem migração de dados.
- Validar: `php console migrate --dry-run` + `migrate:status`.
- `phinx.php` lê `$_ENV` independente — fallback `logistics`/`development`.
