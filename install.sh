#!/bin/bash

cp -n laradock/env-example laradock/.env

sed -i "s/PHP_VERSION=7.3/PHP_VERSION=7.4/g" laradock/.env

cd laradock

docker-compose build php-fpm

docker-compose down

docker-compose up -d workspace nginx redis

docker-compose exec workspace composer install
