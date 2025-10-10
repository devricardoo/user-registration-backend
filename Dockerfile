FROM php:8.3.0-cli

ARG user=laravel
ARG uid=1000

RUN apt-get update && apt-get install -y \
    git \
    curl \
    unzip \
    zip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libicu-dev \
    g++ \
    make \
    autoconf \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalador prático de extensões PHP
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/install-php-extensions

# Extensões PHP essenciais para Laravel
RUN install-php-extensions \
    gd \
    pdo_mysql \
    zip \
    mbstring \
    exif \
    pcntl \
    bcmath \
    intl

# Composer oficial
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Criar usuário para rodar o app
RUN useradd -G www-data,root -u $uid -d /home/$user $user \
    && mkdir -p /home/$user/.composer \
    && chown -R $user:$user /home/$user

WORKDIR /var/www

COPY . .

RUN mkdir -p storage/{app/{public,uploads},framework/{cache,sessions,views},logs} \
    && chmod -R 777 storage bootstrap/cache \
    && chown -R $user:$user /var/www

EXPOSE 8000

CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]
