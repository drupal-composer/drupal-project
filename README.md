# Composer template for Drupal projects

[![Build Status](https://travis-ci.org/drupal-composer/drupal-project.svg?branch=8.x)](https://travis-ci.org/drupal-composer/drupal-project)

This project template should provide a kickstart for managing your site
dependencies with [Composer](https://getcomposer.org/).

If you want to know how to use it as replacement for
[Drush Make](https://github.com/drush-ops/drush/blob/master/docs/make.md) visit
the [Documentation on drupal.org](https://www.drupal.org/node/2471553).

## Usage

First you need to [install composer](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-osx).

> Note: The instructions below refer to the [global composer installation](https://getcomposer.org/doc/00-intro.md#globally).
You might need to replace `composer` with `php composer.phar` (or similar) for your setup.

After that you can create the project:

```
composer create-project drupal-composer/drupal-project:8.x-dev some-dir --stability dev --no-interaction
```

With `composer require ...` you can download new dependencies to your installation.

```
cd some-dir
composer require drupal/devel:8.*
```

## What does the template do?

When installing the given `composer.json` some tasks are taken care of:

* Drupal will be installed in the `web`-directory.
* Autoloader is implemented to use the generated composer autoloader in `vendor/autoload.php`,
  instead of the one provided by Drupal (`web/vendor/autoload.php`).
* Modules (packages of type `drupal-module`) will be placed in `web/modules/contrib/`
* Theme (packages of type `drupal-theme`) will be placed in `web/themes/contrib/`
* Profiles (packages of type `drupal-profile`) will be placed in `web/profiles/contrib/`
* Creates default writable versions of `settings.php` and `services.yml`.
* Creates `sites/default/files`-directory.
* Latest version of drush is installed locally for use at `vendor/bin/drush`.
* Latest version of DrupalConsole is installed locally for use at `vendor/bin/console`.

## Updating Drupal Core

Updating Drupal core is a two-step process.

1. Update the version number of `drupal/core` in `composer.json`.
1. Run `composer update drupal/core`.
1. Run `./scripts/drupal/update-scaffold [drush-version-spec]` to update files
   in the `web` directory, where `drush-version-spec` is an optional identifier
   acceptable to Drush, e.g. `drupal-8.0.x` or `drupal-8.1.x`, corresponding to
   the version you specified in `composer.json`. (Defaults to `drupal-8`, the
   latest stable release.) Review the files for any changes and restore any
   customizations to `.htaccess` or `robots.txt`.
1. Commit everything all together in a single commit, so `web` will remain in
   sync with the `core` when checking out branches or running `git bisect`.

## Generate composer.json from existing project

With using [the "Composer Generate" drush extension](https://www.drupal.org/project/composer_generate)
you can now generate a basic `composer.json` file from an existing project. Note
that the generated `composer.json` might differ from this project's file.


## Customize build properties

Create a new file in the root of the project named `build.properties.local`
using your favourite text editor:

```
$ vim build.properties.local
```

This file will contain configuration which is unique to your development
machine. This is mainly useful for specifying your database credentials and the
username and password of the Drupal admin user so they can be used during the
installation.

Because these settings are personal they should not be shared with the rest of
the team. Make sure you never commit this file!

All options you can use can be found in the `build.properties.dist` file. Just
copy the lines you want to override and change their values. For example:

```
# The location of the Composer binary.
composer.bin = /usr/bin/composer

# Database settings.
drupal.db.name = my_database
drupal.db.user = root
drupal.db.password = hunter2

# Admin user.
drupal.admin.username = admin
drupal.admin.password = admin

# The base URL to use in Behat tests.
behat.base_url = http://platform.local

# Verbosity of Drush commands. Set to 'yes' for verbose output.
drush.verbose = yes
```


## Listing the available build commands

You can get a list of all the available Phing build commands ("targets") with a
short description of each target with the following command:

```
$ ./vendor/bin/phing
```


## Install the website.

```
$ ./vendor/bin/phing install
```


## Set up tools for the development environment

If you want to install a version suitable for development you can execute the
`setup-dev` Phing target.

```
$ ./vendor/bin/phing setup-dev
```

This will perform the following tasks:

1. Configure Behat.
2. Configure PHP CodeSniffer.
3. Enable 'development mode'. This will:
  * Enable the services in `development.services.yml`.
  * Show all error messages with backtrace information.
  * Disable CSS and JS aggregation.
  * Disable the render cache.
  * Allow test modules and themes to be installed.
  * Enable access to `rebuild.php`.
4. Enable development modules.
5. Create a demo user for each user role.

To set up a development environment quickly, you can perform both the `install`
and `setup-dev` targets at once by executing `install-dev`:

```
$ ./vendor/bin/phing install-dev
```


## Running Behat tests

The Behat test suite is located in the `tests/` folder. The easiest way to run
them is by going into this folder and executing the following command:

```
$ cd tests/
$ ./behat
```

If you want to execute a single test, just provide the path to the test as an
argument. The tests are located in `tests/features/`:

```
$ cd tests/
$ ./behat features/authentication.feature
```

If you want to run the tests from a different folder, then provide the path to
`tests/behat.yml` with the `-c` option:

```
# Run the tests from the root folder of the project.
$ ./vendor/bin/behat -c tests/behat.yml
```


## Checking for coding standards violations

### Set up PHP CodeSniffer

PHP CodeSniffer is included to do coding standards checks of PHP and JS files.
In the default configuration it will scan all files in the following folders:
- `web/modules` (excluding `web/modules/contrib`)
- `web/profiles`
- `web/themes`

First you'll need to execute the `setup-php-codesniffer` Phing target (note that
this also runs as part of the `install-dev` and `setup-dev` targets):

```
$ ./vendor/bin/phing setup-php-codesniffer
```

This will generate a `phpcs.xml` file containing settings specific to your local
environment. Make sure to never commit this file.

### Run coding standards checks

The coding standards checks can then be run as follows:

```
# Scan all files for coding standards violations.
$ ./vendor/bin/phpcs

# Scan only a single folder.
$ ./vendor/bin/phpcs web/modules/custom/mymodule
```

### Customize configuration

The basic configuration can be changed by copying the relevant Phing properties
from the "PHP CodeSniffer configuration" section in `build.properties.dist` to
`build.properties` and changing them to your requirements. Then regenerate the
`phpcs.xml` file by running the `setup-php-codesniffer` target:

```
$ ./vendor/bin/phing setup-php-codesniffer
```

To change to PHP CodeSniffer ruleset itself, make a copy of the file
`phpcs-ruleset.xml.dist` and rename it to `phpcs-ruleset.xml`, and then put this
line in your `build.properties` file:

```
phpcs.standard = ${project.basedir}/phpcs-ruleset.xml
```

For more information on configuring the ruleset see [Annotated ruleset](http://pear.php.net/manual/en/package.php.php-codesniffer.annotated-ruleset.php).


## FAQ

### Should I commit the contrib modules I download

Composer recommends **no**. They provide [argumentation against but also workrounds if a project decides to do it anyway](https://getcomposer.org/doc/faqs/should-i-commit-the-dependencies-in-my-vendor-directory.md).
