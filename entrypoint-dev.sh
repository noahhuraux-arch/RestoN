#!/bin/sh
set -e

echo "Waiting for database..."
until php -r "try { new PDO('mysql:host=db;dbname=symfony', 'symfony', 'symfony'); } catch (Exception \$e) { exit(1); }"; do
  sleep 1
done

echo "Database is ready."

composer db || true

php-fpm
