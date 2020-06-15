#!/usr/bin/env bash

echo "Clearing bundles dir ..."
rm -rf web/bundles/

echo "Creating assets via webpack ..."
NODE_ENV=production npx webpack --config=webpack/config.prd.js

echo "Clearing all environment caches ..."
php app/console cache:clear --env=dev --no-debug
php app/console cache:clear --env=prod --no-debug

echo "Installing bundle assets ..."
php app/console assets:install web/ --env=prod

echo "Creating assets via webpack again ..."
NODE_ENV=production npx webpack --config=webpack/config.prd.js

echo "Dumping bundle assets ..."
php app/console assetic:dump --env=prod --no-debug
