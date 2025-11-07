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
    zip \
    unzip \
    && rm -rf /var/lib/apt/lists/*

# Installation des extensions PHP nécessaires pour Laravel
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Installation de Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Définir le répertoire de travail
WORKDIR /var/www/html

# Copier composer.json et composer.lock
COPY composer.json composer.lock ./

# Installer les dépendances PHP sans autoload
RUN composer install --no-dev --optimize-autoloader --no-scripts --no-autoloader

# Copier TOUS les fichiers de l'application (y compris public/)
COPY . .

# VERIFICATION CRITIQUE : Vérifier que public/index.php existe après la copie
RUN if [ ! -f public/index.php ]; then \
        echo "❌ ERREUR FATALE: public/index.php n'a pas été copié!"; \
        echo "Contenu de /var/www/html:"; \
        ls -la /var/www/html/; \
        echo "Contenu de /var/www/html/public:"; \
        ls -la /var/www/html/public/ || echo "Le dossier public n'existe pas!"; \
        exit 1; \
    fi

# Finaliser l'installation de Composer
RUN composer dump-autoload --optimize

# Créer le fichier .env
RUN touch .env

# Créer les dossiers nécessaires avec les bonnes permissions
RUN mkdir -p storage/framework/{sessions,views,cache} \
    && mkdir -p storage/logs \
    && mkdir -p bootstrap/cache

# Permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache

# Créer le fichier de configuration Nginx proprement
RUN cat > /etc/nginx/sites-available/default <<'NGINXCONF'
server {
    listen 10000 default_server;

    root /var/www/html/public;

    server_name _;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php index.html index.htm;

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

# Configuration Nginx principale
RUN cat > /etc/nginx/nginx.conf <<'NGINXMAIN'
user www-data;
worker_processes auto;
pid /run/nginx.pid;
error_log /var/log/nginx/error.log;

events {
    worker_connections 1024;
}

http {
    include /etc/nginx/mime.types;
    default_type application/octet-stream;

    access_log /var/log/nginx/access.log;

    sendfile on;
    tcp_nopush on;
    tcp_nodelay on;
    keepalive_timeout 65;
    types_hash_max_size 2048;

    gzip on;

    include /etc/nginx/conf.d/*.conf;
    include /etc/nginx/sites-enabled/*;
}
NGINXMAIN

# Recréer les liens Nginx
RUN rm -f /etc/nginx/sites-enabled/default \
    && rm -f /etc/nginx/conf.d/default.conf \
    && ln -s /etc/nginx/sites-available/default /etc/nginx/sites-enabled/default

# Script de démarrage
RUN cat > /start.sh <<'STARTSCRIPT'
#!/bin/bash
set -e

echo "================================================="
echo "  🚀 Football Actuel Gabon"
echo "================================================="

# VERIFICATION AVANT TOUT
echo "🔍 Vérification des fichiers..."
if [ ! -f /var/www/html/public/index.php ]; then
    echo "❌ ERREUR FATALE: public/index.php introuvable!"
    echo "Contenu de /var/www/html:"
    ls -lah /var/www/html/
    echo ""
    echo "Contenu de /var/www/html/public (si existe):"
    ls -lah /var/www/html/public/ 2>/dev/null || echo "Le dossier public n'existe pas!"
    exit 1
fi
echo "✅ public/index.php trouvé"

# Créer le fichier .env
cat > /var/www/html/.env <<EOF
APP_NAME=${APP_NAME:-Laravel}
APP_ENV=${APP_ENV:-production}
APP_KEY=${APP_KEY}
APP_DEBUG=${APP_DEBUG:-false}
APP_URL=${APP_URL}

LOG_CHANNEL=${LOG_CHANNEL:-stack}
LOG_LEVEL=${LOG_LEVEL:-error}

DB_CONNECTION=${DB_CONNECTION:-mysql}
DB_HOST=${DB_HOST}
DB_PORT=${DB_PORT:-3306}
DB_DATABASE=${DB_DATABASE}
DB_USERNAME=${DB_USERNAME}
DB_PASSWORD=${DB_PASSWORD}

BROADCAST_DRIVER=log
CACHE_DRIVER=${CACHE_DRIVER:-file}
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=${SESSION_DRIVER:-cookie}
SESSION_LIFETIME=120
EOF

echo "✅ Fichier .env créé"

# Générer APP_KEY si nécessaire
if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "base64:CHANGEME" ]; then
    php artisan key:generate --force
fi

# Storage link
php artisan storage:link --force 2>/dev/null || true

# Démarrer PHP-FPM
echo "⚙️  Démarrage de PHP-FPM..."
php-fpm -D
sleep 3

# Test Nginx
echo "⚙️  Test de la configuration Nginx..."
nginx -t

# Optimisation Laravel
echo "⚙️  Optimisation de Laravel..."
php artisan config:cache
php artisan route:cache 2>/dev/null || true
php artisan view:cache

# Migrations
echo "⚙️  Migrations de la base de données..."
php artisan migrate --force 2>/dev/null || echo "⚠️  Migrations ignorées"

echo ""
echo "================================================="
echo "✅ Application démarrée avec succès!"
echo "📁 Root: /var/www/html/public"
echo "🌐 Port: 10000"
echo "================================================="
echo ""

# Démarrer Nginx
exec nginx -g "daemon off;"
STARTSCRIPT

RUN chmod +x /start.sh

EXPOSE 10000

CMD ["/start.sh"]
