# Composer template for Drupal projects

[![Build Status](https://travis-ci.org/drupal-composer/drupal-project.svg?branch=7.x)](https://travis-ci.org/drupal-composer/drupal-project)

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

```
composer create-project drupal-composer/drupal-project:7.x-dev some-dir --no-interaction
```

With `composer require ...` you can download new dependencies to your
installation.

```
cd some-dir
composer require drupal/devel:~1.0
```

The `composer create-project` command passes ownership of all files to the
project that is created. You should create a new git repository, and commit
all files not excluded by the .gitignore file.

## What does the template do?

When installing the given `composer.json` some tasks are taken care of:

* Drupal will be installed in the `web`-directory.
* Modules (packages of type `drupal-module`) will be placed in `web/sites/all/modules/contrib/`
* Theme (packages of type `drupal-module`) will be placed in `web/sites/all/themes/contrib/`
* Profiles (packages of type `drupal-profile`) will be placed in `web/profiles/`
* Libraries (packages of type `drupal-library`) will be placed in `web/sites/all/libraries/` (See Libraries)
* Helps for using other PHP packages almost similar to the Drupal 8 version
* Creates default writable versions of `settings.php`.
* Creates `web/sites/default/files`-directory.
* Latest version of drush is installed locally for use at `vendor/bin/drush`.

## Generate composer.json from existing project

With using [the "Composer Generate" drush extension](https://www.drupal.org/project/composer_generate)
you can now generate a basic `composer.json` file from an existing project. Note
that the generated `composer.json` might differ from this project's file.

## How to enable the Composer autoloader in your Drupal 7 website

The skeleton already installs the `composer_autoloader` module. Just enable it in the website before enabling
any possible module that have dependencies various packages.

## Libraries

Libraries normally would be extra packages that need to be public available (CSS and JS).
Normally this are not maintained using Composer, but if you want to have a 100% Composer deployment and benefit from patches you can use in `composer.json` this example, changing the `repositories` section and adding in `require` section:
```

"repositories": [
  ...
  {
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
],
"require": {
  ...
  "kenwheeler/slick": "~1.6.0"
},
```
After this run `composer update --lock` to install just the manually managed package.
_(You may run `composer require "kenwheeler/slick:~1.6.0"` as well if you add just the package definition)_

## FAQ

### Should I commit the contrib modules I download?

Composer recommends **no**. They provide [argumentation against but also
workrounds if a project decides to do it anyway](https://getcomposer.org/doc/faqs/should-i-commit-the-dependencies-in-my-vendor-directory.md).

### How can I apply patches to downloaded modules?

If you need to apply patches (depending on the project being modified, a pull
request is often a better solution), you can do so with the
[composer-patches](https://github.com/cweagans/composer-patches) plugin.

To add a patch to drupal module foobar insert the patches section in the extra
section of composer.json:
```json
"extra": {
    "patches": {
        "drupal/foobar": {
            "Patch description": "URL or local path to patch"
        }
    }
}
```
### How do I switch from packagist.drupal-composer.org to packages.drupal.org?

Follow the instructions in the [documentation on drupal.org](https://www.drupal.org/docs/develop/using-composer/using-packagesdrupalorg).

### How do I specify a PHP version ?

This project supports PHP 5.3 as minimum version (see [Drupal 7 PHP requirements](https://www.drupal.org/docs/7/system-requirements/php-requirements)), however it's possible that a `composer update` will upgrade some package that will then require PHP 7+.

To prevent this you can add this code to specify the PHP version you want to use in the `config` section of `composer.json`:
```json
"config": {
    "sort-packages": true,
    "platform": {
        "php": "5.3.3"
    }
},
```
