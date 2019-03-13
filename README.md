# Bbdgnc
Building Blocks Database Generator of Natural Compounds

## Requirements
Apache or other similar server

PHP 7.2 or higher

PHP components need: libcurl, libxml, soap

[Composer](https://getcomposer.org/download/)

npm

SQLite3

## Install
Clone this repository
    
    git clone git@github.com:privrja/bbdgnc.git

Install dependencies via composer without dev packages

    cd bbdgnc
    composer install --no-dev

For install dev packages run

    composer install
    
For install javascript dependencies run:
    
    npm install    
    
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
    
## XAMPP Windows configuration

In php.ini only need to uncomment one line:

    extension=php_soap.dll
    
If there is a problem with the certificates try to add this line.

    curl.cainfo="C:\xampp\php\ext\cacert.pem"
 
and comment following line

    #openssl.cafile=

## Database

Application use sqlite3 database.
For creating database you'l need to create database file and run create script.

    sqlite3 application/db/data.sqlite
    .read application/db/create.sql

