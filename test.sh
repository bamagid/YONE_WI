#!/bin/bash
sed -i "s/DB_DATABASE=yone_wi/DB_DATABASE=yone_witest/" .env
php artisan optimize:clear
if [ $# -eq 0 ]; then
    php artisan test
else
    php artisan test --filter "$1"
fi
sed -i "s/DB_DATABASE=yone_witest/DB_DATABASE=yone_wi/" .env