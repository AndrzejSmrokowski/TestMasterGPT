# Base image
FROM php:8.2-fpm

# Install dependencies and PHP extensions
RUN apt-get update && \
    apt-get install -y libpq-dev libzip-dev zip && \
    docker-php-ext-install zip pdo_mysql && \
    pecl install xdebug && \
    docker-php-ext-enable xdebug && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

# Xdebug configuration
COPY xdebug.ini "${PHP_INI_DIR}/conf.d"

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Change user
# RUN usermod -u 1000 www-data

# Command to run
CMD ["php-fpm"]
