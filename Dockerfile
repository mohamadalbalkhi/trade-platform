FROM php:8.2-cli

WORKDIR /app

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libzip-dev \
    zip \
    && docker-php-ext-install zip pdo pdo_mysql

COPY . .

RUN cp .env.example .env || true

RUN curl -sS https://getcomposer.org/installer | php \
    && php composer.phar install

CMD php artisan key:generate && php artisan config:cache && php artisan serve --host=0.0.0.0 --port=10000