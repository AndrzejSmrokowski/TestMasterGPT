FROM php:8.2-fpm

# Instalacja zależności i rozszerzeń PHP
RUN apt-get update && \
    apt-get install -y libpq-dev libzip-dev zip && \
    docker-php-ext-install zip pdo_mysql && \
    pecl install xdebug && \
    docker-php-ext-enable xdebug

# Konfiguracja Xdebug
COPY ./xdebug.ini "${PHP_INI_DIR}/conf.d"

# Instalacja Composera
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

CMD ["php-fpm"]
