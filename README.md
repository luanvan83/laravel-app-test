## Test App

### Design document

### For local development

#### Prerequisites

- Docker
- Port 80 is free

#### Build and start containers

```
$ docker-compose build
$ docker-compose up -d
```

#### Monitor the application logs

```
$ docker-compose logs -f api
```

#### Run test

```
 docker-compose exec api php artisan test
```

#### Start/Stop/Restart all containers

```
 docker-compose stop/start/restart
```

You can Start/Stop/Restart individual container by specifying the components such as `api`, `nginx`, `mysql`