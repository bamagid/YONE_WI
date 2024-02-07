#!/bin/bash
sed -i 's/DB_HOST=mysql_yone_wi/DB_HOST=127.0.0.1/' .env
php artisan optimize:clear