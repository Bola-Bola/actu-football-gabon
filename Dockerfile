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

# Copier les fichiers composer en premier (pour le cache Docker)
COPY composer.json composer.lock ./

# Installer les dépendances PHP
RUN composer install --no-dev --optimize-autoloader --no-scripts --no-autoloader

# Copier tous les fichiers de l'application
COPY . .

# Créer le fichier .env depuis les variables d'environnement (sera rempli au démarrage)
RUN touch .env

# Finaliser l'installation de Composer
RUN composer dump-autoload --optimize

# Créer les dossiers nécessaires avec les bonnes permissions
RUN mkdir -p storage/framework/{sessions,views,cache} \
    && mkdir -p storage/logs \
    && mkdir -p bootstrap/cache \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache

# Configuration Nginx - ABSOLUMENT FORCER sur /var/www/html/public
RUN echo 'server { \n\
    listen 10000 default_server; \n\
    listen [::]:10000 default_server; \n\
    \n\
    # ROOT ABSOLU VERS PUBLIC \n\
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
    # Log pour debug \n\
    access_log /var/log/nginx/access.log; \n\
    error_log /var/log/nginx/error.log; \n\
    \n\
    # Toute requête passe par index.php dans public \n\
    location / { \n\
        try_files $uri $uri/ /index.php?$query_string; \n\
    } \n\
    \n\
    location = /favicon.ico { access_log off; log_not_found off; } \n\
    location = /robots.txt  { access_log off; log_not_found off; } \n\
    \n\
    error_page 404 /index.php; \n\
    \n\
    # PHP-FPM pour tous les fichiers .php \n\
    location ~ \.php$ { \n\
        fastcgi_pass 127.0.0.1:9000; \n\
        fastcgi_index index.php; \n\
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name; \n\
        include fastcgi_params; \n\
        fastcgi_hide_header X-Powered-By; \n\
        fastcgi_param PATH_INFO $fastcgi_path_info; \n\
    } \n\
    \n\
    # Bloquer l accès aux fichiers cachés \n\
    location ~ /\.(?!well-known).* { \n\
        deny all; \n\
    } \n\
    \n\
    # Bloquer l accès aux fichiers sensibles \n\
    location ~ /\.(env|git|htaccess) { \n\
        deny all; \n\
    } \n\
}' > /etc/nginx/sites-available/default

# Créer aussi un fichier nginx.conf principal
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
    log_format main "$remote_addr - $remote_user [$time_local] \"$request\" " \n\
                    "$status $body_bytes_sent \"$http_referer\" " \n\
                    "\"$http_user_agent\" \"$http_x_forwarded_for\""; \n\
    \n\
    access_log /var/log/nginx/access.log main; \n\
    \n\
    sendfile on; \n\
    tcp_nopush on; \n\
    tcp_nodelay on; \n\
    keepalive_timeout 65; \n\
    types_hash_max_size 2048; \n\
    \n\
    gzip on; \n\
    gzip_vary on; \n\
    gzip_proxied any; \n\
    gzip_comp_level 6; \n\
    gzip_types text/plain text/css text/xml text/javascript application/json application/javascript application/xml+rss application/rss+xml font/truetype font/opentype application/vnd.ms-fontobject image/svg+xml; \n\
    \n\
    include /etc/nginx/conf.d/*.conf; \n\
    include /etc/nginx/sites-enabled/*; \n\
}' > /etc/nginx/nginx.conf

# Supprimer les configurations par défaut et recréer le lien
RUN rm -f /etc/nginx/sites-enabled/default \
    && rm -f /etc/nginx/conf.d/default.conf \
    && ln -s /etc/nginx/sites-available/default /etc/nginx/sites-enabled/default

# Script de démarrage amélioré
RUN echo '#!/bin/bash\n\
set -e\n\
\n\
echo "================================================="\n\
echo "  Démarrage de Football Actuel Gabon"\n\
echo "================================================="\n\
\n\
# Créer le fichier .env avec les variables d environnement de Render\n\
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
echo "✓ Fichier .env créé"\n\
\n\
# Générer la clé APP_KEY si elle n existe pas\n\
if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "base64:CHANGEME" ]; then\n\
    echo "⚠ Génération de APP_KEY..."\n\
    php artisan key:generate --force\n\
fi\n\
\n\
# Créer le lien symbolique storage\n\
php artisan storage:link --force || echo "⚠ Storage link déjà créé"\n\
\n\
# Vérifier que public/index.php existe\n\
if [ ! -f /var/www/html/public/index.php ]; then\n\
    echo "❌ ERREUR: public/index.php introuvable!"\n\
    ls -la /var/www/html/\n\
    exit 1\n\
fi\n\
\n\
echo "✓ Fichier public/index.php trouvé"\n\
\n\
# Démarrer PHP-FPM en arrière-plan\n\
echo "⚙ Démarrage de PHP-FPM..."\n\
php-fpm -D\n\
\n\
# Attendre que PHP-FPM soit prêt\n\
sleep 3\n\
\n\
# Tester la configuration Nginx\n\
echo "⚙ Test de la configuration Nginx..."\n\
nginx -t\n\
\n\
# Optimiser Laravel pour la production\n\
echo "⚙ Optimisation de Laravel..."\n\
php artisan config:clear\n\
php artisan config:cache\n\
php artisan route:cache || echo "⚠ Route cache échoué"\n\
php artisan view:cache\n\
\n\
# Exécuter les migrations\n\
echo "⚙ Exécution des migrations..."\n\
php artisan migrate --force || echo "⚠ Migrations échouées ou déjà exécutées"\n\
\n\
# Afficher les informations de configuration\n\
echo "================================================="\n\
echo "  Configuration Nginx"\n\
echo "================================================="\n\
echo "✓ Port: 10000"\n\
echo "✓ Root: /var/www/html/public"\n\
echo "✓ Index: index.php"\n\
echo ""\n\
echo "Contenu de /var/www/html/public:"\n\
ls -lah /var/www/html/public/\n\
echo "================================================="\n\
\n\
# Démarrer Nginx au premier plan\n\
echo "🚀 Démarrage de Nginx..."\n\
echo ""\n\
exec nginx -g "daemon off;"\n\
' > /start.sh && chmod +x /start.sh

# Exposer le port
EXPOSE 10000

# Démarrer l application
CMD ["/start.sh"]
