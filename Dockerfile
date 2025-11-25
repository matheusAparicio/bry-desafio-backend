FROM php:8.2-apache

# Habilita mods do Apache
RUN a2enmod rewrite

# Instala dependências
RUN apt-get update && apt-get install -y \
    libpq-dev zip unzip git libonig-dev libzip-dev supervisor \
    && docker-php-ext-install pdo pdo_pgsql zip

# Instala Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Configura diretório da aplicação
WORKDIR /var/www/html

# Copia projeto
COPY . .

# Instala dependências PHP
RUN composer install --no-dev --optimize-autoloader

# Permissões para o Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Copia arquivo de configuração do Apache
COPY docker/apache.conf /etc/apache2/sites-available/000-default.conf

EXPOSE 80

RUN echo "=== LISTING ROOT DIRECTORY ===" && ls -l / \
    && echo "=== LISTING /var/www ===" && ls -l /var/www \
    && echo "=== LISTING /var/www/html ===" && ls -l /var/www/html \
    && echo "=== FINISHED ==="

CMD ["apache2ctl", "-D", "FOREGROUND"]