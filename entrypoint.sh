#!/bin/sh
set -e

php bin/console importmap:install --env=prod
php bin/console importmap:compile --env=prod

php bin/console asset-map:compile --env=prod

echo "Assets compiled"

exec php-fpm
