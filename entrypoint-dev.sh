#!/bin/sh
set -e

# Attendre que la DB soit prête
echo "Waiting for database..."
until php -r "try { new PDO('mysql:host=db;dbname=symfony', 'symfony', 'symfony'); } catch (Exception \$e) { exit(1); }"; do
  sleep 1
done

echo "Database is ready."

# Exécuter ton script composer
composer db || true

# Lancer PHP-FPM
php-fpm
