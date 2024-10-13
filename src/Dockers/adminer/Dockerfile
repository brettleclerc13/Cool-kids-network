FROM alpine:3.19

RUN apk upgrade
RUN apk add curl php php-fpm php-mysqli php-json php-xml php-pdo php-dom php-simplexml php-session php-ctype mariadb-client

RUN curl -L -O https://github.com/vrana/adminer/releases/download/v4.8.1/adminer-4.8.1.php
RUN mkdir -p /var/www/html
RUN mv ./adminer-4.8.1.php /var/www/html/index.php
RUN adduser -u 82 -D -S -G nobody nogroup

COPY /conf/www.conf /etc/php82/php-fpm.d/www.conf
RUN chmod 755 /etc/php82/php-fpm.d/www.conf
RUN mkdir -p /run/php

CMD [ "php-fpm82", "-F" ]

