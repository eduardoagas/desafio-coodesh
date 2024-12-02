## Guia de Instalação

### Pré-Requisitos

- Docker instalado.

### Instruções

1. Clone o repositório.

2. Renomeie o arquivo `.env.example` na pasta application para `.env`.**

3. No arquivo `.env`, adicione a chave da API do WORDS API:

   ```env
   WORDS_API_KEY=
   ```

4. No terminal, acesse o diretório clonado e execute o comando:

   ```bash
   docker compose up --build -d
   ```

5. Ainda no diretório clonado, use o comando abaixo para entrar no terminal do contêiner PHP:

   ```bash
   docker compose exec app bash
   ```

6. No terminal do contêiner, entre na pasta da aplicação Laravel:

   ```bash
   cd application
   ```

7. Execute o comando para instalar as dependências. Caso necessário, adicione o parâmetro `COMPOSER_PROCESS_TIMEOUT=1200`:

   ```bash
   composer install
   ```

8. Configure as tabelas do banco de dados usando o comando:

   ```bash
   php artisan migrate
   ```

9. Execute o comando para importar as palavras:

   ```bash
   php artisan app:importar-palavras
   ```