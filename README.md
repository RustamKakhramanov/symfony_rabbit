# Test task
For installation please run makefile or run described commands
## Commands:

Docker compose

```bash
docker compose up -d
```

---

Generate ssl keys:

```bash
    mkdir -p config/jwt
    openssl genpkey -out config/jwt/private.pem -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096
    openssl pkey -in config/jwt/private.pem -out config/jwt/public.pem -pubout
```

---
> ⚠️Don't forget about secret key Put pass phrase in config/packages/lexik_jwt_authentication.yaml


Generate DataBases And Tables

```bash
docker exec task_php php bin/console doctrine:database:create
docker exec task_php php bin/console doctrine:migrations:migrate
```

---

Run messages consume

```bash
docker exec task_php -it php bin/console messenger:consume-messages
```

---

### Run tests

```bash
docker exec task_php php ./vendor/bin/phpunit
```
---
