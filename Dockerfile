FROM php:7.2-apache

WORKDIR /var/www/html

# install libraries
RUN apt-get update

RUN apt-get update && \
    apt-get install -y \
        libxml2-dev \
        zlib1g-dev \
        git \
        unzip

RUN docker-php-ext-install -j$(nproc) zip
RUN docker-php-ext-install -j$(nproc) curl
RUN docker-php-ext-install -j$(nproc) soap

# install composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php -r "if (hash_file('sha384', 'composer-setup.php') === '48e3236262b34d30969dca3c37281b3b4bbe3221bda826ac6a9a62d6444cdb0dcd0615698a5cbe587c3f0fe57a54d8f5') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
RUN php composer-setup.php
RUN php -r "unlink('composer-setup.php');"

COPY . /var/www/html

# install php dependecies
RUN /var/www/html/composer.phar install --no-dev

# configure Apache
ENV PORT 80
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN cp vhost.conf /etc/apache2/sites-available/bbdgnc.conf
RUN a2ensite bbdgnc.conf
RUN a2dissite 000-default.conf
RUN a2enmod rewrite