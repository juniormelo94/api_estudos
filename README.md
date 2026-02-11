<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# API com Laravel

## Baixar projeto 

````
git clone git@github.com:juniormelo94/api_reino_company.git
````
ou
````
git clone https://github.com/juniormelo94/api_reino_company.git
````

## Banco de dados
### Criar um banco de dados(mysql) com as seguintes configurações:

```` 
Database: reino_company
Username: root
Password: 
````

## Obrigatório

- PHP 8.2.

## Instalação

### Etapa 1: Renomeie arquivo .env

```` 
De: ".env.dev"
Para: ".env"
````
### Etapa 2: Instalar dependências via composer

```` 
composer install
````

### Etapa 3: Gerar tabelas no banco de dados

```` 
php artisan migrate
````

### Etapa 4: Iniciar servidor

```` 
php artisan serve
````

## Consumo da API

### Registrar Usuário Inicial

- **POST [http://127.0.0.1:8000/api/registrar](http://127.0.0.1:8000/api/registrar)**

Request:
```` 
POST http://127.0.0.1:8000/api/registrar

{
    "hash": "abc123",
    "name": "John Doe",
    "email": "john@doe.com",
    "password": "123456",
    "password_confirmation": "123456"
}
````
Response:
```` 
{
  "status": true,
  "token_type": "Bearer",
  "token": "1|aMsCGgwoVdX4WCutTJbbddjFilUPGS0s5EIBISRb94a6fade",
  "data": {
    "id": 2,
    "name": "John Doe",
    "email": "john@doe.com",
    "email_verified_at": null,
    "created_at": "2024-12-01T18:50:33.000000Z",
    "updated_at": "2024-12-01T18:50:33.000000Z",
    "colaborador_user": {
      "id": null,
      "tipo_user": null,
      "permissoes": null,
      "divisoes_ids": null,
      "instalacoes_ids": null,
      "colaboradores_id": null,
      "users_id": null,
      "status": null
    }
  }
}
````

### Logar

- **POST [http://127.0.0.1:8000/api/logar](http://127.0.0.1:8000/api/logar)**

Request:
```` 
http://127.0.0.1:8000/api/logar

{
    "email": "john@doe.com",
    "password": "123456"
}
````
Response:
```` 
{
  "status": true,
  "token_type": "Bearer",
  "token": "1|aMsCGgwoVdX4WCutTJbbddjFilUPGS0s5EIBISRb94a6fade",
  "data": {
    "id": 3,
    "name": "John Doe",
    "email": "john@doe.com",
    "email_verified_at": null,
    "created_at": "2024-12-01T18:52:22.000000Z",
    "updated_at": "2024-12-01T18:52:22.000000Z",
    "colaborador_user": {
      "id": null,
      "tipo_user": null,
      "permissoes": null,
      "divisoes_ids": null,
      "instalacoes_ids": null,
      "colaboradores_id": null,
      "users_id": null,
      "status": null
    }
  }
}
````

### Deslogar

- **POST [http://127.0.0.1:8000/api/deslogar](http://127.0.0.1:8000/api/deslogar)**<br>
  Authorization: Bearer + token

Request:
```` 
http://127.0.0.1:8000/api/deslogar
Authorization: Bearer 1|aMsCGgwoVdX4WCutTJbbddjFilUPGS0s5EIBISRb94a6fade
````
Response:
```` 
{
  "status": true,
  "message": "Deslogado com sucesso."
}
````

### Buscar todos logs(com filtros)

- **GET [http://127.0.0.1:8000/api/logs?por_pagina=10&codigo_erro=0&criado_de=2024-12-01&criado_ate=2024-12-31](http://127.0.0.1:8000/api/logs?por_pagina=10&codigo_erro=0&criado_de=2024-12-01&criado_ate=2024-12-31)**<br>
  Authorization: Bearer + token

Request:
```` 
http://127.0.0.1:8000/api/logs?por_pagina=10&codigo_erro=0&criado_de=2024-12-01&criado_ate=2024-12-31
Authorization: Bearer 1|aMsCGgwoVdX4WCutTJbbddjFilUPGS0s5EIBISRb94a6fade
````
Response:
```` 
{
  "status": true,
  "data": [
    {
      "id": 1,
      "mensagem_erro": "Undefined property: App\\Http\\Controllers\\Api\\AuthController::$usserRepository",
      "codigo_erro": "0",
      "arquivo_erro": "C:\\xampp\\htdocs\\api_reino_company\\app\\Http\\Controllers\\Api\\AuthController.php",
      "linha_erro": "46",
      "rastreamento_erro": "#0 C:\\xampp\\htdocs\\api_reino_company\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Bootstrap\\HandleExceptions.php(256): Illuminate\\Foundation\\Bootstrap\\HandleExceptions->handleError(2, 'Undefined prope...')",
      "created_at": "2024-12-01T19:03:25.000000Z",
      "updated_at": "2024-12-01T19:03:25.000000Z"
    }
  ]
}
````

### Buscar log pelo id

- **GET [http://127.0.0.1:8000/api/logs/{id}](http://127.0.0.1:8000/api/logs/{id})**<br>
  Authorization: Bearer + token

Request:
```` 
http://127.0.0.1:8000/api/logs/1
Authorization: Bearer 1|aMsCGgwoVdX4WCutTJbbddjFilUPGS0s5EIBISRb94a6fade
````
Response:
````
{
  "status": true,
  "data": {
    "id": 1,
    "mensagem_erro": "Undefined property: App\\Http\\Controllers\\Api\\AuthController::$usserRepository",
    "codigo_erro": "0",
    "arquivo_erro": "C:\\xampp\\htdocs\\api_reino_company\\app\\Http\\Controllers\\Api\\AuthController.php",
    "linha_erro": "46",
    "rastreamento_erro": "#0 C:\\xampp\\htdocs\\api_reino_company\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Bootstrap\\HandleExceptions.php(256): Illuminate\\Foundation\\Bootstrap\\HandleExceptions->handleError(2, 'Undefined prope...')",
    "created_at": "2024-12-01T19:03:25.000000Z",
    "updated_at": "2024-12-01T19:03:25.000000Z"
  }
}
````

### Deletar log pelo id

- **DELETE [http://127.0.0.1:8000/api/logs/{id}](http://127.0.0.1:8000/api/logs/{id})**<br>
  Authorization: Bearer + token

Request:
```` 
http://127.0.0.1:8000/api/logs/1
Authorization: Bearer 1|aMsCGgwoVdX4WCutTJbbddjFilUPGS0s5EIBISRb94a6fade
````
Response:
```` 
{
  "status": true,
  "message": "Log deletado com sucesso."
}
````

### Buscar todas divisões(com filtros)

- **GET [http://127.0.0.1:8000/api/divisoes?por_pagina=10&pesquisar=a&status=ativo&criado_de=2024-12-01&criado_ate=2024-12-31](http://127.0.0.1:8000/api/divisoes?por_pagina=10&pesquisar=a&status=ativo&criado_de=2024-12-01&criado_ate=2024-12-31)**<br>
  Authorization: Bearer + token

Request:
```` 
http://127.0.0.1:8000/api/divisoes?por_pagina=10&pesquisar=a&status=ativo&criado_de=2024-12-01&criado_ate=2024-12-31
Authorization: Bearer 1|aMsCGgwoVdX4WCutTJbbddjFilUPGS0s5EIBISRb94a6fade
````
Response:
```` 
{
  "status": true,
  "data": [
    {
      "id": 1,
      "nome": "NATURA",
      "ramo": "PERFUMARIA E COSMETICOS",
      "cnpj": null,
      "cor_primaria": "Laranja",
      "cor_secundaria": null,
      "cor_tercearia": null,
      "logo_img": null,
      "status": "Ativo",
      "created_at": "2024-12-01T19:23:27.000000Z",
      "updated_at": "2024-12-01T19:23:27.000000Z"
    }
  ]
}
````

### Criar divisão

- **POST [http://127.0.0.1:8000/api/divisoes](http://127.0.0.1:8000/api/divisoes)**<br>
  Authorization: Bearer + token

Request:
```` 
http://127.0.0.1:8000/api/divisoes
Authorization: Bearer 1|aMsCGgwoVdX4WCutTJbbddjFilUPGS0s5EIBISRb94a6fade

{
  "nome*": "NATURA",
  "ramo*": "PERFUMARIA E COSMETICOS",
  "cnpj": null,
  "cor_primaria*": "Laranja",
  "cor_secundaria": null,
  "cor_tercearia": null,
  "logo_img": null,
  "status*": "Ativo"
}
````
Response:
```` 
{
  "status": true,
  "data": {
    "id": 1,
    "nome": "NATURA",
    "ramo": "PERFUMARIA E COSMETICOS",
    "cnpj": null,
    "cor_primaria": "Laranja",
    "cor_secundaria": null,
    "cor_tercearia": null,
    "logo_img": null,
    "status": "Ativo",
    "created_at": "2024-11-17T02:31:11.000000Z",
    "updated_at": "2024-11-17T02:31:11.000000Z"
  }
}
````

### Buscar divisão pelo id

- **GET [http://127.0.0.1:8000/api/divisoes/{id}](http://127.0.0.1:8000/api/divisoes/{id})**<br>
  Authorization: Bearer + token

Request:
```` 
http://127.0.0.1:8000/api/divisoes/1
Authorization: Bearer 1|aMsCGgwoVdX4WCutTJbbddjFilUPGS0s5EIBISRb94a6fade
````
Response:
````
{
  "status": true,
  "data": {
    "id": 1,
    "nome": "NATURA",
    "ramo": "PERFUMARIA E COSMETICOS",
    "cnpj": null,
    "cor_primaria": "Laranja",
    "cor_secundaria": null,
    "cor_tercearia": null,
    "logo_img": null,
    "status": "Ativo",
    "created_at": "2024-11-17T02:31:11.000000Z",
    "updated_at": "2024-11-17T02:31:11.000000Z"
  }
}
````

### Atualizar divisão pelo id

- **PUT [http://127.0.0.1:8000/api/divisoes/{id}](http://127.0.0.1:8000/api/divisoes/{id})**<br>
  Authorization: Bearer + token

Request:
```` 
http://127.0.0.1:8000/api/divisoes/1
Authorization: Bearer 1|aMsCGgwoVdX4WCutTJbbddjFilUPGS0s5EIBISRb94a6fade

{
  "nome*": "NATURA",
  "ramo*": "PERFUMARIA E COSMETICOS",
  "cnpj": null,
  "cor_primaria*": "Laranja",
  "cor_secundaria": null,
  "cor_tercearia": null,
  "logo_img": null,
  "status*": "Ativo"
}
````
Response:
```` 
{
  "status": true,
  "data": {
    "id": 1,
    "nome": "NATURA",
    "ramo": "PERFUMARIA E COSMETICOS",
    "cnpj": null,
    "cor_primaria": "Laranja",
    "cor_secundaria": null,
    "cor_tercearia": null,
    "logo_img": null,
    "status": "Ativo",
    "created_at": "2024-11-17T02:31:11.000000Z",
    "updated_at": "2024-11-17T02:31:11.000000Z"
  }
}
````

### Deletar divisão pelo id

- **DELETE [http://127.0.0.1:8000/api/divisoes/{id}](http://127.0.0.1:8000/api/divisoes/{id})**<br>
  Authorization: Bearer + token

Request:
```` 
http://127.0.0.1:8000/api/divisoes/1
Authorization: Bearer 1|aMsCGgwoVdX4WCutTJbbddjFilUPGS0s5EIBISRb94a6fade
````
Response:
```` 
{
  "status": true,
  "message": "Divisão deletada com sucesso."
}
````