FROM php:8.2-cli

# Install system dependencies
RUN apt-get update && apt-get install -y \
    unzip zip git curl libzip-dev libpng-dev libonig-dev libxml2-dev \
    libsqlite3-dev libpq-dev libjpeg-dev libfreetype6-dev \
    default-mysql-client \
    nodejs npm gnupg ca-certificates lsb-release wget

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql mbstring zip exif pcntl bcmath

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /workspace

# Optional: install npm and build tools
RUN npm install -g npm@latest
