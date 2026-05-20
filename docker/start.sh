#!/usr/bin/env bash
set -euo pipefail

export PORT="${PORT:-8080}"

sed -ri "s/^Listen .*/Listen ${PORT}/" /etc/apache2/ports.conf
sed -ri "s/<VirtualHost \*:[0-9]+>/<VirtualHost *:${PORT}>/" /etc/apache2/sites-available/000-default.conf

mkdir -p \
    storage/app/public \
    storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/testing \
    storage/framework/views \
    storage/logs \
    bootstrap/cache

if [[ "${DB_CONNECTION:-sqlite}" == "sqlite" ]]; then
    SQLITE_DATABASE="${DB_DATABASE:-/var/www/html/database/database.sqlite}"

    if [[ "${SQLITE_DATABASE}" != ":memory:" ]]; then
        mkdir -p "$(dirname "${SQLITE_DATABASE}")"
        touch "${SQLITE_DATABASE}"
        chown www-data:www-data "${SQLITE_DATABASE}" "$(dirname "${SQLITE_DATABASE}")"
    fi
fi

chown -R www-data:www-data storage bootstrap/cache database

if [[ -n "${APP_KEY:-}" && "${APP_KEY}" != base64:* ]]; then
    if php -r 'exit(strlen(base64_decode(getenv("APP_KEY"), true) ?: "") === 32 ? 0 : 1);'; then
        export APP_KEY="base64:${APP_KEY}"
    fi
fi

if [[ -z "${APP_KEY:-}" ]]; then
    echo "WARNING: APP_KEY is not set. Set it on Render with: php artisan key:generate --show"
fi

php artisan storage:link >/dev/null 2>&1 || true
php artisan config:clear --no-ansi

if [[ "${RUN_MIGRATIONS:-true}" == "true" ]]; then
    for attempt in 1 2 3 4 5; do
        if php artisan migrate --force --no-ansi; then
            break
        fi

        if [[ "${attempt}" == "5" ]]; then
            exit 1
        fi

        echo "Migration attempt ${attempt} failed; retrying in 5 seconds..."
        sleep 5
    done
fi

php artisan config:cache --no-ansi
php artisan view:cache --no-ansi

exec apache2-foreground
