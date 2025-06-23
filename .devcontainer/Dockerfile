# Use official PHP image with Apache and PHP 8.1 (you can adjust version)
FROM php:8.2-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    mariadb-server \
    mariadb-client \
    libzip-dev \
    unzip \
    git \
    zip \
    curl \
    && docker-php-ext-install pdo_mysql zip

# Enable Apache mod_rewrite (common for Laravel)
RUN a2enmod rewrite

# Copy your Laravel project into container (optional, depends on mount)
# WORKDIR /var/www/html

# Copy startup script
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["apache2-foreground"]
