FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git \
    curl \
    libzip-dev \
    zip \
    unzip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libssl-dev \
    supervisor \
    && docker-php-ext-install pdo pdo_mysql zip mbstring exif pcntl bcmath gd xml \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install sockets

# Cài composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy config PHP
COPY ./local.ini /usr/local/etc/php/conf.d/local.ini

# Copy config supervisor
COPY ./supervisor/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

WORKDIR /var/www/html

CMD ["sh", "-c", "php-fpm & supervisord -n"]
