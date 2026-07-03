import { defineConfig } from 'vite';

export default defineConfig({
    publicDir: false,
    build: {
        outDir: 'public/assets',
        emptyOutDir: true,
        rollupOptions: {
            input: 'resources/js/app.js',
            output: {
                entryFileNames: 'js/[name].js',
                assetFileNames: 'css/[name][extname]',
            },
        },
    },
});
