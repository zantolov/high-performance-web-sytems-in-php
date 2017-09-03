Based on https://github.com/maxpou/docker-symfony

Create `.env` file with following content:

```
# Symfony application's path (absolute or relative)
SYMFONY_APP_PATH=./symfony

# MySQL
MYSQL_ROOT_PASSWORD=password
MYSQL_DATABASE=mydb
MYSQL_USER=user
MYSQL_PASSWORD=password
```


Run `docker-compose exec php composer install` to install dependencies
