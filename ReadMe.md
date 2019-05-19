### Развернуть проект.
`docker-compose -f docker-compose.dev.yml up -d`

### Установить приложения через Composer
docker-compose -f docker-compose.dev.yml run --rm php composer install

### Развернуть Базу данных (Postgres)
`docker-compose -f docker-compose.dev.yml run --rm php bin/console doctrine:migrations:migrate`

