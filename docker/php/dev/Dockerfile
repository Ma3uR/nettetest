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
RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"
ADD php/dev/config/* /usr/local/etc/php/conf.d/

## Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql mysqli
RUN apt-get install -y libzip-dev zip --fix-missing
RUN docker-php-ext-configure zip
RUN docker-php-ext-install zip

## Install Composer
COPY --from=composer-image /usr/bin/composer /usr/local/bin/composer

## Install debugger
RUN pecl install xdebug-2.9.8 \
    && docker-php-ext-enable xdebug

## Prepare directory to have ability run tests with coverage via PHPStorm
RUN mkdir /opt/phpstorm-coverage
RUN chown $APPLICATION_USER_ID:$APPLICATION_USER_GROUP /opt/phpstorm-coverage
