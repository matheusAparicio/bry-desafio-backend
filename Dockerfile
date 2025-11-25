FROM php:8.2-apache

# Enable rewrite
RUN a2enmod rewrite

# Install system deps
RUN apt-get update && apt-get install -y \
    libpq-dev zip unzip git libonig-dev libzip-dev supervisor \
    && docker-php-ext-install pdo pdo_pgsql zip

# Move Apache to listen on 8080 (required by Railway)
ENV PORT=8080
EXPOSE 8080
RUN sed -i 's/Listen 80/Listen 8080/' /etc/apache2/ports.conf
RUN sed -i 's/<VirtualHost \*:80>/<VirtualHost \*:8080>/' /etc/apache2/sites-available/000-default.conf

# Set working dir
WORKDIR /var/www/html

# Copy ONLY needed files
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --optimize-autoloader

COPY . .

# Fix permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Copy Apache config
COPY docker/apache.conf /etc/apache2/sites-available/000-default.conf
