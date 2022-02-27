PWD			:= $(shell pwd)
PHP			:= task_php

# DO NOT USE IN PROD

up:
	@docker-compose up --build -d --force-recreate
	@mkdir -p $(PWD)/config/jwt
	@openssl genpkey -out $(PWD)/config/jwt/private.pem -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096
	@openssl pkey -in $(PWD)/config/jwt/private.pem -out config/jwt/public.pem -pubout
	@docker exec $(PHP) php bin/console doctrine:database:create
	@docker exec $(PHP) php bin/console doctrine:migrations:migrate
