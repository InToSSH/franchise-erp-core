#!/bin/bash
set -e

echo "🔧 Running Laravel setup tasks (php)..."

# Run migrations (only once in php container)
echo "⚙️  Running database migrations..."
php artisan migrate --force

# Cache optimizations
php artisan config:clear
php artisan cache:clear
php artisan config:cache
php artisan route:cache

echo "✅ PHP container is ready."

exec "$@"
