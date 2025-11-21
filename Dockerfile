    # ==================================================
# DEV: Use webdevops/php-nginx (with xdebug, etc.)
# ==================================================
FROM webdevops/php-nginx-dev:8.4 AS dev

COPY docker/nginx/vhost.conf /opt/docker/etc/nginx/vhost.conf
ENV APP_ENV=local
ENV APP_DEBUG=true

# Switch to root to install system packages
USER root

# Install Node.js + npm
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - && \
    apt-get install -y nodejs && \
    npm install -g npm@latest && \
    apt-get clean && rm -rf /var/lib/apt/lists/*

USER application
COPY docker/dev/app/.bash_aliases /home/application/.bash_aliases
RUN cat /home/application/.bash_aliases >> /home/application/.bashrc
EXPOSE 80

# ==================================================
# BASE PHP-FPM image (for prod)
# ==================================================
FROM php:8.4-fpm AS base
WORKDIR /app

# Install PHP extensions
RUN apt-get update && apt-get install -y \
    git unzip libpq-dev libzip-dev libonig-dev libicu-dev libpng-dev libsodium-dev libfreetype6-dev libjpeg62-turbo-dev libwebp-dev libavif-dev libxpm-dev curl \
     && docker-php-ext-configure gd \
            --with-freetype \
            --with-jpeg \
            --with-webp \
            --with-avif \
            --with-xpm \
    && docker-php-ext-install pdo pdo_mysql zip intl gd bcmath pcntl exif opcache sodium \
    && rm -rf /var/lib/apt/lists/*

RUN pecl install --onlyreqdeps --force redis \
&& rm -rf /tmp/pear \
&& docker-php-ext-enable redis

COPY docker/production/php/php.ini /usr/local/etc/php/conf.d/99-customphp.ini
COPY docker/production/php/php-fpm.conf /usr/local/etc/php-fpm.d/zz-custom.conf
COPY docker/production/php/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Create user
RUN groupadd -g 1000 laravel && useradd -u 1000 -g laravel -s /bin/bash -m laravel
RUN chown -R laravel:laravel /app
USER laravel

# ==================================================
# BUILD dependencies & frontend
# ==================================================
FROM base AS build

# Install PHP dependencies
COPY --chown=laravel:laravel composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-progress --no-interaction

# Install Node.js (LTS) and build frontend
USER root
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && rm -rf /var/lib/apt/lists/*

USER laravel
COPY --chown=laravel:laravel package.json package-lock.json ./
RUN npm install
COPY --chown=laravel:laravel . .
RUN npm run build

# ==================================================
# PRODUCTION PHP-FPM
# ==================================================
FROM base AS php
COPY --chown=laravel:laravel . .
COPY --from=build --chown=laravel:laravel /app/vendor /app/vendor
COPY --from=build --chown=laravel:laravel /app/public/build /app/public/build
RUN composer dump-autoload --optimize

COPY --chown=laravel:laravel docker/production/entrypoint.sh /entrypoint.sh
COPY --chown=laravel:laravel docker/production/entrypoint-queue.sh /entrypoint-queue.sh
RUN chmod +x /entrypoint.sh /entrypoint-queue.sh

ENTRYPOINT ["/entrypoint.sh"]
CMD ["php-fpm"]

# ==================================================
# NGINX for production
# ==================================================
FROM nginx:stable-alpine AS nginx
COPY docker/production/nginx/vhost.conf /etc/nginx/conf.d/default.conf
COPY --from=php /app/public /app/public
EXPOSE 80
