#!/bin/sh
if [ "$(. ./.env; printf '%s' "$APP_ENV")" = "production" ]; then
    # update source code
    git pull
    # update PHP dependencies
    composer install --no-interaction --no-dev --prefer-dist
    # --no-interaction Do not ask any interactive question
    # --no-dev  Disables installation of require-dev packages.
    # --prefer-dist  Forces installation from package dist even for dev versions.
    # update database
    php artisan migrate --force
    # --force  Required to run when in production.
    php artisan optimize
    php artisan queue:restart
    sudo service php8.1-fpm restart
elif [ "$(. ./.env; printf '%s' "$APP_ENV")" = "build" ]; then
    git checkout dev_master 
    git reset --hard DEV/master
    git fetch DEV master
    git reset --hard DEV/master
    git checkout master 
    git reset --hard origin/master
    git fetch origin master
    git reset --hard origin/master
    git merge --no-ff -m "auto-merge on lzong.tw" dev_master
    git push origin master
else    
    # update source code
    git pull
    # update PHP dependencies
    composer install 
    php artisan migrate --force
    # --force  Required to run when in production.
fi