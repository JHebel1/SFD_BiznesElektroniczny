
DB_CONTAINER=db
DB_NAME=prestashop
DB_USER=root
DB_PASS=root


up:	perm
	@echo "Starting docker environment..."
	docker compose up -d
	@sleep 3
	make perm

down:
	@echo "Stopping and removing environment..."
	docker compose down -v


perm:
	@echo "Fixing file permissions..."
	sudo chown -R 33:33 ./prestashop
	sudo find ./prestashop -type d -exec chmod 755 {} \;
	sudo find ./prestashop -type f -exec chmod 644 {} \;


dump:
	@echo "Dumping database to dump.sql..."
	docker exec $(DB_CONTAINER) mysqldump \
		-u$(DB_USER) -p$(DB_PASS) \
		$(DB_NAME) \
		--single-transaction \
		--quick \
		--skip-lock-tables \
		> dump.sql
	@echo "Dump created: dump.sql"


restore:
	@echo "Restoring database from dump.sql..."
	cat dump.sql | docker exec -i $(DB_CONTAINER) mysql \
		-u$(DB_USER) -p$(DB_PASS) \
		$(DB_NAME)
	@echo "Database restored."


