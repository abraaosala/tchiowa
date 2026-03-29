<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salaab Framework - Construa Rápido</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/app.css">
    
    <style>
        /* Animações e Extensões Customizadas (Além do Tailwind Base) */
        @keyframes slideDown {
            from { transform: translateY(-20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        @keyframes slideUp {
            from { transform: translateY(30px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        .animate-slide-down { animation: slideDown 0.6s ease-out forwards; }
        .animate-slide-up { animation: slideUp 0.8s ease-out forwards; }
        .delay-200 { animation-delay: 200ms; }
        .delay-400 { animation-delay: 400ms; }
        .delay-600 { animation-delay: 600ms; }
        .delay-800 { animation-delay: 800ms; }
    </style>
</head>
<body class="bg-[#0b0e14] text-gray-100 min-h-screen flex flex-col relative overflow-x-hidden font-sans">
    
    <!-- Background Radial Gradients -->
    <div class="fixed inset-0 z-[-2] pointer-events-none" style="background-image: radial-gradient(circle at top right, rgba(168,85,247,0.15), transparent 40%), radial-gradient(circle at bottom left, rgba(6,182,212,0.15), transparent 40%);"></div>

    <!-- Glow flutuante central -->
    <div class="absolute -top-32 left-1/2 -translate-x-1/2 w-[300px] h-[300px] bg-gradient-to-r from-purple-500 to-cyan-500 blur-[150px] opacity-40 z-[-1] animate-pulse"></div>
    
    <div class="container mx-auto px-5 py-16 flex-1 flex flex-col justify-center items-center text-center z-10 max-w-4xl">
        
        <!-- Badge -->
        <div class="opacity-0 animate-slide-down bg-white/5 border border-white/5 px-4 py-1.5 rounded-full text-sm text-gray-400 mb-8 backdrop-blur-md">
            v1.0 • Desenvolvido com padrão elegante
        </div>
        
        <!-- Titulo Principal -->
        <h1 class="opacity-0 animate-slide-up delay-200 text-5xl md:text-7xl font-black leading-tight tracking-tight mb-6">
            O Seu Novo Espaço<br>Começa <span class="bg-gradient-to-br from-purple-500 to-cyan-500 text-transparent bg-clip-text">Aqui.</span>
        </h1>
        
        <!-- Subtitulo -->
        <p class="opacity-0 animate-slide-up delay-400 text-lg md:text-xl text-gray-400 max-w-2xl mb-14 leading-relaxed">
            Bem-vindo ao Salaab Framework. Uma arquitetura leve, estruturada com o poder do ecosistema Illuminate (Laravel) e componentes minimalistas para focar puramente no seu projeto.
        </p>
        
        <!-- Cards de Features -->
        <div class="opacity-0 animate-slide-up delay-600 grid grid-cols-1 md:grid-cols-3 gap-6 w-full text-left">
            
            <div class="bg-white/5 border border-white/5 rounded-2xl p-8 backdrop-blur-md transition-all duration-300 hover:-translate-y-1 hover:bg-white/10 hover:border-white/10">
                <div class="text-3xl mb-4">⚡</div>
                <h3 class="text-xl font-bold mb-3 text-white">Moderno & Rápido</h3>
                <p class="text-gray-400 text-sm leading-relaxed">
                    Nenhuma configuração oculta. Um sistema de Base (Boilerplate) totalmente pronto para criar, migrar e gerir APIs e Telas perfeitamente.
                </p>
            </div>
            
            <div class="bg-white/5 border border-white/5 rounded-2xl p-8 backdrop-blur-md transition-all duration-300 hover:-translate-y-1 hover:bg-white/10 hover:border-white/10">
                <div class="text-3xl mb-4">🗂</div>
                <h3 class="text-xl font-bold mb-3 text-white">Arquitetura Limpa</h3>
                <p class="text-gray-400 text-sm leading-relaxed">
                    Organizado em Controllers, Services e Repositórios. Escalável independentemente do tamanho da tarefa ao dispor.
                </p>
            </div>
            
            <div class="bg-white/5 border border-white/5 rounded-2xl p-8 backdrop-blur-md transition-all duration-300 hover:-translate-y-1 hover:bg-white/10 hover:border-white/10">
                <div class="text-3xl mb-4">🛠</div>
                <h3 class="text-xl font-bold mb-3 text-white">Console Integrado</h3>
                <p class="text-gray-400 text-sm leading-relaxed">
                    O `php console` carrega diversas ferramentas que automatizam a criação de Models e gerem banco de dados num estalar de dedos.
                </p>
            </div>

        </div>

        <!-- Bloco de Comando -->
        <div class="opacity-0 animate-slide-up delay-800 bg-black/80 px-6 py-4 rounded-xl border border-white/10 font-mono text-emerald-400 text-sm inline-flex items-center gap-3 mt-14 shadow-2xl">
            <span class="text-gray-500 select-none">$</span> php console serve
        </div>
        
    </div>

    <!-- Footer -->
    <footer class="w-full text-center py-8 text-gray-500 text-sm border-t border-white/5 mt-auto">
        Desenvolvido com padrão internacional em Angola por 
        <a href="https://github.com/abraaosala" target="_blank" class="text-gray-200 font-medium hover:text-purple-400 transition-colors">Abraão Sala</a> | Nsimba Engenharia © 2026
    </footer>

</body>
</html>
