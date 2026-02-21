FROM php:8.3-cli-bookworm

ARG WWWUSER=1000
ARG WWWGROUP=1000
ARG NODE_VERSION=20

ENV DEBIAN_FRONTEND=noninteractive
ENV COMPOSER_ALLOW_SUPERUSER=1

WORKDIR /var/www/html

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        ca-certificates \
        curl \
        git \
        unzip \
        zip \
        autoconf \
        g++ \
        make \
        libicu-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        libfreetype6-dev \
        libonig-dev \
        libxml2-dev \
        libzip-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" \
        bcmath \
        exif \
        gd \
        intl \
        mbstring \
        pcntl \
        pdo_mysql \
        zip \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && curl -fsSL "https://deb.nodesource.com/setup_${NODE_VERSION}.x" | bash - \
    && apt-get install -y --no-install-recommends nodejs \
    && npm install -g npm@latest \
    && groupadd --force -g "${WWWGROUP}" sail \
    && useradd -ms /bin/bash --no-user-group -g "${WWWGROUP}" -u "${WWWUSER}" sail \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer
COPY docker/start-container.sh /usr/local/bin/start-container

RUN chmod +x /usr/local/bin/start-container

EXPOSE 80

CMD ["sh", "-c", "start-container prepare-only && php artisan config:cache && php artisan migrate --force && php artisan migrate --seed --force && exec php artisan serve --host=0.0.0.0 --port=80"]
