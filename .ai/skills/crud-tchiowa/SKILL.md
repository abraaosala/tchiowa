---
name: crud-tchiowa
description: Cria CRUD no padrão Tchiowa — controller, model, rotas, views BladeOne. Use ao criar entidade (Turma, Aluno, etc.)
---

# CRUD Tchiowa — Skill

## Quando usar
Ao criar nova entidade com `make:controller` + `make:model` + `routes/web.php` + `resources/views`.

## Passos
1. `php console make:model Nome` → `app/Infrastructure/Persistence/Eloquent/Nome.php` (Eloquent, `declare(strict_types=1)`, `fillable`, `casts`).
2. `php console make:migration create_nomes_table` → Phinx `creation` order, `collation utf8mb4_unicode_ci`, `created_at/updated_at` nullable.
3. `php console make:controller Nome` → `app/Http/Controllers/NomeController.php`.
4. `routes/web.php` → `/** @var Router $router */` → `$router->get('/nomes', [NomeController::class,'index'])` + `post/store`, `get/{id}/edit`, `post/{id}/update`, `post/{id}/delete` — grupo com `AuthMiddleware` se protegido.
5. Views `resources/views/nomes/{index,create,edit}.blade.php` — `extends layouts.app`, pesquisa Alpine `x-data="{q:''}" + data-search + x-show + x-cloak`, forms `max-w-[640px] mx-auto`, header `text-center`.
6. Validar com `Illuminate\Validation` — nunca `$_POST` direto.

## Checklist
- [ ] `php console migrate --seed` limpo
- [ ] `pint --test` verde
- [ ] Pesquisa instantânea sem reload
