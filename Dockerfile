FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    nginx \
    supervisor \
    libpq-dev

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql pdo_pgsql mbstring exif pcntl bcmath gd

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create system user to run Composer and Artisan Commands
RUN useradd -G www-data,root -u 1000 -d /home/devuser devuser
RUN mkdir -p /home/devuser/.composer && \
    chown -R devuser:devuser /home/devuser

# Set working directory
WORKDIR /var/www

# Copy existing application directory
COPY . /var/www

# Copy existing application directory permissions
COPY --chown=devuser:devuser . /var/www

# Install dependencies
RUN composer install --optimize-autoloader --no-dev

# Generate key
RUN php artisan key:generate

# Optimize Laravel
RUN php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache

# Copy nginx config
COPY docker/nginx/render.conf /etc/nginx/sites-available/default

# Copy supervisor config
COPY docker/supervisor/render.conf /etc/supervisor/conf.d/supervisord.conf

# Create necessary directories and set permissions
RUN mkdir -p /var/www/storage/logs && \
    chmod -R 775 /var/www/storage && \
    chmod -R 775 /var/www/bootstrap/cache && \
    chown -R devuser:www-data /var/www/storage && \
    chown -R devuser:www-data /var/www/bootstrap/cache

# Expose port 10000 for Render
EXPOSE 10000

# Start supervisor
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]