#!/bin/sh
set -e

echo "Starting PHP-FPM in background..."
php-fpm &

PHP_FPM_PID=$!

echo "Waiting for database..."
until php -r "try { new PDO('mysql:host=db;dbname=symfony', 'symfony', 'symfony'); } catch (Exception \$e) { exit(1); }"; do
  sleep 1
done

echo "Database is ready."

composer db || true

echo "Initialization complete. PHP-FPM is running."

wait $PHP_FPM_PID

