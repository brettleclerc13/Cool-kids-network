#!/bin/bash

service mysql start;
sleep 10
if ! mysqladmin ping -u root --silent;
then
	echo "MySQL did not start properly"
	exit 1
fi
mysql -e "CREATE DATABASE IF NOT EXISTS \`cool-kids-database\`;"
mysql -e "CREATE USER IF NOT EXISTS \`${MYSQL_USER}\`@'localhost' IDENTIFIED BY '${MYSQL_PASSWORD}';"
mysql -e "GRANT ALL PRIVILEGES ON \`cool-kids-database\`.* TO \`${MYSQL_USER}\`@'%' IDENTIFIED BY '${MYSQL_PASSWORD}';"
#mysql -e "ALTER USER 'root'@'localhost' IDENTIFIED BY '${MYSQL_ROOT_PASSWORD}';"
mysql -e "FLUSH PRIVILEGES;"
#mysqladmin -u root -p$MYSQL_ROOT_PASSWORD shutdown
mysqladmin -u root shutdown
exec mysqld_safe
