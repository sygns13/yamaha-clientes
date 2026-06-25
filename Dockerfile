# ============================================================
# Etapa 1: Dependencias PHP (Composer) -> genera vendor/
# Necesario ANTES de Vite porque Ziggy (tightenco/ziggy) vive en vendor/
# y resources/js/app.js lo importa desde ahi.
# ============================================================
FROM composer:2 AS vendor
WORKDIR /app
COPY composer.json composer.lock ./
# --no-scripts: artisan aun no existe en esta etapa (no copiamos el codigo completo)
# --ignore-platform-reqs: las extensiones reales (gd, zip, etc.) las tiene la imagen final
RUN composer install \
    --no-dev \
    --no-scripts \
    --optimize-autoloader \
    --no-interaction \
    --prefer-dist \
    --ignore-platform-reqs

# ============================================================
# Etapa 2: Build de assets (Vite / Inertia + Vue)
# ============================================================
FROM node:20-alpine AS assets
WORKDIR /app
COPY package*.json ./
RUN npm ci
COPY . .
# Traemos vendor desde la etapa "vendor" para que Vite resuelva Ziggy
COPY --from=vendor /app/vendor ./vendor
RUN npm run build

# ============================================================
# Etapa 3: Runtime PHP (serversideup: nginx + php-fpm, escucha en 8080)
# ============================================================
FROM serversideup/php:8.3-fpm-nginx
WORKDIR /var/www/html

# Codigo de la app (sin vendor/, sin .env, sin node_modules -> ver .dockerignore)
COPY --chown=www-data:www-data . .

# vendor optimizado desde la etapa Composer
COPY --from=vendor --chown=www-data:www-data /app/vendor ./vendor

# Assets compilados desde la etapa Node
COPY --from=assets --chown=www-data:www-data /app/public/build ./public/build

# Garantizar que existan los directorios de escritura de Laravel
# (por si el .dockerignore dejo alguno vacio). Se crean como www-data.
RUN mkdir -p \
    storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    storage/app/public \
    bootstrap/cache

# Symlink public/storage -> storage/app/public (para servir uploads por la web).
# Se usa ln -snf en vez de "artisan storage:link" para que sea deterministico
# y no dependa de que el framework arranque en build.
RUN ln -snf ../storage/app/public public/storage