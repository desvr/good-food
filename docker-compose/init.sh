#!/bin/sh

cp .env.example .env

docker compose build

docker-compose run --rm composer clearcache
docker-compose run --rm composer dump-autoload
docker-compose run --rm composer install --no-scripts
docker-compose run --rm npm install

docker-compose run --rm artisan optimize:clear
docker-compose run --rm artisan key:generate

docker-compose run --rm artisan storage:link
docker-compose run --rm artisan migrate:fresh
docker-compose run --rm artisan db:seed

docker-compose run --rm artisan config:cache
docker-compose run --rm artisan route:cache
docker-compose run --rm artisan view:cache

docker-compose run --rm npm run build

docker compose up
