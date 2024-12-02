﻿# desafio-coodesh

Pré-Requistos

Docker instalado.

Instruções


Clone o repositório.

Altere o nome do arquivo ".env.example" para ".env".

No .env, adicione a api key do WORDS API em WORDS_API_KEY=.

Acesse o diretório  clonado pelo o terminal gere os contêineres do docker utilizando o comando:

docker compose up --build -d

Ainda no diretório clonado, acesse o terminal dentro contêiner do PHP usando o comando:

docker compose exec app bash

Entre na pasta da aplicação Laravel:

cd application

E execute o comando para instalar as dependências: (usar o parâmetro COMPOSER_PROCESS_TIMEOUT=1200 se necessário)

composer install

Execute as migrações do banco de dados:

php artisan migrate

Importe as palavras usando o comando:

php artisan app:importar-palavras
