## Test App

### Documentation

This is a test app includes 2 components:

1.	Backend API : is implemented using
  - PHP Laravel version 11 is used for development
  - Nginx is used as web server
  - Mysql 8 is used as data storage 

2.	Frontend
  - In scope of the test, we are going to a simple SPA Vue app which will interact to the BE via Rest API

The software is shipped via docker

### API document (Swagger)

http://localhost/api/documentation

### For local development

#### Prerequisites

- Docker
- Port 80 is free for API
- Port 3000 is free for frontend

#### Build and start containers

```
$ docker-compose build
$ docker-compose up -d
```

#### Monitor the application logs

```
$ docker-compose logs -f api front
```

#### Run test

```
 docker-compose exec api php artisan test
```

#### Start/Stop/Restart all containers

```
 docker-compose stop/start/restart
```

You can Start/Stop/Restart individual container by specifying the components such as `api`, `nginx`, `mysql`, `front`