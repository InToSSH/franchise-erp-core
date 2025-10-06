up:
	@docker compose up -d
down:
	@docker compose down
dev:
	@docker compose exec -it app-dev /bin/bash

xdebug: ## Docker: enable xdebug
	docker/bin/dc_xdebug --on
	docker/bin/dc_restart_php

xdebug-off: ## Docker: enable xdebug
	docker/bin/dc_xdebug --off
	docker/bin/dc_restart_php

exec:
	@docker compose exec -it app-dev /bin/bash

install:
	docker/bin/dc_exec "composer install"
	docker/bin/dc_exec "npm install"

migrate:
	docker/bin/dc_exec "php artisan migrate"

watch: ## Start watching NPM
	npm run dev

build: ## Build NPM
	docker/bin/dc_exec "npm run build"
