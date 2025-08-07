FROM php:8.4-fpm

ENV PHP_OPCACHE_ENABLE=1
ENV PHP_OPCACHE_ENABLE_CLI=1
ENV PHP_OPCACHE_VALIDATE_TIMESTAMPS=1
ENV PHP_OPCACHE_REVALIDATE_FREQ=2

RUN usermod --uid 1000 www-data

RUN apt-get update && apt-get install -y \
    zip unzip git dos2unix curl netcat-openbsd \
    libzip-dev libcurl4-gnutls-dev libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql bcmath curl opcache \
    && curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs net-tools \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/app

COPY --chown=www-data . .

COPY php/php.ini /usr/local/etc/php/php.ini
COPY php/php-fpm.conf /usr/local/etc/php-fpm.d/www.conf
COPY nginx/nginx.conf /etc/nginx/nginx.conf
COPY nginx/conf.d/default.conf /etc/nginx/conf.d/default.conf

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN if [ -f composer.json ]; then composer install --no-progress --no-interaction; fi

RUN npm install -g vite
# RUN npm install && npm run dev

COPY scripts/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN dos2unix /usr/local/bin/entrypoint.sh && chmod +x /usr/local/bin/entrypoint.sh

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
