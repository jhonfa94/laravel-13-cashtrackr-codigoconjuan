# ==================================================
# Proyecto Laravel – Dokploy
# ==================================================

FROM elrincondeisma/laravel-docker-images:latest

WORKDIR /var/www

# ==================================================
# Copiar proyecto con permisos correctos
# ==================================================
COPY --chown=laravel:laravel . .

# ==================================================
# Dependencias PHP
# ==================================================
RUN composer install \
    --no-dev \
    --optimize-autoloader \
    --no-interaction

# ==================================================
# Frontend (Vite)
# ==================================================
RUN npm install \
    && npm run build

# ==================================================
# Instalar Octane con FrankenPHP
# ==================================================
RUN php artisan octane:install --server=frankenphp --no-interaction

# ==================================================
# Optimización Laravel
# ==================================================
RUN php artisan key:generate --force \
    && php artisan storage:link \
    && php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache \
    && php artisan event:cache

# ==================================================
# Exponer puerto Octane
# ==================================================
EXPOSE 8000

# ==================================================
# Arranque Octane
# ==================================================
CMD ["php", "artisan", "octane:start", "--server=frankenphp", "--host=0.0.0.0", "--port=8000"]