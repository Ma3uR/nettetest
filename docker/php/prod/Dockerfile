ARG PHP_VERSION
ARG COMPOSER_VERSION

FROM composer:${COMPOSER_VERSION} AS composer-image
FROM php:${PHP_VERSION}-fpm

## User configuration
ARG APPLICATION_USER_ID
ARG APPLICATION_USER_GROUP
RUN echo "app-container:x:$APPLICATION_USER_ID:$APPLICATION_USER_GROUP:container,,,:/:/bin/bash" >> /etc/passwd
RUN echo "app-container:x:$APPLICATION_USER_GROUP:" >> /etc/group

## Update
RUN apt-get update
RUN apt-get update --fix-missing

## Setup configuration
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
ADD php/dev/config/* /usr/local/etc/php/conf.d/

## Install required PHP extensions
RUN docker-php-ext-install pdo pdo_mysql mysqli
RUN apt-get install -y libzip-dev zip && docker-php-ext-install zip

## Install APCu and APC backward compatibility
RUN pecl install apcu \
    && pecl install apcu_bc-1.0.3 \
    && docker-php-ext-enable apcu --ini-name 10-docker-php-ext-apcu.ini \
    && docker-php-ext-enable apc --ini-name 20-docker-php-ext-apc.ini

## Install OPCache
RUN docker-php-ext-install -j$(nproc) opcache

## Install Composer
COPY --from=composer-image /usr/bin/composer /usr/local/bin/composer
