#!/bin/bash

# Install PHP and cURL if they're not already installed
if ! command -v php &> /dev/null
then
    sudo apt-get update
    sudo apt-get install -y php
fi

if ! command -v curl &> /dev/null
then
    sudo apt-get update
    sudo apt-get install -y curl
fi

# Download and install Composer
EXPECTED_SIGNATURE="$(curl -sS https://composer.github.io/installer.sig)"
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
ACTUAL_SIGNATURE="$(php -r "echo hash_file('sha384', 'composer-setup.php');")"

if [ "$EXPECTED_SIGNATURE" != "$ACTUAL_SIGNATURE" ]
then
    >&2 echo 'ERROR: Invalid installer signature'
    rm composer-setup.php
    exit 1
fi

sudo php composer-setup.php --install-dir=/usr/local/bin --filename=composer --quiet
RESULT=$?
rm composer-setup.php
exit $RESULT
