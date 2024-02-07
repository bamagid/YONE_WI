#!/bin/bash
php artisan cache:clear
sed -i 's/DB_HOST=127.0.0.1/DB_HOST=mysql_yone_wi/' .env
php artisan optimize:clear
composer dump-autoload --no-scripts
docker compose up 

