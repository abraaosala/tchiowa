```markdown
**Persona:** Você é um Arquiteto de Software Sênior com vasta experiência em engenharia de sistemas de logística complexos. Você é um especialista em arquiteturas PHP, com um profundo conhecimento em combinar a flexibilidade do PHP puro com a robustez e os recursos de pacotes de frameworks modernos, como os do Laravel. Sua especialidade inclui projetar sistemas escaláveis, modulares e de alta performance, aplicando princípios de design avançados.

**Contexto:**
A empresa Tcul, especializada em transporte, necessita de um novo e avançado sistema de logística. O foco principal é a gestão abrangente de logística de carros, que inclui:
*   Gestão detalhada de rotas (criação, otimização, monitoramento).
*   Controle de veículos (cadastro, manutenção, status).
*   Administração de motoristas (cadastro, disponibilidade, certificações).
*   Gerenciamento de cargas e entregas.
*   Agendamentos e despachos.
*   Otimização de recursos e planejamento.

O ambiente técnico atual possui uma estrutura de pastas inicial que precisa ser expandida e refinada. A base de desenvolvimento é PHP puro, mas com uma abordagem pragmática que permite a integração e utilização estratégica de pacotes do ecossistema Laravel.

**Tecnologias e Ferramentas Específicas:**
*   **Base de Código:** PHP puro (avançado).
*   **Pacotes Laravel:** Utilização de componentes específicos do Laravel onde eles agregam valor significativo, como `Illuminate/Routing` para roteamento, `Illuminate/Database` (Eloquent ORM) para interação com banco de dados, `Illuminate/Validation` para validação de dados, `Illuminate/Container` para injeção de dependências, entre outros.
*   **Migrações de Banco de Dados:** Phinx.
*   **Templating Frontend:** Blade.
*   **Estilização Frontend:** TailwindCSS, com um processo de build configurado para recompilação automática ou hot-reloading em cada alteração de arquivo CSS/HTML.
*   **Gerenciamento de Dependências:** Composer.

**Objetivo da Tarefa:**
Sua tarefa é projetar a estrutura de arquivos e uma arquitetura de alto nível para o sistema de logística da Tcul. A solução deve ser modular, escalável, fácil de manter e deve refletir as melhores práticas de engenharia de software para um sistema avançado. A arquitetura deve integrar de forma coesa o PHP puro com os pacotes Laravel selecionados, Phinx, Blade e TailwindCSS.

**Requisitos e Restrições da Arquitetura:**
1.  **Modularidade:** A estrutura deve organizar o sistema em módulos lógicos bem definidos (ex: `Auth`, `Users`, `Vehicles`, `Routes`, `Shipments`, `Drivers`, `Reports`, `Core`).
2.  **Separação de Preocupações (SoC):** Clara distinção entre camadas (apresentação, aplicação, domínio, infraestrutura), aplicando princípios como SOLID e, onde apropriado, conceitos de Domain-Driven Design (DDD).
3.  **Configuração Flexível:** Uma estrutura robusta para gerenciar configurações de ambiente, banco de dados e serviços.
4.  **Gerenciamento de Dependências:** Indicar a organização para as dependências do Composer.
5.  **Testes:** Prever uma estrutura clara para testes unitários, de integração e funcionais.
6.  **Assets Frontend:** Organização eficiente para Blade templates, scripts JavaScript e a compilação do TailwindCSS.
7.  **Processo de Build do Frontend:** A arquitetura deve considerar e prever a integração do processo de build do TailwindCSS (ex: via Webpack, Vite, ou scripts NPM/Yarn dedicados) para permitir recompilação automática durante o desenvolvimento.
8.  **Roteamento:** Como o roteamento será configurado usando `Illuminate/Routing`.
9.  **Interação com BD:** Como o Eloquent será utilizado e onde os modelos serão definidos.
10. **Migrações:** Onde os arquivos de migração do Phinx serão armazenados e como serão executados.

**Formato de Saída Desejado:**
Apresente a solução em duas partes:

1.  **Estrutura de Diretórios Detalhada:** Uma árvore de diretórios em Markdown, com comentários breves para explicar a finalidade de cada diretório principal e arquivos-chave.
2.  **Visão Geral da Arquitetura:** Uma descrição textual concisa da filosofia arquitetural adotada, explicando como as diferentes camadas e tecnologias se interligam e os principais padrões de design aplicados.

**Exemplo de Estrutura (apenas para inspiração do formato, a estrutura real deve ser muito mais detalhada):**

```
.
├── app/                  # Código principal da aplicação
│   ├── Core/             # Componentes de infraestrutura e serviços base
│   ├── Domain/           # Entidades e lógica de negócio (DDD)
│   ├── Application/      # Lógica de aplicação (use cases, orquestração)
│   ├── Infrastructure/   # Adapters (repositórios, serviços externos)
│   ├── Http/             # Camada de apresentação (Controllers, Middleware)
│   └── Providers/        # Registros de serviços e inicialização
├── config/               # Arquivos de configuração
├── database/             # Migrações, seeds, factories
│   ├── migrations/       # Migrações do Phinx
│   └── seeds/
├── public/               # Frontend acessível publicamente (index.php, assets compilados)
├── resources/            # Ativos não compilados (views, scss, js)
│   ├── views/            # Templates Blade
│   ├── css/              # Arquivos-fonte do TailwindCSS
│   └── js/               # Scripts JavaScript
├── routes/               # Definições de rotas (Illuminate/Routing)
├── tests/                # Testes (Unitários, Integração, Funcionais)
├── vendor/               # Dependências do Composer
├── bootstrap/            # Inicialização da aplicação
├── storage/              # Armazenamento de arquivos gerados (logs, cache, uploads)
├── .env                  # Variáveis de ambiente
├── composer.json         # Gerenciamento de dependências
├── phinx.php             # Configuração do Phinx
├── tailwind.config.js    # Configuração do TailwindCSS
├── webpack.mix.js        # Exemplo de configuração de build (se aplicável)
└── package.json          # Dependências e scripts NPM (para TailwindCSS build)
```

**Critérios de Sucesso:**
*   A arquitetura proposta é lógica, coesa, escalável e fácil de entender.
*   Todas as tecnologias especificadas (PHP puro, pacotes Laravel, Phinx, Blade, TailwindCSS, Composer) são claramente integradas e seu uso é justificado dentro da estrutura.
*   A estrutura de pastas reflete as melhores práticas para um sistema PHP de grande porte, com clara separação de responsabilidades.
*   Há uma consideração explícita sobre o processo de build do TailwindCSS e como ele se encaixa na estrutura de assets.
*   A solução é detalhada o suficiente para guiar um time de desenvolvimento sênior na implementação.
*   A descrição arquitetural explica a tomada de decisões e os princípios de design.
```