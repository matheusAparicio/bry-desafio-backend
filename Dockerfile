FROM php:8.2-apache

# 1. Instala dependências do sistema
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libpq-dev \
    libzip-dev \
    && docker-php-ext-install pdo_pgsql mbstring exif pcntl bcmath gd zip

# 2. Habilita mod_rewrite do Apache
RUN a2enmod rewrite

# 3. Configuração do Apache
COPY ./docker/apache/000-default.conf /etc/apache2/sites-available/000-default.conf

# 4. Instala o Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 5. Define diretório de trabalho
WORKDIR /var/www/html

# 6. Copia composer.json e composer.lock PRIMEIRO
COPY composer.json composer.lock ./

# 7. Instala dependências (CORREÇÃO AQUI: adicionado --no-scripts)
# Isso impede que ele tente rodar 'php artisan' antes de copiar o projeto
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader --no-scripts

# 8. Copia o restante do código da aplicação (Agora o arquivo artisan chega)
COPY . .

# 9. Roda o dump-autoload final para garantir que tudo foi mapeado corretamente
RUN composer dump-autoload --optimize

# 10. Configura permissões
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# 11. Entrypoint
COPY ./docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 80

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

CMD ["apache2-foreground"]