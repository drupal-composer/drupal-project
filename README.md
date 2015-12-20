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

## Using in Docker on Mac OS X
The following allows you to run a Drupal app on your Mac OS X filesystem within a PHP 5.6-Apache Docker container against a MySQL database. It uses a modified version of the [official Drupal](https://hub.docker.com/_/drupal/) Dockerfile.

As Drupal requires write access to some of the app filesystem, download and install [docker-osx-dev](https://github.com/brikis98/docker-osx-dev):
```
curl -o /usr/local/bin/docker-osx-dev https://raw.githubusercontent.com/brikis98/docker-osx-dev/master/src/docker-osx-dev
chmod +x /usr/local/bin/docker-osx-dev
docker-osx-dev install
```

Clone this project to your development machine:
```
git clone git@github.com:drupal-composer/drupal-project.git <your-drupal-app-path>
```

Give Docker containers access to your Drupal code. Use `docker-osx-dev` to mount and rsync local drupal code in `boot2docker`:
```
docker-osx-dev -s <your-drupal-app-path>
```
See `docker-osx-dev` documentation. This shell is now watching your files. 

In a new shell, run the `docker-dev` Drupal setup script for OS X, with the argument values you want:
```
<your-drupal-app-path>/docker-dev/setup-osx --app-path <your-drupal-app-path> --app-name drupal-app --container-name some-drupal-app --mysql-container-name some-mysql --mysql-root-password some-password --localhost-port 8080
```

See the [official Drupal Docker setup](https://hub.docker.com/_/drupal/) to understand these arguments. Set `localhost-port` as the port you want your `localhost` Drupal app to be served on.

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


## FAQ

### Should I commit the contrib modules I download

Composer recommends **no**. They provide [argumentation against but also workrounds if a project decides to do it anyway](https://getcomposer.org/doc/faqs/should-i-commit-the-dependencies-in-my-vendor-directory.md).

### How can I apply patches to downloaded modules?

If you need to apply patches (depending on the project being modified, a pull request is often a better solution), you can do so with the [composer-patches](https://github.com/cweagans/composer-patches) plugin.

To add a patch to drupal module foobar insert the patches section in the extra section of composer.json:
```json
"extra": {
    "patches": {
        "drupal/foobar": {
            "Patch description": "URL to patch"
        }
    }
}
```
