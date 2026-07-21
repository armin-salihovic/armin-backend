#!/usr/bin/env sh
set -eu

cd /var/www/html

if [ -z "${APP_KEY:-}" ]; then
  echo "ERROR: APP_KEY is missing."
  echo "Generate one with: docker compose run --rm app php artisan key:generate --show"
  echo "Then set it in .env.docker before starting the stack."
  exit 1
fi

mkdir -p \
  database/sqlite \
  storage/app/public \
  storage/framework/cache \
  storage/framework/sessions \
  storage/framework/views \
  storage/logs \
  bootstrap/cache

if [ "${DB_CONNECTION:-}" = "sqlite" ]; then
  SQLITE_DATABASE="${DB_DATABASE:-/var/www/html/database/sqlite/database.sqlite}"
  mkdir -p "$(dirname "$SQLITE_DATABASE")"
  touch "$SQLITE_DATABASE"
fi

chown -R www-data:www-data storage bootstrap/cache database/sqlite 2>/dev/null || true

php artisan storage:link --force || true

if [ "${RUN_MIGRATIONS:-false}" = "true" ]; then
  php artisan migrate --force
fi

if [ "${RUN_SEEDERS:-false}" = "true" ]; then
  php artisan db:seed --force
fi

if [ "${APP_ENV:-production}" = "production" ]; then
  php artisan config:cache
  php artisan view:cache
fi

exec "$@"
