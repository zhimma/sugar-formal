#!/bin/sh
if [ "$(. ./.env; printf '%s' "$APP_ENV")" = "production" ]; then
    # update source code
    git pull
    # update PHP dependencies
    composer update --no-interaction --no-dev --prefer-dist
    # --no-interaction Do not ask any interactive question
    # --no-dev  Disables installation of require-dev packages.
    # --prefer-dist  Forces installation from package dist even for dev versions.
    # update database
    php artisan migrate --force
    # --force  Required to run when in production.
    php artisan optimize
    php artisan queue:restart
    sudo service php8.1-fpm restart
elif [ "$(. ./.env; printf '%s' "$APP_ENV")" = "build" ] || [ "$(. ./.env; printf '%s' "$APP_ENV")" = "staging" ]; then
    now=$(date +"%Y-%m-%d-%H-%M-%S")
    git checkout dev_master 
    git reset --hard DEV/master
    git fetch DEV master
    git reset --hard DEV/master
    git branch -c ready_to_deploy/$now
    git checkout ready_to_deploy/$now
    git push origin ready_to_deploy/$now
    hub pull-request --base mmmaya111:master --head mmmaya111:ready_to_deploy/$now -m "auto pull-request $now"
else    
    # update source code
    git pull
    # update PHP dependencies
    composer install 
    php artisan migrate --force
    # --force  Required to run when in production.
fi