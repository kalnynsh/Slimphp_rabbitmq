up: docker-up

init: docker-clear-hard docker-up-build api-permissions api-env api-composer api-genrsa pause api-migration api-fixtures frontend-env frontend-install frontend-build storage-permissions websocket-env websocket-key websocket-init websocket-start

docker-clear-hard:
	docker-compose down --remove-orphans
	sudo rm -rf api/var/docker

pause:
	sleep 5

docker-down:
	docker-compose down --remove-orphans

docker-up:
	docker-compose up -d

docker-up-build:
	docker-compose up --build -d

api-permissions:
	sudo chmod 777 api/var
	sudo chmod 777 api/var/cache
	sudo chmod 777 api/var/log
	sudo chmod 777 api/var/mail
	sudo chmod 777 storage/public/video

api-env:
	docker-compose exec api-php-cli rm -f .env
	docker-compose exec api-php-cli ln -sr .env.example .env

api-composer:
	docker-compose exec api-php-cli composer install

api-genrsa:
	docker-compose exec api-php-cli openssl genrsa -out private.key 2048
	docker-compose exec api-php-cli openssl rsa -in private.key -pubout -out public.key

api-migration:
	docker-compose exec api-php-cli composer app migrations:migrate

api-fixtures:
	docker-compose exec api-php-cli composer app fixtures:load

frontend-env:
	docker-compose exec frontend-nodejs rm -f .env
	docker-compose exec ln -sr .env.local.example .env

frontend-install:
	docker-compose exec frontend-nodejs npm install

frontend-build:
	docker-compose exec frontend-nodejs npm run build

frontend-watch:
	docker-compose exec frontend-nodejs npm run watch

storage-permissions:
	sudo chmod 777 storage/public/video

websocket-init:
	docker-compose exec websocket-nodejs npm install

websocket-update:
	docker-compose exec websocket-nodejs npm update

websocket-env:
	docker-compose exec websocket-nodejs rm -f .env
	docker-compose exec websocket-nodejs ln -sr .env.example .env

websocket-key:
	rm -f ./websocket/public.key
	cp ./api/public.key ./websocket/public.key

websocket-start:
	docker-compose exec websocket-nodejs npm run start
