FROM php:8.2-cli

# Install dependencies
RUN apt-get update && apt-get install -y \
    git zip unzip libzip-dev libonig-dev libpng-dev libcurl4-openssl-dev pkg-config libssl-dev mysql-client \
    && docker-php-ext-install pdo_mysql zip mbstring

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /workspace
