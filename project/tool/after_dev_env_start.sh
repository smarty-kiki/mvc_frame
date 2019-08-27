#!/bin/bash

ENV=development php /var/www/mvc_frame/public/cli.php migrate:install
ENV=development php /var/www/mvc_frame/public/cli.php migrate
