FROM php:8.3-apache

# Instalar dependencias del sistema y Node.js para Vite
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get update && apt-get install -y \
        git curl libpng-dev libonig-dev libxml2-dev zip unzip libpq-dev \
        libzip-dev postgresql-client nodejs \
    && docker-php-ext-install pdo pdo_pgsql pgsql mbstring exif pcntl bcmath gd zip

# Instalar Composer
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

# Habilitar mod_rewrite para Laravel
RUN a2enmod rewrite

WORKDIR /var/www/html

# Copiar archivos del proyecto
COPY . .

# Instalar dependencias de PHP
RUN composer install --optimize-autoloader --no-interaction

# Instalar dependencias de Node y compilar
RUN npm install && npm run build

# Ajustar permisos
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache

COPY start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

EXPOSE 8080

CMD ["/usr/local/bin/start.sh"]