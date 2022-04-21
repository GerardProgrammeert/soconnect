#!/bin/bash
echo "asdsdsds"
composer install
composer dump-autoload
sleep 30
cp .env.example .env
DB_DATABASE="sc"
DB_USERNAME="root"
DB_PASSWORD="example"
echo "$MYSQL_ROOT_PASSWORD"

sed -i "s/DB_DATABASE=.*/DB_DATABASE=$MYSQL_DATABASE/" .env
sed -i "s/DB_USERNAME=.*/DB_USERNAME=$MYSQL_USER/" .env
sed -i "s/DB_PASSWORD=.*/DB_PASSWORD=$MYSQL_ROOT_PASSWORD/" .env

php artisan key:generate
php artisan migrate
while true; do sleep 1; done
