#!/bin/bash
sleep 60
echo "Entrypoint"
echo "Run Composer install"
composer install
composer dump-autoload
echo "Wait for a while"
sleep 30
echo "Generate env file"
cp .env.example .env

sed -i "s/DB_HOST=.*/DB_HOST=$MYSQL_HOST/" .env
sed -i "s/DB_DATABASE=.*/DB_DATABASE=$MYSQL_DATABASE/" .env
sed -i "s/DB_USERNAME=.*/DB_USERNAME=$MYSQL_USER/" .env
sed -i "s/DB_PASSWORD=.*/DB_PASSWORD=$MYSQL_PASSWORD/" .env

php artisan key:generate
php artisan migrate
while true; do sleep 1; done
