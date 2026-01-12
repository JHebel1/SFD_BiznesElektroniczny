#!/bin/bash

echo "Changing urls"
DB_CONTAINER_ID=$(docker ps -q -f name=admin-mysql_db)
docker exec -i $DB_CONTAINER_ID mysql -uroot -pstudent BE_197648 <<EOF
UPDATE ps_configuration SET value='localhost:19764' WHERE name IN ('PS_SHOP_DOMAIN', 'PS_SHOP_DOMAIN_SSL');
UPDATE ps_shop_url SET domain='localhost:19764', domain_ssl='localhost:19764', physical_uri='/';
UPDATE ps_configuration SET value='1' WHERE name IN ('PS_SSL_ENABLED', 'PS_SSL_ENABLED_EVERYWHERE');
EOF

echo "Urls changed"