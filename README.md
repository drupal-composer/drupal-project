# Composer template for Drupal projects

**TL;DR**: Use [Composer](https://getcomposer.org/) instead of [Drush Make](http://drush.ws/docs/make.txt) for Drupal 7 projects:

```sh
curl -sS https://getcomposer.org/installer | php
php composer.phar create-project reload/drupal-composer-project some-dir --stability dev --no-interaction
cd some-dir
php ../composer.phar require drupal/ctools:7.*
```

## Background

A Drupal project usually consists of the following:

* Drupal Core
* A number of modules and maybe a base theme downloaded from Drupal.org
* Perhaps even some PHP libraries found on GitHub
* Custom code written by you and your team mates

The most popular approach to assembling these parts is using [Drush Make](http://drush.ws/docs/make.txt). 

Meanwhile the PHP Community has gathered around another dependency manager, [Composer](https://getcomposer.org/). It is even used for [mangaing dependencies for Drupal 8 Core](https://drupal.org/node/1764330).

This project aims to be a ressource for using Composer to manage Drupal projects with the same advantages as Drush Make but without the tradeoffs regardless of what version of Drupal being used.

