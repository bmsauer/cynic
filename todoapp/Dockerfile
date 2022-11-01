FROM ci4base

#install dependencies
COPY todoapp/composer.json composer.json
COPY todoapp/composer.lock composer.lock
RUN composer update

#copy app code over
COPY todoapp /var/www/html/ci4app

#change permissions on writable dir
RUN chown -R www-data:www-data writable



