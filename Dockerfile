FROM php:8.2-fpm

# Installation des dépendances système
RUN apt-get update && apt-get install -y \
    nginx \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libpq-dev \
    postgresql-client \
    zip \
    unzip \
    && rm -rf /var/lib/apt/lists/*

# Installation des extensions PHP
RUN docker-php-ext-install pdo_pgsql pgsql mbstring exif pcntl bcmath gd zip

# Installation de Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Augmenter les limites de mémoire PHP pour Composer
RUN echo "memory_limit=512M" > /usr/local/etc/php/conf.d/memory-limit.ini

WORKDIR /var/www/html

# Copier uniquement composer.json et composer.lock d'abord
COPY composer.json composer.lock ./

# Vérifier que composer.json est valide
RUN composer validate --no-check-publish || echo "Warning: composer.json validation failed"

# Installer les dépendances sans scripts ni autoload d'abord
RUN composer install \
    --no-scripts \
    --no-autoloader \
    --no-interaction \
    --prefer-dist \
    || (echo "❌ Composer install échoué" && exit 1)

# Maintenant copier le reste du code
COPY . .

# Vérifier les fichiers critiques
RUN test -f public/index.php || (echo "❌ public/index.php manquant!" && exit 1)

# Générer l'autoloader maintenant que tout le code est là
RUN composer dump-autoload --optimize --no-dev

# Créer les dossiers nécessaires
RUN mkdir -p storage/framework/{sessions,views,cache} \
    storage/logs \
    bootstrap/cache

# Permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache

# Configuration Nginx
COPY <<'NGINXCONF' /etc/nginx/sites-available/default
server {
    listen 10000 default_server;
    root /var/www/html/public;
    server_name _;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php index.html;
    charset utf-8;

    access_log /var/log/nginx/access.log;
    error_log /var/log/nginx/error.log;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass 127.0.0.1:9000;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
NGINXCONF

COPY <<'NGINXMAIN' /etc/nginx/nginx.conf
user www-data;
worker_processes auto;
pid /run/nginx.pid;

events {
    worker_connections 1024;
}

http {
    include /etc/nginx/mime.types;
    default_type application/octet-stream;

    access_log /var/log/nginx/access.log;
    error_log /var/log/nginx/error.log;

    sendfile on;
    keepalive_timeout 65;
    gzip on;

    include /etc/nginx/sites-enabled/*;
}
NGINXMAIN

RUN rm -f /etc/nginx/sites-enabled/default \
    && ln -s /etc/nginx/sites-available/default /etc/nginx/sites-enabled/

# Script de démarrage
COPY <<'STARTSCRIPT' /start.sh
#!/bin/bash
set -e

echo "================================================="
echo "  🚀 Football Actuel Gabon"
echo "================================================="

# Créer .env
cat > /var/www/html/.env <<EOF
APP_NAME=${APP_NAME:-Laravel}
APP_ENV=${APP_ENV:-production}
APP_KEY=${APP_KEY}
APP_DEBUG=${APP_DEBUG:-false}
APP_URL=${APP_URL}

LOG_CHANNEL=${LOG_CHANNEL:-stack}
LOG_LEVEL=${LOG_LEVEL:-debug}

DB_CONNECTION=pgsql
DB_HOST=${DB_HOST}
DB_PORT=${DB_PORT:-5432}
DB_DATABASE=${DB_DATABASE}
DB_USERNAME=${DB_USERNAME}
DB_PASSWORD=${DB_PASSWORD}

BROADCAST_DRIVER=log
CACHE_DRIVER=${CACHE_DRIVER:-database}
FILESYSTEM_DISK=local
QUEUE_CONNECTION=${QUEUE_CONNECTION:-database}
SESSION_DRIVER=${SESSION_DRIVER:-database}
SESSION_LIFETIME=120
EOF

echo "✅ .env créé"

# Générer APP_KEY si nécessaire
if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "base64:CHANGEME" ]; then
    php artisan key:generate --force
fi

# Storage link
php artisan storage:link --force 2>/dev/null || true

# Démarrer PHP-FPM
echo "⚙️  PHP-FPM..."
php-fpm -D
sleep 2

# Test Nginx
nginx -t

# Optimisation Laravel
echo "⚙️  Optimisation..."
php artisan config:clear
php artisan config:cache
php artisan route:cache 2>/dev/null || true
php artisan view:cache

# Migrations avec retry
echo "⚙️  Migrations..."
for i in {1..10}; do
    if php artisan migrate --force 2>&1; then
        echo "✅ Migrations OK!"
        break
    else
        echo "⏳ Tentative $i/10..."
        sleep 3
    fi
done

echo ""
echo "================================================="
echo "✅ Démarré sur le port 10000"
echo "================================================="

exec nginx -g "daemon off;"
STARTSCRIPT

RUN chmod +x /start.sh

EXPOSE 10000
CMD ["/start.sh"]
