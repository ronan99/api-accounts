### Descrição

API em laravel utilizando PHP. Consiste na transferência de saldo entre contas aplicando conceitos lock pessimista, unit of work e jobs/queues para envio de e-mail. Foco da aplicação é a consistência dos dados e transações.

## Tecnologias Utilizadas
A aplicação foi desenvolvida utilizando as Tecnologias abaixo, segue links para consultas de documentação:

- [Laravel 10.x](https://laravel.com/docs/10.x) - Backend PHP Framework 
- [Docker](https://docs.docker.com/) - Aplicação Rodando em Container Docker

## Arquitetura

A arquitetura é feita utilizando o MVC do laravel(Sem views), adicionando o Repository Pattern com uma camada Services.

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
docker-compose run --rm composer install
docker-compose run --rm artisan key:generate
docker-compose run --rm artisan jwt:secret
docker-compose up -d
```

### 4. Rota Backend

Para acessar o backend, abra o navegador e vá para:

[http://localhost:8080](http://localhost:8080)

Endpoint da API de items: 

[http://localhost:8080/api/items](http://localhost:8080/api/items)