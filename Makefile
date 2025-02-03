x:
	docker compose exec php bash
build:
	docker compose build
run:
	docker compose up -d
check:
	composer phpcs &
	composer phpstan &
	composer psalm &
	composer test