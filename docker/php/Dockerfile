FROM php:8.3-fpm

# Atualizar o sistema e instalar dependências necessárias
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    locales \
    jpegoptim \
    optipng \
    pngquant \
    gifsicle \
    vim \
    git \
    curl \
    zip \
    unzip \
    libpq-dev \
    libzip-dev \
    libexif-dev \
    libonig-dev \
    && rm -rf /var/lib/apt/lists/*

# Instalar extensões PHP necessárias
RUN docker-php-ext-install pdo pdo_pgsql mbstring zip exif pcntl
RUN docker-php-ext-install gd

# Instalar o Composer globalmente
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Adicionar usuário Laravel (usando IDs específicos)
RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

# Definir o diretório de trabalho
WORKDIR /var/www

# Copiar o código do projeto Laravel para dentro do container
COPY . /var/www

# Ajustar permissões para o diretório do Laravel
RUN chown -R www:www /var/www

# Garantir que o Composer tenha permissões corretas
RUN chmod -R 755 /var/www

# Mudar para o usuário 'www'
USER www

# Expor a porta do PHP-FPM (9000)
EXPOSE 9000

# Iniciar o PHP-FPM
CMD ["php-fpm"]
