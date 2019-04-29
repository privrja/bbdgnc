# Bbdgnc
Building Blocks Database Generator of Natural Compounds

## Requirements
Apache or other similar server

PHP 7.2 or higher
PHP components need: libcurl, libxml, soap

[Composer](https://getcomposer.org/download/)

[npm](https://nodejs.org/en/)

[SQLite3](https://www.sqlite.org/download.html)

## Install on XAMPP
You can download XAMPP [here](https://www.apachefriends.org/index.html)

In php.ini only need to uncomment one line:

    extension=php_soap.dll
    
It's recommended to use larger size for cookies, this problem typically appears with sequences with block count greater than 20.
To fix this problem add following line in httpd.conf:

    LimitRequestFieldSize 16380

Clone this repository
    
    git clone https://github.com/privrja/bbdgnc.git

Install dependencies via composer without dev packages

    cd bbdgnc
    composer install --no-dev

For install dev packages run

    composer install
    
For install javascript dependencies run:
    
    npm install --only=prod 
    
To install dev dependecies run:

    npm install
    
Configure base url in application/config/config.php
Default value is http://localhost/ already set

    $config['base_url'] = 'http://localhost/';
    

Application use sqlite3 database.
For creating database you can use sql scripts or application itself.
The way using application is recommended for deploying application to server.

####Using scripts

For creating database you'l need to create database file and run create script.

    sqlite3 deploy/data.sqlite
    .read deploy/create.sql
    
If you would like to have 20 amino acids in database run insert script.    
    
    .read deploy/blocks.sql

####Using aplication

You'l need to set 777 permissions to bbgdgc and bbdgnc/application folders.

    chmod 777 .
    chmod 777 ./application 
    
Then open main page of application in your browser. Create scripts run automatically.
After that restore permission of folder to 755.

    chmod 755 .
    chmod 755 ./application/

Configuration done!

## Docker

You can install application via [docker](https://www.docker.com/get-started).

For running application on localhost you only need to clone repository from docker hub and run it.

    docker pull privrja/bbdgnc
    docker run -d -p 8080:80 bbdgnc
    
For change some configuration you'll need to clone git repository setup configurations, build docker image and run it.
Typically you'll change file in folder deploy file config.php and setup right base url from localhost to your domain.

    git clone https://github.com/privrja/bbdgnc.git
    
Now change configurations, then build docker image and run it. 
    
    docker build --tag=bbdgnc .
    docker run -d -p 8080:80 bbdgnc
