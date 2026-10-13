# Frontend — Guideline

- **Build:** `Vite ^8.1` — `npm run dev` = `vite build --watch`, `npm run build` = `vite build`. `vite.config.js`: `outDir: public/assets`, `emptyOutDir:true` **apaga tudo em `public/assets` a cada build** — nunca commitar logos/favicons lá, usar `public/` ou `storage/`.
- **Entry:** `resources/js/app.js` importa `../css/app.css` + `alpinejs ^3.15`. Tailwind `^3.4` escaneia `resources/views/**/*.blade.php`, `resources/js/**/*.js`, `public/*.php`.
- **Dev:** `php console serve` já corre `npm run dev` (Windows via `npm.cmd run dev`).
- **Antes de UI:** carregar `frontend-ui-engineering` + `frontend-design` e validar com `browser-testing-with-devtools`. Sem isto não mexer em `resources/views`, `resources/js`, `resources/css`, `tailwind.config.js`.
- **Padrões:** Alpine `x-data="{q:''}" + data-search + x-show + x-model + x-cloak` para pesquisa instantânea; forms `max-w-[640px] mx-auto`, header `text-center`, botões `justify-center`; layout pauta 286px + sidebar Alpine.
