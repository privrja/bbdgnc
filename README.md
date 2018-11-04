# Bbdgnc
Building Blocks Database Generator of Natural Compounds

## Requirements
Apache or other similar server

PHP 5.3.7 or higher
PHP componets need: libcurl, libxml

[Composer](https://getcomposer.org/download/)

## Install
Clone this repository
    
    git clone git@github.com:privrja/bbdgnc.git

Install dependencies via composer without dev packages

    cd bbdgnc
    composer install --no-dev

For install dev packages run

    composer install
    
Configure base url in application/config/config.php
Default value is http://localhost/ already set

    $config['base_url'] = 'http://localhost/';
    
## EasyPHP Windows configuration

In php.ini uncomment two lines:
    
    extension=php_curl.dll
    extension=php_soap.dll

Download CA certificates form [here](https://curl.haxx.se/docs/caextract.html)     

Add a path to certificate in php.ini

    curl.cainfo="<path to certificate>\cacert.pem"
    
Tested on DEVSERVER 17.0 

## XAMPP Windows configuration

In php.ini only need to uncomment one line:

    extension=php_soap.dll
    
Tested on XAMPP 5.6.38 / PHP 5.6.38


    
