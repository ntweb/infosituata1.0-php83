FROM php:8.3-fpm-alpine3.18
RUN apk update; \
    apk upgrade;

RUN apk add libzip-dev
RUN apk add --no-cache mariadb-client
RUN docker-php-ext-install zip

RUN docker-php-ext-install mysqli pdo pdo_mysql

RUN apk add jpeg-dev libpng-dev \
    && docker-php-ext-configure gd --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd

RUN NUMPROC=$(grep -c ^processor /proc/cpuinfo 2>/dev/null || 1) \
    && docker-php-ext-install -j${NUMPROC} gd

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY php.ini /usr/local/etc/php/conf.d/
