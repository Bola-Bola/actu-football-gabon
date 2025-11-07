FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    nginx git curl \
    libpng-dev libonig-dev libxml2-dev libzip-dev libpq-dev \
    postgresql-client zip unzip \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo_pgsql pgsql mbstring exif pcntl bcmath gd zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN echo "memory_limit=512M" > /usr/local/etc/php/conf.d/memory-limit.ini

WORKDIR /var/www/html

# Étape 1: Installer les dépendances sans le code de l'app
COPY composer.json composer.lock ./
RUN composer validate --no-check-publish || true
RUN composer install --no-scripts --no-autoloader --no-interaction --prefer-dist

# Étape 2: Copier tout le code
COPY . .
RUN test -f public/index.php || exit 1

# Étape 3: Générer l'autoloader (on essaie, si ça échoue on continue)
RUN composer dump-autoload --optimize 2>/dev/null || \
    echo "⚠️ Autoload generation failed, will be done at runtime"

# Permissions
RUN mkdir -p storage/framework/{sessions,views,cache} storage/logs bootstrap/cache \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache

# Nginx config
RUN cat > /etc/nginx/sites-available/default <<'EOF'
server {
    listen 10000;
    root /var/www/html/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass 127.0.0.1:9000;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\. {
        deny all;
    }
}
EOF

RUN cat > /etc/nginx/nginx.conf <<'EOF'
user www-data;
worker_processes auto;
events { worker_connections 1024; }
http {
    include /etc/nginx/mime.types;
    sendfile on;
    keepalive_timeout 65;
    gzip on;
    include /etc/nginx/sites-enabled/*;
}
EOF

RUN ln -sf /etc/nginx/sites-available/default /etc/nginx/sites-enabled/

# Start script
RUN cat > /start.sh <<'EOF'
#!/bin/bash
set -e
cat > .env <<ENVEOF
APP_NAME=${APP_NAME:-Laravel}
APP_ENV=${APP_ENV:-production}
APP_KEY=${APP_KEY}
APP_DEBUG=${APP_DEBUG:-false}
APP_URL=${APP_URL}
LOG_CHANNEL=stack
LOG_LEVEL=debug
DB_CONNECTION=pgsql
DB_HOST=${DB_HOST}
DB_PORT=5432
DB_DATABASE=${DB_DATABASE}
DB_USERNAME=${DB_USERNAME}
DB_PASSWORD=${DB_PASSWORD}
CACHE_DRIVER=database
QUEUE_CONNECTION=database
SESSION_DRIVER=database
ENVEOF

[ -z "$APP_KEY" ] && php artisan key:generate --force
php artisan storage:link --force 2>/dev/null || true

# Régénérer l'autoload au démarrage
echo "🔄 Génération de l'autoload..."
composer dump-autoload --optimize 2>/dev/null || echo "⚠️ Autoload warning"

php-fpm -D
sleep 2

echo "⚙️ Configuration Laravel..."
php artisan config:cache
php artisan route:cache || true
php artisan view:cache

for i in {1..10}; do
    php artisan migrate --force && break || sleep 3
done

exec nginx -g "daemon off;"
EOF

RUN chmod +x /start.sh

EXPOSE 10000
CMD ["/start.sh"]
