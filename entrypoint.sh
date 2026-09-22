#!/bin/bash


# Mettre en cache la configuration pour de meilleurs performances
php artisan config:cache

# Lancer les migrations automatiquement au démarrage
php artisan migrate --force
php artisan db:seed --force

# Démarrer Apache au premier plan (obligatoire pour que le conteneur reste actif)
apache2-foreground