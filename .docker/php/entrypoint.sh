#!/bin/bash

/usr/bin/npm install
/usr/local/bin/composer install
/usr/local/bin/composer dump-autoload -o

php-fpm