FROM php:8.1-cli

RUN apt-get update && apt-get install -y \
    libzip-dev zip unzip git curl libonig-dev libxml2-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring zip xml

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/app

COPY app/ .

RUN composer install

EXPOSE 8080

CMD ["php", "./yii", "serve", "--port=8080", "--docroot=web", "0.0.0.0"]


