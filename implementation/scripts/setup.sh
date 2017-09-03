# Start containers
docker-compose build
docker-compose up -d

# Import db
mysql --port=13306 --host=127.0.0.1 -uuser -ppassword mydb < db.sql

