module.exports = {
    content: [
        "./resources/views/**/*.blade.php",
        "./resources/js/**/*.js",
        "./public/*.php",
    ],
    darkMode: 'class',
    theme: {
        extend: {
            colors: {
                dark: {
                    bg: '#0f172a',
                    secondary: '#1e293b',
                    card: '#1e293b',
                    border: '#334155',
                    input: '#111827'
                }
            }
        },
    },
    plugins: [],
};

