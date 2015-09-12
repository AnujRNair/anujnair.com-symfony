#!/usr/bin/env bash

read -p "Are you sure you wish to destroy the DB and recreate it? (Y/n)" -n 1 -r
if [[ ! $REPLY =~ ^[Y]$ ]]
then
    printf '\nCancelled\n'
    exit 1
fi
printf '\n'

php app/console doctrine:database:drop --force
php app/console doctrine:database:create
php app/console doctrine:schema:update --force