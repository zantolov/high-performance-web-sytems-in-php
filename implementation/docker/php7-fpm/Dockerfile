# See https://github.com/docker-library/php/blob/4677ca134fe48d20c820a19becb99198824d78e3/7.0/fpm/Dockerfile
FROM php:7.0-fpm

MAINTAINER Maxence POUTORD <maxence.poutord@gmail.com>

RUN apt-get update && apt-get install -y \
    git \
    unzip

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer --version

RUN docker-php-ext-install pdo pdo_mysql

# Opcache
RUN docker-php-ext-install opcache

ADD custom.ini /usr/local/etc/php/conf.d/custom.ini

RUN useradd -m symfony
USER symfony

WORKDIR /var/www/symfony
