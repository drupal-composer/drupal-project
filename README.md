# Composer template for Drupal projects

[![Build Status](https://travis-ci.org/drupal-composer/drupal-project.svg?branch=7.x)](https://travis-ci.org/drupal-composer/drupal-project)

This project template should provide a kickstart for managing your site
dependencies with [Composer](https://getcomposer.org/).

If you want to know, how to use it as replacement for
[Drush Make](https://github.com/drush-ops/drush/blob/master/docs/make.md) visit
the [Documentation on drupal.org](https://www.drupal.org/node/2471553).

## Usage

First you need to [install composer](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-osx).

> Note: The instructions below refer to the [global composer installation](https://getcomposer.org/doc/00-intro.md#globally).
You might need to replace `composer` with `php composer.phar` (or similar) for your setup.

After that you can create the project:

```
composer create-project drupal-composer/drupal-project:7.x-dev some-dir --stability dev --no-interaction
```

With `composer require ...` you can download new dependencies to your installation.

```
cd some-dir
composer require "drupal/ctools:~1.12"
```

## What does the template do?

When installing the given `composer.json` some tasks are taken care of:

* Drupal will be installed in the `web`-directory.
* Modules (packages of type `drupal-module`) will be placed in `web/sites/all/modules/contrib/`
* Theme (packages of type `drupal-module`) will be placed in `web/sites/all/themes/contrib/`
* Profiles (packages of type `drupal-profile`) will be placed in `web/profiles/`
* Libraries (packages of type `drupal-library`) will be placed in `web/sites/all/libraries/` (See Libraries)
* Helps for using othe PHP packages almost similar to the Drupal 8 version

## How to enable the Composer autoloader in your Drupal 7 website

The skeleton already installs the `composer_autoloader` module. Just enable it in the website before enabling
any possible module that have dependencies various packages.

## Adding patches to core, contrib modules or themes

You may add a patch so you don't need to maintain separate a modified module or theme to get faster a fix or make a critical change for your project. Add in `composer.json` as in the example.

```
"extra": {
  "patches": {
    "drupal/drupal": {
      "Add startup configuration for PHP server": "https://www.drupal.org/files/issues/add_a_startup-1543858-30.patch"
    }
  }
}
```

## Libraries

Libraries normally would be extra packages that need to be public available (CSS and JS).
Normally this are not maintained using Composer, but if you want to have a 100% Composer deployment and benefit from patches you can use in `composer.json` this example, changing the `repositories` section and adding in `require` section:
```

"repositories": {
  ...
  "slick": {
    "type": "package",
    "package": {
        "name": "kenwheeler/slick",
        "version": "1.6.0",
        "dist": {
            "url": "https://github.com/kenwheeler/slick/archive/1.6.0.zip",
            "type": "zip"
        },
        "source": {
            "url": "https://github.com/kenwheeler/slick.git",
            "type": "git",
            "reference": "1.6.0"
        },
        "type": "drupal-library"
    }
  }
},
"require": {
  ...
  "kenwheeler/slick": "~1.6.0"
},
```
After this run `composer update --lock` to install just the manually managed package.
_(You may run `composer require "kenwheeler/slick:~1.6.0"` as well if you add just the package definition)_

## FAQ

### Should I commit the contrib modules I download

Composer recommends **no**. They provide [argumentation against but also workrounds if a project decides to do it anyway](https://getcomposer.org/doc/faqs/should-i-commit-the-dependencies-in-my-vendor-directory.md).
