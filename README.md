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

## Usage

The following aims to explain Composer in relation to Drush Make based on the structure of [the Drush Make documentation](http://drush.ws/docs/make.txt).

Composer has [a great introduction](https://getcomposer.org/doc/00-intro.md) and [thorough documentation of the composer.json format](https://getcomposer.org/doc/04-schema.md).

### Core version

The core version of Drupal to be used with the project is specified using the version of the `thecodingmachine/drupal` package.

The following will download Drupal 7.23:

```json 
    "require": {
	    "thecodingmachine/drupal": "7.23.*@dev",
    }
```

#### Notes
This behavior differs from both Drush Make and standard Composer.

Requiring this package will download Drupal Core as a part of the project unpack it in the root of the project. Consequently there is no need for recursive make files or the like.

The behavior is handled by a custom Composer installer, [mouf/archieve-installer](https://github.com/thecodingmachine/archive-installer).

### Projects

All Drupal projects to be retrieved should be added as dependencies with their short name prefixed by `drupal/` and their version in the format `[drupal-version].[module-major-version].[module.minor-version]`.

The following will download the [Chaos tool suite (ctools)](https://drupal.org/project/ctools) 1.4 for Drupal 7.


```json 
    "require": {
	    "drupal/ctools": "7.1.4",
    }
```

The module will be placed under `sites/all/modules/contrib/`.

You can also run `php composer.phar require drupal/ctools` from the command line in the root directory of the project. This is prompt for any additional information needed and update `composer.json` accordingly.

#### Notes

Drupal packages are normally not available from the default Composer package repository Packagist to for this to work a custom repository must be added:

```json
    "repositories": [
      {
        "type": "composer",
        "url": "http://drupal-packages.kasper.reload.dk/packages.json"
      }
    ]
```

### Project options

#### Version

#### Patch

#### Subdir

The location of modules can be changed in the `installer-paths` section of `composer.json` either by individual project or by type.

```json
    "extra": {
      "installer-paths": {
        "sites/all/modules/contrib/{$name}/": ["type:drupal-module"],
        "sites/all/themes/{$name}/": ["drupal/zen"]
      }
    }
```

Custom location of packages are handled by [the Composer Installers project](https://github.com/composer/installers).

#### Location

This is not supported by Composer.


#### Type

#### Directory name

#### l10n_path

This is not supported by Composer.

#### l10n_url

This is not supported by Composer.

#### overwrite

#### translations

This is not supported by Composer.

### Project download options

### Libraries

### Library options

### Includes

### Recursion

## Credit

* Archieve installer [mouf/archieve-installer](https://github.com/thecodingmachine/archive-installer) [introduced by The Coding Machine](http://blog.thecodingmachine.com/content/installing-drupal-using-composer).
* Composer installers [composer/installers](Custom location of packages are handled by [the Composer Installers project](https://github.com/composer/installers).
* Netresearch patches plugin [netresearch/composer-patches-plugin
](https://github.com/netresearch/composer-patches-plugin).

