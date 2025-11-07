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

# Configuration Nginx
RUN echo 'server { \n\
    listen 10000 default_server; \n\
    listen [::]:10000 default_server; \n\
    \n\
    # ROOT VERS PUBLIC \n\
    root /var/www/html/public; \n\
    \n\
    server_name _; \n\
    \n\
    add_header X-Frame-Options "SAMEORIGIN"; \n\
    add_header X-Content-Type-Options "nosniff"; \n\
    \n\
    index index.php index.html index.htm; \n\
    \n\
    charset utf-8; \n\
    \n\
    access_log /var/log/nginx/access.log; \n\
    error_log /var/log/nginx/error.log; \n\
    \n\
    location / { \n\
        try_files $uri $uri/ /index.php?$query_string; \n\
    } \n\
    \n\
    location = /favicon.ico { access_log off; log_not_found off; } \n\
    location = /robots.txt  { access_log off; log_not_found off; } \n\
    \n\
    error_page 404 /index.php; \n\
    \n\
    location ~ \.php$ { \n\
        fastcgi_pass 127.0.0.1:9000; \n\
        fastcgi_index index.php; \n\
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name; \n\
        include fastcgi_params; \n\
        fastcgi_hide_header X-Powered-By; \n\
    } \n\
    \n\
    location ~ /\.(?!well-known).* { \n\
        deny all; \n\
    } \n\
}' > /etc/nginx/sites-available/default

# Configuration Nginx principale
RUN echo 'user www-data; \n\
worker_processes auto; \n\
pid /run/nginx.pid; \n\
error_log /var/log/nginx/error.log; \n\
\n\
events { \n\
    worker_connections 1024; \n\
} \n\
\n\
http { \n\
    include /etc/nginx/mime.types; \n\
    default_type application/octet-stream; \n\
    \n\
    access_log /var/log/nginx/access.log; \n\
    \n\
    sendfile on; \n\
    tcp_nopush on; \n\
    tcp_nodelay on; \n\
    keepalive_timeout 65; \n\
    types_hash_max_size 2048; \n\
    \n\
    gzip on; \n\
    \n\
    include /etc/nginx/conf.d/*.conf; \n\
    include /etc/nginx/sites-enabled/*; \n\
}' > /etc/nginx/nginx.conf

# Recréer les liens Nginx
RUN rm -f /etc/nginx/sites-enabled/default \
    && rm -f /etc/nginx/conf.d/default.conf \
    && ln -s /etc/nginx/sites-available/default /etc/nginx/sites-enabled/default

# Script de démarrage
RUN echo '#!/bin/bash\n\
set -e\n\
\n\
echo "================================================="\n\
echo "  🚀 Football Actuel Gabon"\n\
echo "================================================="\n\
\n\
# VERIFICATION AVANT TOUT\n\
echo "🔍 Vérification des fichiers..."\n\
if [ ! -f /var/www/html/public/index.php ]; then\n\
    echo "❌ ERREUR FATALE: public/index.php introuvable!"\n\
    echo "Contenu de /var/www/html:"\n\
    ls -lah /var/www/html/\n\
    echo ""\n\
    echo "Contenu de /var/www/html/public (si existe):"\n\
    ls -lah /var/www/html/public/ 2>/dev/null || echo "Le dossier public n existe pas!"\n\
    exit 1\n\
fi\n\
echo "✅ public/index.php trouvé"\n\
\n\
# Créer le fichier .env\n\
cat > /var/www/html/.env << EOF\n\
APP_NAME="${APP_NAME:-Laravel}"\n\
APP_ENV="${APP_ENV:-production}"\n\
APP_KEY="${APP_KEY}"\n\
APP_DEBUG="${APP_DEBUG:-false}"\n\
APP_URL="${APP_URL}"\n\
\n\
LOG_CHANNEL="${LOG_CHANNEL:-stack}"\n\
LOG_LEVEL="${LOG_LEVEL:-error}"\n\
\n\
DB_CONNECTION="${DB_CONNECTION:-mysql}"\n\
DB_HOST="${DB_HOST}"\n\
DB_PORT="${DB_PORT:-3306}"\n\
DB_DATABASE="${DB_DATABASE}"\n\
DB_USERNAME="${DB_USERNAME}"\n\
DB_PASSWORD="${DB_PASSWORD}"\n\
\n\
BROADCAST_DRIVER=log\n\
CACHE_DRIVER="${CACHE_DRIVER:-file}"\n\
FILESYSTEM_DISK=local\n\
QUEUE_CONNECTION=sync\n\
SESSION_DRIVER="${SESSION_DRIVER:-cookie}"\n\
SESSION_LIFETIME=120\n\
EOF\n\
\n\
echo "✅ Fichier .env créé"\n\
\n\
# Générer APP_KEY si nécessaire\n\
if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "base64:CHANGEME" ]; then\n\
    php artisan key:generate --force\n\
fi\n\
\n\
# Storage link\n\
php artisan storage:link --force 2>/dev/null || true\n\
\n\
# Démarrer PHP-FPM\n\
echo "⚙️  Démarrage de PHP-FPM..."\n\
php-fpm -D\n\
sleep 3\n\
\n\
# Test Nginx\n\
echo "⚙️  Test de la configuration Nginx..."\n\
nginx -t\n\
\n\
# Optimisation Laravel\n\
echo "⚙️  Optimisation de Laravel..."\n\
php artisan config:cache\n\
php artisan route:cache 2>/dev/null || true\n\
php artisan view:cache\n\
\n\
# Migrations\n\
echo "⚙️  Migrations de la base de données..."\n\
php artisan migrate --force 2>/dev/null || echo "⚠️  Migrations ignorées"\n\
\n\
echo ""\n\
echo "================================================="\n\
echo "✅ Application démarrée avec succès!"\n\
echo "📁 Root: /var/www/html/public"\n\
echo "🌐 Port: 10000"\n\
echo "================================================="\n\
echo ""\n\
\n\
# Démarrer Nginx\n\
exec nginx -g "daemon off;"\n\
' > /start.sh && chmod +x /start.sh

EXPOSE 10000

CMD ["/start.sh"]
