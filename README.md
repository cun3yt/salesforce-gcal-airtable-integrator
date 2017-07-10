# Attent's Integrator for Different Services

This project is built to fetch data from Attent's Clients' Salesforce and Google Calendar data; and associate/present 
data in internal relational database.
 
## Environment details

* This is a PHP project
* It includes script to run periodically
* It is currently served from AWS EC2

## Initial Installation

* Install Composer: Details are [here](https://getcomposer.org/download/)
* Run Composer Install: `php composer.phar install`
* Autoload Activation: `php composer.phar dump-autoload`

## Propel Runtime Connection Settings
* `./propel config:convert` (@todo This needs to be part of deployment!)

## Migrations

### Database Change to Migration Class Generation & Model Generation
* `./propel diff --schema-dir models --table-renaming`
* `./propel model:build --schema-dir=models --output-dir=models`

### Application of Migrations
* `./propel migrate`
* [More info on Migration](http://propelorm.org/documentation/09-migrations.html)

### To Re-Load Composer Autoload 
* `php ./composer.phar dump-autoload`

### Onboarding a New Client
These scripts must run in this particular order:

* gcal-sync.php [*needs to run again & again until all consumed**, due to the ugly setup of pseudo-pagination]
* map-contact-to-account.php [one time]
* map-account-to-sfdc.php [one time]
* map-attendee-to-sfdc.php [one time]
* map-opportunity-to-sfdc.php [one time]
  
