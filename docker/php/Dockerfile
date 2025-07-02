FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git \
    curl \
    libzip-dev \
    unzip \
    default-mysql-client \
    zlib1g-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev

RUN docker-php-ext-install pdo_mysql mbstring zip gd dom pcntl bcmath opcache

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www