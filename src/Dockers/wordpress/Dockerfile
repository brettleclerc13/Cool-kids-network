FROM alpine:3.19

#dependencies
RUN apk upgrade
RUN apk add wget
RUN apk add php php-fpm php-mysqli php-phar php-iconv php-tokenizer redis php-redis php-ctype mariadb-client

#php additional settings
COPY /conf/www.conf /etc/php82/php-fpm.d/www.conf
RUN chmod 755 /etc/php82/php-fpm.d/www.conf
RUN mkdir -p /run/php

#WP-CLI for automatic configuration
RUN wget https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar
RUN chmod +x wp-cli.phar
RUN mv wp-cli.phar /usr/local/bin/wp

#configuration script
COPY /tools/auto_config.sh /usr/local/bin/auto_config.sh
RUN chmod 755 /usr/local/bin/auto_config.sh

WORKDIR /var/www/wordpress

ENTRYPOINT [ "/usr/local/bin/auto_config.sh" ]
