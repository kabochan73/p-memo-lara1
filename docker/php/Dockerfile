FROM php:8.5-fpm

# Laravelが必要とするシステムパッケージとPHP拡張をインストール
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    && docker-php-ext-install pdo_mysql zip bcmath

# Composer(PHPのパッケージ管理ツール)を、公式イメージからコピーして使えるようにする
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
