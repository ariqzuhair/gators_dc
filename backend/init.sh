#!/bin/bash

# Backend initialization script for Laravel with MongoDB

echo "Initializing Laravel backend..."

# Create necessary Laravel directories if they don't exist
mkdir -p storage/framework/{sessions,views,cache}
mkdir -p storage/logs
mkdir -p bootstrap/cache
mkdir -p database/{factories,migrations,seeders}
mkdir -p public

# Set permissions
chmod -R 775 storage
chmod -R 775 bootstrap/cache

echo "Laravel backend structure created successfully!"
echo ""
echo "Next steps:"
echo "1. Run: docker exec -it gators_backend composer install"
echo "2. Run: docker exec -it gators_backend php artisan key:generate"
echo "3. Run: docker-compose restart backend"
