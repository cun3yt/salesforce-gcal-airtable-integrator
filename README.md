# Salesforce Gcal Airtable Integrator

This project is built to fetch data from Salesforce and Client (e.g. 15Five) Google Calendar data; then associate data 
and present it in Airtable document.
 
## Environment details

* This is a PHP project
* It includes script to run periodically
* It is currently served from AWS EC2

## Initial Installation

* Install Composer: Details are [here](https://getcomposer.org/download/)
* Run Composer Install: `php composer.phar install`
* Autoload Activation: `php composer.phar dump-autoload`

## Migrations

### Database Change to Migration Class Generation & Model Generation
* `./propel diff --schema-dir models`
* `./propel model:build --schema-dir=models --output-dir=models`

### Application of Migrations
* `./propel migrate`
* [More info on Migration](http://propelorm.org/documentation/09-migrations.html)

### To Re-Load Composer Autoload 
* `php ./composer.phar dump-autoload`