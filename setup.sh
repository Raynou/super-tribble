#!/bin/bash

# Install Moodle
chmod +rwx /var/www/html/moodle/
cd /var/www/html/moodle/admin/cli/
php install.php --lang=en --wwwroot=http://localhost/moodle --dataroot=/var/www/moodledata/ --dbtype=mysqli --dbhost=$DB_HOST --dbuser=$DB_USER --dbpass=$DB_PASSWORD --dbport=$DB_PORT --fullname=test --shortname=ts --adminuser=$MOODLE_ADMIN_USER --adminpass=$MOODLE_ADMIN_PASS --adminemail=foo@mock.com --supportemail=foo@mock.com --non-interactive --agree-license
chmod -R +rwx /var/www/html/moodle/