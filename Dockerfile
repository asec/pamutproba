FROM php:8.3-apache

COPY ./public /var/www/html
COPY ./src /var/www/src
COPY ./templates /var/www/templates
COPY ./tests /var/www/tests
COPY ./vendor /var/www/vendor
COPY ./phpunit.xml /var/www/phpunit.xml

COPY ./docker/pamut-install.sh /var/www/docker/pamut-install.sh
COPY ./docker/pamut-test.sh /var/www/docker/pamut-test.sh
COPY ./docker/.env.php /var/www/.env.php

RUN apt-get update

RUN docker-php-ext-install pdo_mysql

RUN chmod +x /var/www/docker/pamut-install.sh
RUN /var/www/docker/pamut-install.sh