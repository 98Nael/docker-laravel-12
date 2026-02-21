#!/usr/bin/env sh
set -e

cd /var/www/html

if [ ! -f .env ] && [ -f .env.example ]; then
    cp .env.example .env
fi

if [ ! -f vendor/autoload.php ]; then
    composer install --no-interaction --prefer-dist
fi

if [ -f artisan ] && [ -f .env ] && ! grep -q '^APP_KEY=base64:' .env; then
    php artisan key:generate --force
fi

if [ "${1:-}" = "prepare-only" ]; then
    exit 0
fi

exec php artisan serve --host=0.0.0.0 --port=80
