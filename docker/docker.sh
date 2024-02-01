sleep 10
php artisan key:generate
apt-get update && apt-get install -y faker
composer update
php artisan jwt:secret
php artisan optimize:clear
php artisan migrate --force
php artisan db:seed
apache2-foreground