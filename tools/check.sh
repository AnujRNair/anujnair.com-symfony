#!/usr/bin/env bash

echo "Checking security of bundles ..."
php ../bin/security-checker security:check ../composer.lock

echo "Linting Twig Files ..."
php ../app/console lint:twig ../src/AnujRNair/AnujNairBundle/Resources/views/