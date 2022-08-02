FROM php:8.1-apache

#install dependencies
RUN apt update && apt install -y git libicu-dev unzip wait-for-it
    
#install composer
COPY install-composer.sh install-composer.sh
RUN chmod +x install-composer.sh
RUN ./install-composer.sh
RUN mv composer.phar /usr/local/bin/composer && chmod +x /usr/local/bin/composer

#install php extensions
RUN docker-php-ext-install intl mysqli

#enable apache mods
RUN a2enmod rewrite

#add apache site
COPY 001-app-vhost.conf /etc/apache2/sites-available/001-app-vhost.conf
RUN a2dissite 000-default
RUN a2ensite 001-app-vhost

#add apache conf
COPY 001-app-conf.conf /etc/apache2/conf-available/001-app-conf.conf
RUN a2enconf 001-app-conf.conf

#setup app directory
RUN mkdir ci4app
WORKDIR /var/www/html/ci4app