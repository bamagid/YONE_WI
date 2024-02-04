#!/bin/bash
sed -i "/DB_DATABASE=yone_wi/d\
DB_DATABASE=yone_witest" .env
php artisan optimize:clear
if [ $# -eq 0 ]; then
    php artisan test
else
    php artisan test --filter "$1"
fi
sed -i "/DB_DATABASE=yone_witest/d\
DB_DATABASE=yone_wi" .env