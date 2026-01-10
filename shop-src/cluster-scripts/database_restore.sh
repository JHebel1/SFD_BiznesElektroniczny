#!/bin/bash

DB_CONTAINER_ID=$(docker ps -q -f name=admin-mysql_db)

echo "Creating database"
docker exec -i $DB_CONTAINER_ID mysql -uroot -pstudent <<EOF
DROP DATABASE IF EXISTS BE_197648;
CREATE DATABASE IF NOT EXISTS BE_197648 CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
GRANT ALL PRIVILEGES ON BE_197648.* TO 'root'@'%';
FLUSH PRIVILEGES;
EOF

echo "Restoring database from dump.sql"
docker exec -i $DB_CONTAINER_ID mysql -uroot -pstudent BE_197648 < dump.sql

echo "Database initialization completed"