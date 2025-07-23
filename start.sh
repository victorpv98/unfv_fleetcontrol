#!/bin/bash

PORT=${PORT:-8080}
echo "Configurando Apache para puerto $PORT"

echo "Listen $PORT" > /etc/apache2/ports.conf

cat > /etc/apache2/sites-available/000-default.conf << EOF
<VirtualHost *:$PORT>
    ServerName unfv-fleetcontrol.local
    ServerAlias localhost
    DocumentRoot /var/www/html/public
    
    <Directory /var/www/html/public>
        DirectoryIndex index.php index.html
        AllowOverride All
        Require all granted
        Options Indexes FollowSymLinks
    </Directory>
    
    <FilesMatch \.php$>
        SetHandler application/x-httpd-php
    </FilesMatch>
    
    ErrorLog \${APACHE_LOG_DIR}/error.log
    CustomLog \${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
EOF

echo "ServerName unfv-fleetcontrol.local" >> /etc/apache2/apache2.conf

# Limpieza y cache
php artisan config:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Migraciones y seeders
php artisan migrate --force
php artisan db:seed --force || echo "Seeders no ejecutados"

# Crear tablas adicionales
php artisan session:table 2>/dev/null || true
php artisan queue:table 2>/dev/null || true
php artisan cache:table 2>/dev/null || true
php artisan migrate --force

php artisan storage:link

chmod -R 775 storage/ bootstrap/cache
chown -R www-data:www-data storage/ bootstrap/cache

echo "Iniciando Apache en puerto $PORT..."
apache2-foreground