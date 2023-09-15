#!/bin/bash

if [ ! -f "vendor/autoload.php" ]; then
    composer install --no-progress --no-interaction
fi

if [ ! -f ".env" ]; then
    echo "Creating env file for env $APP_ENV"
    cp .env.example .env
else
    echo "env file exists."
fi

# Correct path to copy .env.example
cp .env.example .env

# Wait for MySQL to start (adjust the sleep duration as needed)
sleep 5

php artisan migrate
php artisan key:generate
php artisan optimize:clear

php artisan serve --port=$PORT --host=0.0.0.0 --env=.env
exec docker-php-entrypoint "$@"