FROM php:7.2-apache

WORKDIR /var/www/html

RUN rm /bin/sh && ln -s /bin/bash /bin/sh

# install libraries
RUN apt-get update

RUN apt-get update && \
    apt-get install -y \
        libxml2-dev \
        zlib1g-dev \
        git \
        unzip \
        sqlite3

RUN docker-php-ext-install -j$(nproc) zip
RUN docker-php-ext-install -j$(nproc) soap

# nvm environment variables
ENV NVM_DIR /
ENV NODE_VERSION 11.7.0

# install nvm
# https://github.com/creationix/nvm#install-script
RUN curl --silent -o- https://raw.githubusercontent.com/creationix/nvm/v0.34.0/install.sh | bash

# install node and npm
RUN source $NVM_DIR/nvm.sh \
    && nvm install $NODE_VERSION \
    && nvm alias default $NODE_VERSION \
    && nvm use default

# add node and npm to path so the commands are available
ENV NODE_PATH $NVM_DIR/v$NODE_VERSION/lib/node_modules
ENV PATH $NVM_DIR/versions/node/v$NODE_VERSION/bin:$PATH

# install composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php -r "if (hash_file('sha384', 'composer-setup.php') === '48e3236262b34d30969dca3c37281b3b4bbe3221bda826ac6a9a62d6444cdb0dcd0615698a5cbe587c3f0fe57a54d8f5') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
RUN php composer-setup.php
RUN php -r "unlink('composer-setup.php');"

COPY . /var/www/html

COPY /deploy/config.php /var/www/html/application/config

RUN chmod 777 /var/www/html/application/logs
RUN rm /var/www/html/application/logs/log*.php
RUN chmod 777 /var/www/html/uploads

RUN chown -R www-data:www-data /var/www/html/application/db
RUN chmod -R u+w /var/www/html/application/db

# install php dependecies
RUN /var/www/html/composer.phar install --no-dev
RUN npm install

# database setup
RUN sqlite3 /var/www/html/application/db/data.sqlite < /var/www/html/deploy/database.sh
RUN chmod 777 /var/www/html/application/db/data.sqlite

# configure Apache
ENV PORT 80
ENV APACHE_DOCUMENT_ROOT /var/www/html
RUN cp vhost.conf /etc/apache2/sites-available/bbdgnc.conf
RUN a2ensite bbdgnc.conf
RUN a2dissite 000-default.conf
RUN a2enmod rewrite

# update apache port at runtime for Heroku
#ENTRYPOINT []
#CMD sed -i "s/80/$PORT/g" /etc/apache2/sites-available/bbdgnc.conf /etc/apache2/ports.conf && docker-php-entrypoint apache2-foreground
