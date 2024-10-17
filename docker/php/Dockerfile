FROM php:8.2.5-fpm-alpine3.17

RUN apk update
RUN apk add libpq libpq-dev postgresql postgresql-dev postgresql-libs postgresql-contrib \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo pdo_pgsql pgsql

COPY --from=composer /usr/bin/composer /usr/bin/composer

WORKDIR /userland/symfonycli
RUN apk add --no-cache bash
RUN curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.alpine.sh' | bash -E
RUN apk add symfony-cli

WORKDIR /srv/www
