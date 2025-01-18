FROM php:8.0-fpm

# Install necessary extensions
RUN docker-php-ext-install pdo pdo_mysql

# Copy your application files
COPY . /usr/share/nginx/html

WORKDIR /usr/share/nginx/html