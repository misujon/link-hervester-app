FROM php:8.2 as php

RUN apt-get update -y
RUN apt-get install -y unzip libpq-dev libcurl4-gnutls-dev
RUN docker-php-ext-install pdo pdo_mysql bcmath

WORKDIR /var/www/link-harvester-app

# Set permissions recursively for all files and folders within /var/www
RUN chmod -R 755 /var/www/link-harvester-app

COPY . .
COPY --from=composer:2.3.5 /usr/bin/composer /usr/bin/composer
COPY Docker/entrypoint.sh /usr/local/bin/entrypoint.sh
COPY .env.example /var/www/link-harvester-app/.env.example

ENV PORT=8000
RUN chmod +x /usr/local/bin/entrypoint.sh

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
