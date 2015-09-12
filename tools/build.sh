#!/usr/bin/env bash

echo "Clearing all environemnt caches ..."
php ../app/console cache:clear --env=dev --no-debug
php ../app/console cache:clear --env=prod --no-debug

echo "Symlinking bundle assets ..."
rm -rf ../web/bundles/
php ../app/console assets:install ../web/ --symlink --env=prod
php ../app/console assetic:dump --env=prod --no-debug