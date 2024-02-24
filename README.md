### Descrição

API em laravel utilizando PHP. Consiste na transferência de saldo entre contas aplicando conceitos lock pessimista, unit of work e jobs/queues para envio de e-mail. Foco da aplicação é a consistência dos dados e transações.

## Tecnologias Utilizadas
A aplicação foi desenvolvida utilizando as Tecnologias abaixo, segue links para consultas de documentação:

- [Laravel 10.x](https://laravel.com/docs/10.x) - Backend PHP Framework 
- [Docker](https://docs.docker.com/) - Aplicação Rodando em Container Docker

## Arquitetura

A arquitetura é de uma API, utilizando o framework Laravel, adicionando o Repository Pattern com uma camada de Services.

### 1. Model
Responsável pela representação e manipulação dos dados no banco de dados

### 2. Middleware
Faz o controle de autenticação da aplicação.

### 3. Controller
É a camada de entrada da aplicação. Nele são validados os parâmetros, os endpoints dos recursos e os serviços que serão utilizados para operação. O controller utiliza de FormRequests para validação dos parâmetros de entrada. 

### 4. Services
Local onde as regras de negócio da aplicação são desenvolvidas, estabelecendo a conexão entre o controlador e os repositórios.

### 5. Repositories
Funciona como uma camada de abstração para operações de banco de dados.

### 6. Queues/Jobs
São utilizados para envio de e-mails com o mock de confirmação.

### 7. Tests
São utilizados para envio de e-mails com o mock de confirmação.

## Pré-requisitos

Certifique-se de ter as seguintes ferramentas instaladas em sua máquina:

- Git
- Docker [docker](https://docs.docker.com/get-docker/)
- Docker Compose

## Instruções para Execução

### 1. Clone o Repositório

```bash
git clone <url-do-repo>
cd nome-do-repo
```

### 2. Configuração projeto

Crie a .env a partir da .env.example
```
cd backend
cp .env.example .env
```

### 3. Configuração do Docker
Na raiz do projeto, execute:

```bash
docker-compose build
docker-compose run --rm composer install --ignore-platform-reqs
docker-compose run --rm artisan key:generate
docker-compose run --rm artisan jwt:secret
docker-compose up -d
docker-compose run --rm composer init-db
```

### 4. Rota Backend

Url padrão do backend:

[http://localhost:8080](http://localhost:8080)

## Testes

Para a realização dos testes, é utilizado o [PHPUnit](https://phpunit.de/). Foram desenvolvidos testes de integração para a API. Para consultar o funcionamento dos testes com laravel, basta acessar a documentação de [Testes](https://laravel.com/docs/10.x/testing).

### Rodar testes

Para rodar os testes, basta executar dentro da pasta backend:
```bash
php artisan test
```
OU rodar com docker:
```bash
docker compose run --rm --entrypoint bash artisan
php artisan test
```
obs: bash pode ser trocado por 'sh' em caso de Sistemas diferentes.