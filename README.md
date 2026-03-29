# Salaab Base Framework 🚀

Um mini-framework em PHP prático, leve e moderno, construído utilizando os poderosos e flexíveis componentes do Eloquent (Illuminate) e Symfony Console. Esta base funciona como um esqueleto (boilerplate) pronto para o ínicio de qualquer projeto PHP com uma estrutura organizada (MVC/CSR).

## 🧰 Requisitos

- PHP `^8.1` ou superior
- [Composer](https://packagist.org/packages/salaab/framwork) instanciado na máquina

## 📦 Como Instalar

Para iniciar um projeto novo utilizando esta estrutura base, execute no terminal:

```bash
composer create-project salaab/base nome-do-meu-projeto
```

Isso irá clonar todo este espaço de trabalho com os arquivos mínimos e dependências instaladas.

## 🚀 Como Executar

Durante o desenvolvimento, o seu ambiente possibilita rodar o servidor embutido do PHP junto com a observação (watch) e compilação do Front-End (caso utilize NPM/Vite/Tailwind). Exiba a sua aplicação no navegador através do comando:

```bash
php console serve
```
**(Isso liga tanto o seu emulador de PHP no endereço `http://127.0.0.1:8000` quanto, caso encontre um `package.json`, inicia automaticamente a stream do ambiente NPM).**

Você pode especificar uma porta ou host diferente, caso seu ambiente de desenvolvimento exija:
```bash
php console serve --host=0.0.0.0 --port=8080
```

## 🏗 Arquitetura & Comandos Disponíveis

Este ecossistema vem com várias ferramentas incluídas baseadas no utilitário de terminal `php console` (substituto prático para o 'php artisan').

Na raiz do seu projeto recém-criado, você pode verificar todos os comandos disponíveis utilizando:

```bash
php console list
```

Os utilitários principais incluídos são:
* **`make:*`** - Modelos para criar Controllers, Services, Middleware, Repositories, Models de Banco, etc.
* **`db:*`** e **`migrate:*`** - Ferramentas para Criação, Migrações e Seeds do Banco de Dados.

## 🗂 Estrutura de Rotas e Banco de Dados

* Suas **Variáveis de Ambiente** ficam no `.env` (basta renomear o `.env.example` caso não exista ainda).
* Suas **Rotas** vivem limpas e simplificadas dentro de `routes/web.php`.

## 🤝 Contribuindo

Pull requests são sempre bem-vindos! Para grandes mudanças, por favor adicione uma "issue" primeiramente para discutir de que forma seria mais viável efetuar a sua ideia.

## 📝 Licença

**Proprietária**. (Desenvolvido por Abraão Xavier Sungo Sala - Nsimba Engenharia).
