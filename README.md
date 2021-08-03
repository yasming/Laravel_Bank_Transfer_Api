# Bank_Transfer_Api

This project is an api to effectivate a bank transfer between two users.

## Prerequisites

```
Docker
```

### API Postman Collection / documentation

```
https://www.getpostman.com/collections/3c0827e291b37a5ff42f
```

### How to run project's locally

```
docker-compose up
```

```
After all containers be built the project will be able to access on localhost:80
```

```
To run project's test is need to do:

1. docker exec -it bank_transfer_nginx /bin/bash
2. php artisan test
```
