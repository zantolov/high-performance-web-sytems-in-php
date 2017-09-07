# High performance web system based on PHP
Zoran Antolovic master thesis sample app

## Sample application
- The application is located in implementation/symfony folder
- Database with 500.000 posts is available [here](https://drive.google.com/file/d/0B317gKEmeDtbZGlKSVVSeGFpb2c/view?usp=sharing)
- Create `.env` file (see sample below)
- Install Docker and Docker compose tools
- Configure `docker-compose.yml`
- Start containers in background: `docker-compose up -d`
- Import the database: `mysql --port=13306 --host=127.0.0.1 -uuser -ppassword mydb < db.sql`
- Install dependencies: `docker-compose exec php composer install`
- Optimized version is on `add-optimization` branch

Sample `.env` file:

```
# Symfony application's path (absolute or relative)
SYMFONY_APP_PATH=./symfony

# MySQL
MYSQL_ROOT_PASSWORD=password
MYSQL_DATABASE=mydb
MYSQL_USER=user
MYSQL_PASSWORD=password
```

## Tests
- Run tests with siege by executing `docker run --rm -t yokogawa/siege -d0 -t5m -c100 APP_URL`

## Credits

Docker image is based on https://github.com/maxpou/docker-symfony
