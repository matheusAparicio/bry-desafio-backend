FROM php:8.2-apache

# Habilita mod_rewrite
RUN a2enmod rewrite

# Instala extensões e dependências
RUN apt-get update && apt-get install -y \
    libpq-dev zip unzip git libonig-dev libzip-dev supervisor \
    && docker-php-ext-install pdo pdo_pgsql zip

# Copia o Composer da imagem oficial
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Define diretório de trabalho
WORKDIR /var/www/html

# Copia toda a aplicação para o container
COPY . .

# Instala as dependências do Laravel
RUN composer install --no-dev --optimize-autoloader

# Ajusta permissões das pastas
RUN chown -R www-data:www-data storage bootstrap/cache

# Configuração do Apache
COPY docker/apache.conf /etc/apache2/sites-available/000-default.conf

# Railway expõe somente porta 80
EXPOSE 80

# Comando para manter o Apache rodando
CMD ["apache2ctl", "-D", "FOREGROUND"]
