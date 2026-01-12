#!/bin/bash

CONTAINER_ID=$(docker ps -q -f name=BE_197648_prestashop)
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

echo "deleting cache"
CONTAINER_ID=$(docker ps -q -f name=BE_197648_prestashop)
docker exec -u 0 -i $CONTAINER_ID rm -rf /var/www/html/var/cache/prod
docker exec -u 0 -i $CONTAINER_ID rm -rf /var/www/html/var/cache/dev
docker exec -u 0 -i $CONTAINER_ID rm -rf /var/www/html/img/tmp
docker exec -u 0 -i $CONTAINER_ID rm -rf /var/www/html/themes/*/cache/*.php
docker exec -u 0 -i $CONTAINER_ID rm -f /var/www/html/.htaccess

docker exec -u www-data -i $CONTAINER_ID php -r "require_once('config/config.inc.php'); Tools::generateHtaccess();"
echo "END"