# Bbdgnc
Building Blocks Database Generator of Natural Compounds

## Requirements
Apache or other similar server

PHP 5.3.7 or higher

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
