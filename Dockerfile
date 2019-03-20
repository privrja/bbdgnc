FROM php:7.2-apache

WORKDIR /var/www/html

#install additional libraries
RUN apt-get update

RUN apt-get update && \
    apt-get install -y \
        libxml2-dev \
        git

RUN docker-php-ext-install -j$(nproc) soap

#install compoer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php -r "if (hash_file('sha384', 'composer-setup.php') === '48e3236262b34d30969dca3c37281b3b4bbe3221bda826ac6a9a62d6444cdb0dcd0615698a5cbe587c3f0fe57a54d8f5') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
RUN php composer-setup.php
RUN php -r "unlink('composer-setup.php');"

COPY . /var/www/html

RUN /var/www/html/composer.phar install

#configure Apache
ENV PORT 80
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN cp vhost.conf /etc/apache2/sites-available/bbdgnc.conf
RUN a2ensite bbdgnc.conf
RUN a2dissite 000-default.conf
RUN a2enmod rewrite