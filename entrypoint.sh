#!/bin/sh
sleep 60

php artisan migrate

while true; do sleep 1; done
