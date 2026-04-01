#!/bin/sh
set -e

php bin/console importmap:install --env=prod --no-interaction || true

php bin/console asset-map:compile --env=prod || true

echo "Assets compiled, starting PHP-FPM"

exec php-fpm
