# CabeleleilaLeila

## Deploy AWS
*   http://54.207.5.102/

## Descrição

Este é um projeto desenvolvido com Laravel e Vite para o teste técnico da empresa DSIN, que consiste em um sistema de gerenciamento de um salão de beleza, onde é possível cadastrar clientes, funcionários e agendar horários.

## Tecnologias Utilizadas

*   **Linguagem de Programação:** PHP 8.1
*   **Framework:** Laravel 9.x
*   **Frontend:** JavaScript (ES6), Vite
*   **Banco de Dados:** MySQL
*   **Deploy:** AWS EC2

## Pré-requisitos

Antes de começar, certifique-se de ter instalado:

*   PHP >= 8.1
*   Composer
*   Node.js >= 16
*   NPM ou Yarn
*   MySQL

## Instruções de Instalação

1.  Clone o repositório:

    ```
    git clone https://github.com/vitucs/CabeleleilaLeila.git
    cd CabeleleilaLeila
    ```

2.  Instale as dependências do Composer:

    ```
    composer install
    ```

3.  Copie o arquivo `.env.example` para `.env` e configure as variáveis de ambiente:

    ```
    cp .env.example .env
    nano .env
    ```

    Configure as informações do banco de dados, chave da aplicação, etc.

4.  Gere a chave da aplicação:

    ```
    php artisan key:generate
    ```

5.  Execute as migrações e seeders:

    ```
    php artisan migrate
    php artisan db:seed
    ```

6.  Instale as dependências do Node.js:

    ```
    npm install
    ```

7.  Compile os assets com Vite:

    ```
    npm run build
    ```

## Executando a Aplicação

Para executar a aplicação, você pode usar o servidor embutido do PHP:

