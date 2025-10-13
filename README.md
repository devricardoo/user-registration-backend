<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# 📦 User Registration Backend

Projeto de backend para cadastro de usuários utilizando Laravel, JWT e Docker.

## Instalação

## 1. Clone o repositório

No seu terminal, rode o comando para clonar o repositório:

```bash
git clone https://github.com/devricardoo/user-registration-backend.git
cd user-registration-backend
```

Abra o arquivo no seu editor de código

## 2. Criar e iniciar um container

Rode o seguinte código para criar e iniciar um container Docker:

```bash
docker compose up -d
```

## 3. Acessar o contêiner

No terminal, rode o comando para acessar o contêiner já criado:

```bash
docker compose exec -it user-registration bash
```

Basicamente, esse comando abre um shell Bash dentro do container do serviço já em execução, permitindo rodar comandos diretamente no ambiente do container.

## 4. Instalar dependências com o Composer

Com o shell Bash aberto dentro do contêiner, rode o seguinte comando para instalar as dependências do Laravel:

```bash
composer install
```

## 5. Alterar arquivo .env para rodar as Migrations

Após o clone do repositório, renomeie o arquivo .env.example para .env. Em seguida, coloque os dados do MySql com base no banco de dados que está configurado no arquivo docker-compose.yml. Exemplo no .env:

```bash
DB_CONNECTION=mysql
DB_HOST=db  # Nome do serviço no docker-compose.yml
DB_PORT=3306
DB_DATABASE=nome_do_banco
DB_USERNAME=user
DB_PASSWORD=password
```

## 6. Gerar chave de aplicação

O Laravel utiliza a chave de aplicação (APP_KEY) para manter os dados seguros, incluindo a criptografia de senhas e dados sensíveis da aplicação. Essa chave é única para cada projeto e deve ser gerada antes de rodar a aplicação. Em seu terminal, rode o comando dentro do contêiner:

```bash
php artisan key:generate
```

## 7. Gerar chave JWT

Para que o sistema de autenticação via JWT funcione corretamente, é necessário gerar uma chave secreta que será usada para assinar os tokens.

Execute o seguinte comando dentro do contêiner Laravel:

```bash
php artisan jwt:secret
```

## 8. Rodar as Migrations

Com o banco de dados configurado no arquivo .env, rode o comando dentro do contêiner:

```bash
php artisan migrate
```

## 9. Rodar Seeder

Dentro do contêiner, rode os seeders para popular o banco de dados com dados iniciais:

```bash
php artisan db:seed --class=ProfileSeeder
php artisan db:seed --class=UserSeeder
```
