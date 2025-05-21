# Back-end de Cadastro de Usuários

Este é o **back-end** de um sistema de cadastro de usuários, desenvolvido com **Laravel 8.83.29** e **PHP 7.4.5**. Ele fornece a API necessária para que os usuários possam se registrar, editar, visualizar e excluir informações, com suporte a autenticação e autorização.

## Funcionalidades

-   **Cadastro de novos usuários**: A API permite criar novos usuários no sistema, armazenando dados como nome, e-mail, CPF, senha e endereço.
-   **Edição de dados**: Usuários com permissões de **Admin** podem editar informações dos registros.
-   **Visualização de dados**: Usuários autenticados podem visualizar suas informações, enquanto administradores podem visualizar todos os registros.
-   **Exclusão de dados**: Administradores podem excluir usuários do sistema.
-   **Autenticação de usuários**: A API oferece endpoints para login e autenticação via token JWT.

## Tecnologias Utilizadas

-   **Laravel 8.83.29**: Framework PHP para desenvolvimento da API.
-   **PHP 7.4.5**: Linguagem de programação.
-   **MySQL**: Banco de dados utilizado para persistência de dados.
-   **JWT (JSON Web Tokens)**: Sistema de autenticação para gerenciar a segurança e acesso à API.
-   **Composer**: Gerenciador de dependências PHP.

## Requisitos

Antes de rodar o projeto, é necessário ter as seguintes ferramentas instaladas:

-   [PHP 7.4.5 ou superior](https://www.php.net/)
-   [Composer](https://getcomposer.org/)
-   [MySQL](https://www.mysql.com/)
-   [Node.js](https://nodejs.org/)

## Instalação

### 1. Clone o Repositório

Clone o repositório para o seu ambiente local:

```bash
git clone https://github.com/devricardoo/user-registration-backend.git
cd user-registration-backend
```
