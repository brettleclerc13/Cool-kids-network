COMPOSE_FILE=./src/docker-compose.yml

all: up

up:
	@mkdir -p ./src/Volumes/DB
	@mkdir -p ./src/Volumes/WordPress
	@docker-compose -f $(COMPOSE_FILE) build
	docker-compose -f $(COMPOSE_FILE) up

down:
	docker-compose -f $(COMPOSE_FILE) down

ps:
	@docker-compose -f $(COMPOSE_FILE) ps

fclean: down
	@docker rmi -f $$(docker images -qa);\
	docker volume rm $$(docker volume ls -q);\
	docker system prune -a --force

.PHONY: all up down ps fclean re
