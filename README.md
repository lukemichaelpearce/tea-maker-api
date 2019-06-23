# tea-maker-api

## Installation

1. Ensure a recent stable version of docker and docker-compose are installed on your machine.

2. Clone the repository to your local machine
```
git clone https://github.com/lukemichaelpearce/tea-maker-api.git
```

3. Go to location of tea-maker-api and build docker container and launch
```
docker-compose build && docker-compose up -d
```

4. Run the following commands to run composer and migrations for Symfony
```
docker-compose exec php composer install
docker-compose exec php bin/console doctrine:database:create
docker-compose exec php bin/console make:migration
docker-compose exec php bin/console doctrine:migrations:migrate
```

5. You should now be able to visit http://localhost:8083 (or the port you have set in docker-compose.yml) and see Symfony's welcome page.