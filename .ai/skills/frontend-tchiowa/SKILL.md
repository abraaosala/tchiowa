---
name: frontend-tchiowa
description: UI do Tchiowa — BladeOne + Vite + Tailwind + Alpine. Use ao mexer em resources/views, resources/js, tailwind.config.js
---

# Frontend Tchiowa — Skill

## Quando usar
Ao criar/alterar `resources/views/**`, `resources/js/app.js`, `resources/css/app.css`, `tailwind.config.js`, `vite.config.js`.

## Regras
- Antes de codar: carregar `frontend-ui-engineering` + `frontend-design` e validar com `browser-testing-with-devtools`.
- **Vite:** `outDir public/assets`, `emptyOutDir:true` — nunca pôr logos/favicons em `public/assets` (usar `public/`).
- **Alpine:** pesquisa `x-data="{q:''}"` + `data-search` no card + `x-show` + `x-model` + `x-cloak`.
- **Forms:** `max-w-[640px] mx-auto`, header `text-center`, botões `justify-center`.
- **Tailwind:** escaneia `resources/views/**/*.blade.php`, `resources/js/**/*.js`, `public/*.php`.

## Verificar
`npm run build` → `public/assets` regenerado, `public/*` intacto.
