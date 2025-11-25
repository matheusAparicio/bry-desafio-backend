#!/bin/bash
set -e

echo "Iniciando container..."

# 1. Garante permissões nas pastas de cache/storage (essencial em prod)
echo "Ajustando permissões..."
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# 2. Cria o arquivo .env se não existir (Fallback)
if [ ! -f ".env" ]; then
    echo "Criando .env vazio para o Laravel não reclamar..."
    touch .env
fi

# 3. Otimizações do Laravel para Produção
echo "Otimizando aplicação..."
php artisan config:clear
php artisan route:cache
php artisan view:cache

# 4. Rodar Migrations (Com --force para não pedir confirmação)
echo "Rodando migrations..."
php artisan migrate --force

# 5. Inicia o Apache
echo "Iniciando Apache..."
exec "$@"