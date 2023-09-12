FROM php:8.2-fpm

RUN apt-get update -y && apt-get upgrade -y && apt-get install -y curl git zip unzip vim \
        libfreetype6-dev \
        libtidy-dev \
        libzip-dev \
    && docker-php-ext-install -j$(nproc) mysqli pdo_mysql exif tidy zip

# BC Math
RUN docker-php-ext-install bcmath

# PHP Redis extension
RUN pecl install redis
RUN docker-php-ext-enable redis

# Human Language and Character Encoding Support
RUN apt-get install -y zlib1g-dev libicu-dev g++
RUN docker-php-ext-configure intl
RUN docker-php-ext-install intl

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Clean up
RUN apt-get clean
RUN apt-get -y autoremove
RUN rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

RUN useradd -ms /bin/bash manson

USER manson

WORKDIR /var/www/api
