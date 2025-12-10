# Backend Setup Guide

## Initial Setup

After starting the Docker containers, you need to set up the Laravel backend:

### 1. Install Dependencies
```bash
docker exec -it gators_backend composer install
```

### 2. Generate Application Key
```bash
docker exec -it gators_backend php artisan key:generate
```

### 3. Set Permissions (if needed)
```bash
docker exec -it gators_backend chown -R www-data:www-data /var/www/storage
docker exec -it gators_backend chown -R www-data:www-data /var/www/bootstrap/cache
```

## MongoDB Setup

The MongoDB database is automatically initialized with the required collections and indexes via the `init-mongo.js` script.

### MongoDB Connection Details
- Host: localhost (or mongodb from within containers)
- Port: 27017
- Database: gators_dc
- Username: admin
- Password: admin123

## Creating Sample Data

You can create sample data for testing:

### 1. Create Admin User
```bash
docker exec -it gators_backend php artisan tinker
```

Then run:
```php
$user = new \App\Models\User();
$user->name = 'Admin User';
$user->email = 'admin@gatorsdc.com';
$user->password = bcrypt('password123');
$user->role = 'admin';
$user->phone = '555-0100';
$user->is_active = true;
$user->save();
```

### 2. Create Sample Player
```php
$user = new \App\Models\User();
$user->name = 'John Doe';
$user->email = 'john@example.com';
$user->password = bcrypt('password123');
$user->role = 'player';
$user->phone = '555-0101';
$user->is_active = true;
$user->save();

$player = new \App\Models\Player();
$player->user_id = $user->_id;
$player->membership_type = 'regular';
$player->membership_start_date = now();
$player->membership_end_date = now()->addYear();
$player->skill_level = 'intermediate';
$player->emergency_contact_name = 'Jane Doe';
$player->emergency_contact_phone = '555-0102';
$player->is_active = true;
$player->save();
```

### 3. Create Sample Session
```php
$session = new \App\Models\Session();
$session->title = 'Friday Night Drop-In';
$session->description = 'Casual dodgeball session for all skill levels';
$session->type = 'drop-in';
$session->date = now()->addDays(3);
$session->start_time = now()->addDays(3)->setTime(19, 0);
$session->end_time = now()->addDays(3)->setTime(21, 0);
$session->location = 'Main Gym - 123 Sports Ave';
$session->max_participants = 20;
$session->current_participants = 0;
$session->price = 15.00;
$session->skill_level_required = 'all';
$session->is_active = true;
$session->save();
```

## Troubleshooting

### MongoDB Connection Issues
If you encounter connection issues:
```bash
# Restart MongoDB container
docker-compose restart mongodb

# Check MongoDB logs
docker logs gators_mongodb
```

### Composer Issues
```bash
# Clear composer cache
docker exec -it gators_backend composer clear-cache

# Update dependencies
docker exec -it gators_backend composer update
```

### Laravel Cache Issues
```bash
docker exec -it gators_backend php artisan config:clear
docker exec -it gators_backend php artisan cache:clear
docker exec -it gators_backend php artisan route:clear
```
