#!/bin/bash

# Install Composer dependencies for Laravel
echo "Installing Composer dependencies..."
composer install

# Generate Laravel app key
echo "Generating Laravel application key..."
php artisan key:generate
