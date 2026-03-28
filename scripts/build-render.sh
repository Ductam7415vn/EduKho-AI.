#!/usr/bin/env bash
# Build script for Render
# This script is executed during the build process

echo "🔨 Starting Render build process..."

# Install Composer dependencies
echo "📦 Installing Composer dependencies..."
composer install --optimize-autoloader --no-dev

# Generate application key if not exists
if [ -z "$APP_KEY" ]; then
    echo "🔑 Generating application key..."
    php artisan key:generate --force
fi

# Clear and cache configs
echo "⚡ Optimizing application..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

php artisan config:cache
php artisan route:cache
php artisan view:cache

# Create necessary directories
echo "📁 Creating storage directories..."
mkdir -p storage/app/public
mkdir -p storage/framework/{cache,sessions,testing,views}
mkdir -p storage/logs
mkdir -p bootstrap/cache

# Set permissions
echo "🔒 Setting permissions..."
chmod -R 775 storage bootstrap/cache

# Create storage symlink
echo "🔗 Creating storage symlink..."
php artisan storage:link --force

echo "✅ Build completed successfully!"