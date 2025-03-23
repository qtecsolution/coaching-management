setup:
	@make docker-up-build
	@make composer-install
	@make set-permissions
	@make setup-env
	@make generate-key
	@make migrate-fresh-seed
	@make npm-install-build
	@make npm-run-dev

docker-stop:
	docker compose stop

docker-up-build:
	docker compose up -d --build

composer-install:
	docker exec coachingms-app bash -c "composer install"

composer-update:
	docker exec coachingms-app bash -c "composer update"

set-permissions:
	docker exec coachingms-app bash -c "chmod -R 777 /var/www/storage"
	docker exec coachingms-app bash -c "chmod -R 775 /var/www/bootstrap"

setup-env:
	docker exec coachingms-app bash -c "cp .env.docker .env"

npm-install-build:
	docker exec coachingms-node bash -c "npm install"
	docker exec coachingms-node bash -c "npm run build"

npm-run-dev:
	docker exec coachingms-node bash -c "npm run dev"

npm-run-build:
	docker exec coachingms-node bash -c "npm run build"

generate-key:
	docker exec coachingms-app bash -c "php artisan key:generate"

migrate-fresh-seed:
	docker exec coachingms-app bash -c "php artisan migrate:fresh --seed"
