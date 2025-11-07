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

# Finaliser l'installation de Composer
RUN composer dump-autoload --optimize

# Créer les dossiers nécessaires avec les bonnes permissions
RUN mkdir -p storage/framework/{sessions,views,cache} \
    && mkdir -p storage/logs \
    && mkdir -p bootstrap/cache \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# Créer le lien symbolique storage
RUN php artisan storage:link || true

# Configuration Nginx - FORCER le chemin vers public
RUN echo 'server { \n\
    listen 10000; \n\
    server_name _; \n\
    root /var/www/html/public; \n\
    \n\
    add_header X-Frame-Options "SAMEORIGIN"; \n\
    add_header X-Content-Type-Options "nosniff"; \n\
    \n\
    index index.php index.html; \n\
    \n\
    charset utf-8; \n\
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
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name; \n\
        include fastcgi_params; \n\
        fastcgi_hide_header X-Powered-By; \n\
    } \n\
    \n\
    location ~ /\.(?!well-known).* { \n\
        deny all; \n\
    } \n\
}' > /etc/nginx/sites-available/default

# Supprimer la configuration Nginx par défaut
RUN rm -f /etc/nginx/sites-enabled/default \
    && ln -s /etc/nginx/sites-available/default /etc/nginx/sites-enabled/

# Script de démarrage
RUN echo '#!/bin/bash\n\
set -e\n\
\n\
echo "=== Démarrage de l application Laravel ==="\n\
\n\
# Générer la clé APP_KEY si elle n existe pas\n\
if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "base64:CHANGEME" ]; then\n\
    echo "Génération de APP_KEY..."\n\
    php artisan key:generate --force\n\
fi\n\
\n\
# Démarrer PHP-FPM en arrière-plan\n\
echo "Démarrage de PHP-FPM..."\n\
php-fpm -D\n\
\n\
# Attendre que PHP-FPM soit prêt\n\
sleep 2\n\
\n\
# Optimiser Laravel pour la production\n\
echo "Optimisation de Laravel..."\n\
php artisan config:cache\n\
php artisan route:cache\n\
php artisan view:cache\n\
\n\
# Exécuter les migrations\n\
echo "Exécution des migrations..."\n\
php artisan migrate --force || echo "Migrations échouées ou déjà exécutées"\n\
\n\
# Vérifier le chemin racine de Nginx\n\
echo "=== Configuration Nginx ==="\n\
echo "Root directory: /var/www/html/public"\n\
ls -la /var/www/html/public/\n\
\n\
# Démarrer Nginx au premier plan\n\
echo "Démarrage de Nginx sur le port 10000..."\n\
nginx -g "daemon off;"\n\
' > /start.sh && chmod +x /start.sh

# Exposer le port
EXPOSE 10000

# Définir l utilisateur
USER www-data

# Revenir à root pour le démarrage
USER root

# Démarrer l application
CMD ["/start.sh"]
