FROM php:8.3-cli-trixie

RUN set -eux ; \
    apt-get update ; \
    apt-get install -y --no-install-recommends \
        git \
        unzip \
        zip \
        libzip-dev \
        pkg-config \
        ca-certificates ; \
    rm -rf /var/lib/apt/lists/* ;

RUN set -eux ; \
    docker-php-ext-configure zip ; \
    docker-php-ext-install -j"$(nproc)" zip ; \
    pecl install pcov ; \
    docker-php-ext-enable pcov ;

COPY --from=composer/composer:2.9 /usr/bin/composer /usr/local/bin/composer

WORKDIR /usr/local/packages/tmpfile

CMD ["php", "-v"]
