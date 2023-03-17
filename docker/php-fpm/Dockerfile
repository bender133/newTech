FROM php:8.0-fpm

RUN apt-get update && apt-get install -y \
    libpq-dev \
    libonig-dev \
        curl \
        wget \
        git \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libmcrypt-dev \
        libpng-dev \
		libzip-dev \
        libz-dev libmemcached-dev \
        memcached libmemcached-tools \
    && pecl install memcached \
    && docker-php-ext-enable memcached \
    && docker-php-ext-install -j$(nproc) iconv mbstring mysqli pdo_mysql zip \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install sockets

# composer.
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Xdebug
RUN apt-get update && apt-get install -y wget git unzip \
    && pecl install xdebug-3.0.1 \
    && docker-php-ext-enable xdebug\
    && pecl install redis \

WORKDIR /var/www/html