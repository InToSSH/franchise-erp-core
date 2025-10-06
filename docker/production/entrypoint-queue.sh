#!/bin/bash
set -e

echo "🔧 Running Laravel setup tasks (queue)..."

# Cache optimizations
php artisan config:clear
php artisan cache:clear
php artisan config:cache
php artisan route:cache

echo "✅ Queue container is ready. Starting Horizon..."
exec php artisan horizon
