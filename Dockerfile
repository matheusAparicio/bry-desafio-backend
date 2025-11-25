FROM php:8.2-apache

# Enable Apache rewrite
RUN a2enmod rewrite

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libpq-dev zip unzip git libonig-dev libzip-dev supervisor \
    && docker-php-ext-install pdo pdo_pgsql zip

# Copy composer binary
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Configure Apache for Railway (port 8080)
ENV PORT=8080
EXPOSE 8080
RUN sed -i 's/Listen 80/Listen 8080/' /etc/apache2/ports.conf
RUN sed -i 's/<VirtualHost \*:80>/<VirtualHost \*:8080>/' /etc/apache2/sites-available/000-default.conf

# Set working directory
WORKDIR /var/www/html

# Copy only composer files first (better layer caching)
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --optimize-autoloader

# Now copy rest of the app
COPY . .

# Fix Laravel permissions
RUN chown -R www-data:www-data storage bootstrap/cache

# Copy Apache vhost
COPY docker/apache.conf /etc/apache2/sites-available/000-default.conf
