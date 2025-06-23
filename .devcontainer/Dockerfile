FROM mcr.microsoft.com/vscode/devcontainers/php:8.2

# Install composer globally if not already installed
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /workspace

# Any additional setup can go here
