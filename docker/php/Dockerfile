FROM php:8.4-rc-fpm-alpine

# dev dependencies
RUN apk add --no-cache --virtual .build-deps \
    $PHPIZE_DEPS

# dependencies
RUN apk --no-cache update && apk --no-cache add \
    curl \
    git \
    bash \
    libpq-dev \
    zip \
    unzip \
    sqlite-dev \
    libzip-dev \
    linux-headers

# Install and configure Xdebug
RUN pecl install xdebug && docker-php-ext-enable xdebug

# Install necessary PHP extensions
RUN docker-php-ext-install \
    pdo \
    pdo_pgsql \
    opcache

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer