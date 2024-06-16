
# Projeto API MVC em PHP com Docker

Este é um projeto de uma API baseada em um padrão MVC simples, usando PHP, PDO e SQLite. A aplicação é dockerizada e pode ser iniciada usando o Docker Compose.

## Estrutura do Projeto

```
project/
├── app/
│   ├── Controllers/
│   │   └── UserController.php
│   ├── Models/
│   │   └── User.php
├── config/
│   └── config.php
├── core/
│   ├── Config.php
│   ├── Controller.php
│   ├── Model.php
├── db/
│   └── db.sqlite
├── docker/
│   ├── nginx/
│   │   └── default.conf
│   └── php/
│       ├── Dockerfile
│       ├── conf.d/
│       └── entrypoint.sh
├── docker-compose.yml
└── public/
    └── index.php
```

## Configuração e Início do Projeto

### Pré-requisitos

- Docker
- Docker Compose

### Instruções

1. Clone este repositório:

   ```sh
   git clone https://github.com/ikarolaborda/php-poc-api.git
   cd php-poc-api
   ```

2. Certifique-se de que o arquivo `db/db.sqlite` existe. Se não, o `entrypoint.sh` cuidará de criar o banco de dados na primeira execução.

3. Inicie os containers Docker:

   ```sh
   docker-compose up --build
   ```

4. A aplicação estará disponível em `http://localhost`.

### Arquivo `docker-compose.yml`

```yaml
services:

   php:
      platform: linux/amd64
      container_name: php
      build:
         context: ./docker/php/
         dockerfile: Dockerfile
      ports:
         - '9000:9000'
      volumes:
         - ./:/var/www/mvc
         - ./docker/php/conf.d/xdebug.ini:/usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
         - ./docker/php/conf.d/error_reporting.ini:/usr/local/etc/php/conf.d/error_reporting.ini
      networks:
         - dev

   nginx:
      platform: linux/amd64
      container_name: nginx
      image: nginx:latest
      ports:
         - '80:80'
      volumes:
         - ./docker/nginx/default.conf:/etc/nginx/conf.d/default.conf
         - ./:/var/www/mvc
      depends_on:
         - php
      networks:
         - dev

networks:
   project-dev:
      driver: bridge
```

### Arquivo `docker/php/entrypoint.sh`

```sh
#!/bin/sh

DB_PATH="/var/www/mvc/db/db.sqlite"

# Check if db.sqlite exists
if [ ! -f "$DB_PATH" ]; then
    echo "Database not found. Creating db.sqlite..."
    sqlite3 "$DB_PATH" <<EOF
CREATE TABLE users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    email TEXT NOT NULL UNIQUE,
    password TEXT NOT NULL
);
EOF
    echo "Database created."
fi

exec docker-php-entrypoint php-fpm
```

## Endpoints da API

### Listar Todos os Usuários

**Endpoint:** `GET /user`

**Resposta de Sucesso:**
```json
[
    {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com"
    },
    {
        "id": 2,
        "name": "Jane Doe",
        "email": "jane@example.com"
    }
]
```

### Obter Detalhes de um Usuário

**Endpoint:** `GET /user/show/{id}`

**Resposta de Sucesso:**
```json
{
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com"
}
```

**Resposta de Erro:**
```json
{
    "error": "User not found"
}
```

### Criar um Novo Usuário

**Endpoint:** `POST /user/store`

**Payload:**
```json
{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "securepassword"
}
```

**Resposta de Sucesso:**
```json
{
    "message": "User created"
}
```

### Atualizar um Usuário

**Endpoint:** `POST /user/update/{id}`

**Payload:**
```json
{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "newpassword"
}
```

**Resposta de Sucesso:**
```json
{
    "message": "User updated"
}
```

### Deletar um Usuário

**Endpoint:** `POST /user/delete/{id}`

**Resposta de Sucesso:**
```json
{
    "message": "User deleted"
}
```
