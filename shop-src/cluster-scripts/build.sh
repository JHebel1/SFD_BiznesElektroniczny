#!/bin/bash
echo "start"

docker stack deploy -c docker-compose.yml BE_197648 --with-registry-auth
CONTAINER_ID=""
for i in {1..100}; do
    CONTAINER_ID=$(docker ps -q -f name=BE_197648_prestashop)
    if [ -n "$CONTAINER_ID" ]; then
        break
    fi
    echo "Retrying $i/100"
    sleep 10
done

if [ -z "$CONTAINER_ID" ]; then
    echo "Prestashop does not start"
    exit 1
fi

docker exec -u 0 -i $CONTAINER_ID bash <<EOF
echo "setting permissions"
chown -R www-data:www-data /var/www/html/var
chmod -R 775 /var/www/html/var
echo "settings prestashop configuration"
FILE="/var/www/html/app/config/parameters.php"
if [ -f "\$FILE" ]; then
    sed -i "s/'database_host' => '.*'/'database_host' => 'admin-mysql_db'/" \$FILE
    sed -i "s/'database_name' => '.*'/'database_name' => 'BE_197648'/" \$FILE
    sed -i "s/'database_user' => '.*'/'database_user' => 'root'/" \$FILE
    sed -i "s/'database_password' => '.*'/'database_password' => 'student'/" \$FILE
fi
echo "deleting cache"
rm -rf /var/www/html/var/cache/*
EOF

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

echo "Changing urls"
DB_CONTAINER_ID=$(docker ps -q -f name=admin-mysql_db)
docker exec -i $DB_CONTAINER_ID mysql -uroot -pstudent BE_197648 <<EOF
UPDATE ps_configuration SET value='localhost:19764' WHERE name IN ('PS_SHOP_DOMAIN', 'PS_SHOP_DOMAIN_SSL');
UPDATE ps_shop_url SET domain='localhost:19764', domain_ssl='localhost:19764', physical_uri='/';
UPDATE ps_configuration SET value='1' WHERE name IN ('PS_SSL_ENABLED', 'PS_SSL_ENABLED_EVERYWHERE');
EOF

echo "Database initialization completed"

echo "deleting cache"
CONTAINER_ID=$(docker ps -q -f name=BE_197648_prestashop)
docker exec -u 0 -i $CONTAINER_ID rm -rf /var/www/html/var/cache/prod
docker exec -u 0 -i $CONTAINER_ID rm -rf /var/www/html/var/cache/dev
docker exec -u 0 -i $CONTAINER_ID rm -rf /var/www/html/img/tmp
docker exec -u 0 -i $CONTAINER_ID rm -rf /var/www/html/themes/*/cache/*.php
docker exec -u 0 -i $CONTAINER_ID rm -f /var/www/html/.htaccess

docker exec -u www-data -i $CONTAINER_ID php -r "require_once('config/config.inc.php'); Tools::generateHtaccess();"
echo "END"
