# ---- 1. Build the React/Vite frontend ----
FROM node:22-alpine AS assets
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci
COPY . .
RUN npm run build

# ---- 2. PHP-FPM + Nginx runtime (listens on :8080) ----
FROM serversideup/php:8.4-fpm-nginx-alpine

ENV PHP_OPCACHE_ENABLE=1 \
    AUTORUN_ENABLED=true

WORKDIR /var/www/html

COPY --chown=www-data:www-data . .
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader
COPY --chown=www-data:www-data --from=assets /app/public/build ./public/build
COPY --chmod=755 docker/entrypoint.d/ /etc/entrypoint.d/
