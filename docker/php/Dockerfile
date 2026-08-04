FROM php:8.3.0-fpm

ARG user=laravel
ARG uid=1000

RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    autoconf \
    g++ \
    make \
    && pecl install xdebug redis \
    && docker-php-ext-enable xdebug redis \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Copia configurações personalizadas
COPY xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini
COPY php.ini /usr/local/etc/php/conf.d/custom.ini

# Instalador prático de extensões
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/install-php-extensions
RUN install-php-extensions gd pdo_mysql zip mysqli

# Extensões adicionais
RUN docker-php-ext-install mbstring exif pcntl bcmath

# Composer oficial
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Criação do usuário do sistema
RUN useradd -G www-data,root -u $uid -d /home/$user $user \
    && mkdir -p /home/$user/.composer \
    && chown -R $user:$user /home/$user

WORKDIR /var/www/html

# Garante que as pastas de storage/cache existam e tenham permissões de escrita
RUN mkdir -p /var/www/html/storage/framework/cache/data \
             /var/www/html/storage/framework/sessions \
             /var/www/html/storage/framework/views \
             /var/www/html/storage/logs \
             /var/www/html/bootstrap/cache \
    && chmod -R 777 /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 9000

CMD ["php-fpm"]