#!/bin/sh
ln -s /etc/apache2/mods-available/rewrite.load /etc/apache2/mods-enabled/rewrite.load
ln -s /usr/local/etc/php/php.ini-development /usr/local/etc/php/php.ini

chown -R www-data:www-data /var/www

chmod +x /var/www/docker/pamut-test.sh
ln -s /var/www/docker/pamut-test.sh /usr/local/bin/pamut-test