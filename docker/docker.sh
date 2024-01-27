sleep 10
php artisan key:generate
php artisan jwt:secret
php artisan optimize:clear
php artisan migrate --force
php artisan db:seed
apache2-foreground