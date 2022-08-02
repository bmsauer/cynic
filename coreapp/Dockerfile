FROM ci4base

#install dependencies
COPY coreapp/composer.json composer.json
COPY coreapp/composer.lock composer.lock
RUN composer update

#copy app code over
COPY coreapp /var/www/html/ci4app

#change permissions on writable dir
RUN chown -R www-data:www-data writable



