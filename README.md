
[project] (https://[project].nl en https://cms.[project].nl)
=====
_Laatst bijgewerkt 10-12-2019 door Jelle._

> Voor installatie zie onderste gedeelte van deze Readme

> Over [project] is de Corporate website van [project].

[README [project]; development](./README.md) | [Frontend](./frontend/README.md)

## Afspraken bij ontwikkeling
1. Code wordt in engelse taal geschreven incl comments en t() functies.
1. Labels/page-paden mogen in het nederlands (Deze zijn later altijd evt. aan te passen)
1. Gebruik van view modes / form modes ipv velden in views (Met uitzondering van tabellen en simpele lijsten)
1. Let op naamgeving machine-names. Deze worden automatisch gegenereerd maar zijn daardoor soms nietszegggen. Controleer deze op format en leesbaarheid (Engelse taal heeft voorkeur)
1. Drupal coding standarts [Coding standards Drupal.org](https://www.drupal.org/docs/develop/standards/coding-standards)
1. Drupal 8 (Laatste stabiele versie)
1. Composer require/update voor contrib module installatie & updates / composer install als je project binnentrekt
1. Templates staan in ```/docs/templates/```

********

## Testen

Er zijn nog geen tests geschreven voor dit project. Wel wordt Grumphp vereist bij development op [project].

Zie `installeren` voor informatie over het gebruik hiervan.

## Omgevingen
* Live omgeving: [[project].nl](https://[project].nl)
* Live CMS omgeving: [cms.[project].nl](https:cms.[project].nl)
* Acc omgeving: [acc.[project].nl](https://acc.[project].nl)
* Acc CMS omgeving:  [acccms.[project].nl](https://acccms.[project].nl)
* Test omgeving [test.[project].nl](https://test.[project].nl)
* Text CMS omgeving: [testcms.[project].nl](https://testcms.[project].nl)

### Waar is het gehost?
Over [project] wordt gehost bij Combell en Now (Frontend).
Zie [servers.yml](https://gitlab.frmwrk.nl/drupal/clients/blob/master/[project]/servers.yml) voor details.

## Installeren

1. Je kunt zelf je dev stack gebuiken. voor DDEV zie de [algemene uitleg](https://gitlab.frmwrk.nl/drupal/docs/blob/master/Best%20practices/DDEV%20local%20development.md)
   1. Verander de projectnaam in `.ddev/config.yml`

1. Installeer de dependencies
    ```
    composer install
    ```

1. Installeer grumphp voor code quality checks
    ```shell script
    vendor/bin/grumphp git:init
    ```

1. Kopieer ```/web/sites/default/template.settings.local.php``` naar ```default/settings.local.php```  ;

1. Voor de frontend volg de [Frontend](./frontend/README.md) in `/frontend`

### Werken aan feature

1. Checkout feature branch op basis van master.

1. Update configuratie en packages
    ```shell script
    composer install
    drush updb
    drush cim
    drush locale-check && drush locale-update
    ```

1. Als je lang aan een feature branch werkt. rabase dan op master

1. Check je code met
    ```shell script
    vendor/bin/grumphp run
    ```

## Releasen
Releases gaan via CI.

## Custom modules in this project

Custom modules staan in ```/modules/custom```

* **[project]_decoupled** - Graphql extensies voor [project]. Gebaseerd op de frmwrk_decoupled module

## Documentation

* `Nog geen externe documentatie`

## Drupal-structuur

### Functionaliteit / Modules

* `Bijzonderheden hier`

## Back-end Ontwikkelflow Drupal

1. Basis-configuratie Drupal
    * Search index
    * Content-types
    * Pages
    * url-alias patterns
    * Site-instellingen
1. permissions
1. Security-checks
1. redirects
1. Optimalisatie performace
1. Inregelen live - caching / Cron

# Composer template for FRMWRK Drupal projects

This project template provides a starter kit for managing your site
dependencies with [Composer](https://getcomposer.org/).

If you want to know how to use it as replacement for
[Drush Make](https://github.com/drush-ops/drush/blob/8.x/docs/make.md) visit
the [Documentation on drupal.org](https://www.drupal.org/node/2471553).

## Usage

First you need to [install composer](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-osx).

> Note: The instructions below refer to the [global composer installation](https://getcomposer.org/doc/00-intro.md#globally).
You might need to replace `composer` with `php composer.phar` (or similar)
for your setup.

After that you can create the project:

  1. Create new base
    ```
    composer create-project frmwrk/drupal-project:base-dev some-dir --no-interaction
    ```

  1.  Spin up you development container DDEV (See DDEV manual)

  1.  run `drush si --existing-config`

  1.  Commit the initial files.

  1. With `composer require ...` you can download new dependencies to your
installation.

## Updating Drupal Core

Follow the steps below to update your core files.

1. Run `composer update drupal/core webflo/drupal-core-require-dev "symfony/*" --with-dependencies` to update Drupal Core and its dependencies.
