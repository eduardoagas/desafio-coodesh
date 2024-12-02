## Guia de Instalação

### Pré-Requisitos

- Docker instalado.

### Instruções

1. **Clone o repositório.**

2. **Renomeie o arquivo `.env.example` para `.env`.**

3. **Adicione a API key do WORDS API**  
   No arquivo `.env`, adicione a chave da API do WORDS API:

   ```env
   WORDS_API_KEY=
   ```

4. **Inicie os contêineres Docker**  
   No terminal, acesse o diretório clonado e execute o comando:

   ```bash
   docker compose up --build -d
   ```

5. **Acesse o terminal do contêiner PHP**  
   Ainda no diretório clonado, use o comando abaixo para entrar no terminal do contêiner PHP:

   ```bash
   docker compose exec app bash
   ```

6. **Navegue até a pasta da aplicação Laravel**  

   No terminal do contêiner, entre na pasta da aplicação Laravel:

   ```bash
   cd application
   ```

7. **Instale as dependências**  
   Execute o comando para instalar as dependências. Caso necessário, adicione o parâmetro `COMPOSER_PROCESS_TIMEOUT=1200`:

   ```bash
   composer install
   ```

8. **Execute as migrações do banco de dados**  
   Configure as tabelas do banco de dados usando o comando:

   ```bash
   php artisan migrate
   ```

9. **Importe as palavras**  
   Por fim, execute o comando abaixo para importar as palavras:

   ```bash
   php artisan app:importar-palavras
   ```