sleep 10
php artisan key:generate --force
php artisan jwt:secret --force
php artisan optimize:clear
php artisan migrate --force
php artisan db:seed
apache2-foreground