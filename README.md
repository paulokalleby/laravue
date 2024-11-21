# Laravue Fullstack

Bolerplate fullstack Laravel e Vue JS com multi-tenancy e ACL

## Como rodar o projeto

Clonar repositório do github

```sh
git clone git@github.com:paulokalleby/laravue.git
```

## Backend

```sh
cd laravue/laravue-api
```

Copiar as varieveis de ambiente

```sh
cp .env.example .env
```

Subir containers do projeto

```sh
./vendor/bin/sail up -d
```

Instalar dependências

```sh
./vendor/bin/sail composer i
```

Gere a chave do projeto Laravel

```sh
./vendor/bin/sail artisan key:generate
```

Execute a migração do banco de dados e popule a tabela de permissões

```sh
./vendor/bin/sail artisan migrate --seed
```

Acesse a documentação da api
[http://localhost:8080](http://localhost:8080)

## Frontend

```sh
cd laravue/laravue-web
```

Subir aplicação em container nginx

```sh
docker-compose up -d
```

Acesse a aplicação frontend
[http://localhost:5174](http://localhost:5174)
