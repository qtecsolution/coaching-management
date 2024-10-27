setup:
	@make docker-up-build
	@make composer-install
	@make npm-install-build
	@make set-permissions
	@make setup-env
	@make generate-key
	@make migrate-fresh-seed

docker-stop:
	docker compose stop

docker-up-build:
	docker compose build --no-cache
	docker compose up -d

composer-install:
	docker exec coaching-app bash -c "composer install"

composer-update:
	docker exec coaching-app bash -c "composer update"

set-permissions:
	docker exec coaching-app bash -c "chmod -R 777 /var/www/storage"
	docker exec coaching-app bash -c "chmod -R 777 /var/www/bootstrap"

setup-env:
	docker exec coaching-app bash -c "cp .env-docker.example .env"

npm-install-build:
	docker exec coaching-app bash -c "npm install"
	docker exec coaching-app bash -c "npm run build"

generate-key:
	docker exec coaching-app bash -c "php artisan key:generate"

migrate-fresh-seed:
	docker exec coaching-app bash -c "php artisan migrate:fresh --seed"
