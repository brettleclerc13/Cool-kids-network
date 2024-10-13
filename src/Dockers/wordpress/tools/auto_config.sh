#!/bin/sh

# Wait for database to be ready
sleep 10

# Check if WordPress is already downloaded
if [ ! -e "/var/www/wordpress/wp-config.php" ]; then
	echo "[WP-CLI] core download and config create ..."

	# Download WordPress core if not present
	if [ ! -e "/var/www/wordpress/index.php" ]; then
		wp core download --path='/var/www/wordpress' --allow-root
		if [ $? -ne 0 ]; then
		echo "Failed to download WordPress core."
		exit 1
	fi
	else
		echo "WordPress core files already present."
	fi

	# Create wp-config.php
	for i in {1..5}; do
        	wp config create \
			--dbname='cool-kids-database' \
			--dbuser=$MYSQL_USER \
			--dbpass=$MYSQL_PASSWORD \
			--dbhost=mariadb:3306 --path='/var/www/wordpress' \
			--dbcharset="utf8" \
			--dbcollate="utf8_general_ci" \
			--allow-root
		if [ $? -eq 0 ]; then
			break
		fi
		echo "Failed to create wp-config.php. Retrying in 3 seconds..."
		sleep 3
	done

	if [ $? -ne 0 ]; then
		echo "Failed to create wp-config.php after multiple attempts."
		exit 1
	fi

	chown -R root:root /var/www/wordpress/wp-config.php

	echo "[WP-CLI] core install ..."
	wp core install --url='http://localhost:8080' \
		--title='Cool Kids Network' \
		--admin_user=$WP_ADMIN_USER \
		--admin_password=$WP_ADMIN_PASS \
		--admin_email=$WP_ADMIN_EMAIL --path='/var/www/wordpress' \
		--allow-root
	if [ $? -ne 0 ]; then
		echo "Failed to install WordPress."
		exit 1
	fi

	echo "[WP-CLI] installing theme 20 22 ..."
	wp theme install twentytwentytwo --activate --allow-root
	if [ $? -ne 0 ]; then
		echo "Failed to install and activate theme."
		exit 1
	fi
	
	# Add shared group for file access permissions
	chown -R nobody:nogroup /var/www/wordpress/wp-content	
fi

echo "WordPress has been configured successfully"

# Start PHP-FPM
php-fpm82 -F
