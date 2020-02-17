FROM php:7.4-fpm

# Install modules
RUN apt-get update && apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libicu-dev \
        wget \
        git \
        libzip-dev \
        libonig-dev \
            --no-install-recommends

RUN docker-php-ext-install zip intl mbstring pdo_mysql exif \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

COPY ./install-composer.sh /
COPY ./www.conf /usr/local/etc/php/

RUN apt-get purge -y g++ \
    && apt-get autoremove -y \
    && rm -r /var/lib/apt/lists/* \
    && rm -rf /tmp/* \
    && sh /install-composer.sh \
    && rm /install-composer.sh

RUN usermod -u 1000 www-data

VOLUME /root/.composer
WORKDIR /var/app

EXPOSE 9000
CMD ["php-fpm"]
