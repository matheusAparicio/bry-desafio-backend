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

# 3. Configuração do Apache (Copia da pasta docker/apache)
COPY ./docker/apache/000-default.conf /etc/apache2/sites-available/000-default.conf

# 4. Instala o Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 5. Define diretório de trabalho
WORKDIR /var/www/html

# 6. Copia composer.json e composer.lock PRIMEIRO (para cache do Docker)
COPY composer.json composer.lock ./

# 7. Instala dependências (Modo Produção: sem dev, otimizado)
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

# 8. Copia o restante do código da aplicação
COPY . .

# 9. Configura permissões (O usuário do Apache precisa ser dono dos arquivos)
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# 10. Copia e configura o Entrypoint
COPY ./docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# 11. Expõe a porta 80 (O Railway mapeia isso automaticamente)
EXPOSE 80

# 12. Define o Entrypoint
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

# 13. Comando padrão
CMD ["apache2-foreground"]