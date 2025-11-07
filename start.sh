#!/bin/bash

echo "Démarrage de l'application Laravel..."

# Démarrer PHP-FPM en arrière-plan
php-fpm -D

# Attendre que PHP-FPM soit prêt
sleep 2

# Optimiser Laravel pour la production
echo "Optimisation de Laravel..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Exécuter les migrations (décommentez si nécessaire)
# php artisan migrate --force

# Démarrer Nginx au premier plan
echo "Démarrage de Nginx..."
nginx -g "daemon off;"
