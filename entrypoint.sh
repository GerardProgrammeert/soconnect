#!/bin/bash
echo "Entrypoint"
echo "Run Composer install"
composer install
composer dump-autoload
echo "Generate env file"
cp .env.example .env

sed -i "s/DB_HOST=.*/DB_HOST=$MYSQL_HOST/" .env
sed -i "s/DB_DATABASE=.*/DB_DATABASE=$MYSQL_DATABASE/" .env
sed -i "s/DB_USERNAME=.*/DB_USERNAME=$MYSQL_USER/" .env
sed -i "s/DB_PASSWORD=.*/DB_PASSWORD=$MYSQL_PASSWORD/" .env

php artisan key:generate
php artisan optimize
php artisan migrate
php artisan db:seed --class=EspressoMachineTableSeeder
php artisan serve --host=0.0.0.0 --port=8000
