#!/usr/bin/env bash

echo "=== Démarrage de l'application ==="
echo "Répertoire actuel: $(pwd)"
echo "Contenu du répertoire:"
ls -la

echo "=== Contenu du dossier public ==="
ls -la public/

echo "=== Démarrage du serveur Laravel ==="
php artisan serve --host=0.0.0.0 --port=10000
