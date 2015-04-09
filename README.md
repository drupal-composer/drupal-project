# Composer template for Drupal projects

**TL;DR**: Use [Composer](https://getcomposer.org/) instead of [Drush Make](http://drush.ws/docs/make.txt) for Drupal 7 projects:

```sh
curl -sS https://getcomposer.org/installer | php
php composer.phar create-project drupal-composer/drupal-project some-dir --stability dev --no-interaction
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

Meanwhile the PHP Community has gathered around another dependency manager, [Composer](https://getcomposer.org/). It is even used for [managing dependencies for Drupal 8 Core](https://drupal.org/node/1764330).

This project aims to be a ressource for using Composer to manage Drupal projects with the same advantages as Drush Make but without the tradeoffs regardless of what version of Drupal being used.


## Getting started

To start your first Drupal project with Composer you need to:

1. [Install Composer](https://getcomposer.org/doc/00-intro.md#system-requirements).
2. Create a `composer.json` file in the root of your project with [appropriate properties](https://getcomposer.org/doc/04-schema.md#properties) - primarily [the Drupal core package](#core) and the [Drupal.org package repository](#projects). To use the `composer.json` template provided by this project run `composer create-project drupal-composer/drupal-project project-dir --stability dev --no-interaction
3. Run `composer install` from your project directory to install Drupal 7.
4. Run `composer require drupal/some-project` to install a module or theme from Drupal.org.

You should now have a Drupal 7 installation in your project directory and be ready to include other projects from Drupal.org.


## Usage

The following aims to explain Composer in relation to Drush Make based on the structure of [the Drush Make documentation](http://drush.ws/docs/make.txt).

Composer has [a great introduction](https://getcomposer.org/doc/00-intro.md) and [thorough documentation of the composer.json format](https://getcomposer.org/doc/04-schema.md).


### Core version<a name="core"/>

The core version of Drupal to be used with the project is specified using the [Composer non-destructive archive installer plugin](https://github.com/azt3k/non-destructive-archive-installer).

Adding the following package to the `repositories` and `requires` sections will download Drupal 7.28 to the root of the Composer project:

```json
{
  "repositories": [
    {
      "type": "package",
      "package": {
        "name": "drupal/drupal",
        "type": "non-destructive-archive-installer",
        "version": "7.28",
        "dist": {
          "url": "http://ftp.drupal.org/files/projects/drupal-7.28.zip",
          "type": "zip"
        },
        "require": {
          "azt3k/non-destructive-archive-installer" : "*"
        },
        "extra": {
          "target-dir": ".",
          "omit-first-directory": "true"
        },
        "replace": {
        }
      }
    }
  ],
  "require": {
    "azt3k/non-destructive-archive-installer" : "0.2.*",
    "drupal/drupal": "7.*"
  }
}
```

Using Composer to manage Drupal projects has so far been tested with Drupal 7 projects. It may or may not work for Drupal 6 and 8.

#### Notes

This behavior differs from both Drush Make and standard Composer.

Requiring this package will download Drupal Core as a part of the project and unpack it in the root of the project. Consequently there is no need for recursive make files or the like.

Note that any core module required by contrib projects must be added in the `replace` section to inform Composer that there is no need to download these separately. This project template includes all core modules in the `replace` section.


### Projects<a name="projects"/>

All Drupal projects to be retrieved should be added as dependencies in the format `drupal/[module-short_name] and their version in the format `[drupal-version].[module-major-version].[module.minor-version]`.

The following will download the [Chaos tool suite (ctools)](https://drupal.org/project/ctools) module version 1.4 for Drupal 7.


```json
{
  "require": {
    "drupal/ctools": "7.1.4"
  }
}
```

The module will be placed under `sites/all/modules/contrib/`.

You can also run `php composer.phar require drupal/ctools` from the command line in the root directory of the project. This is prompt for any additional information needed and update `composer.json` accordingly.

#### Notes

Drupal projects are normally not available from the default Composer package repository Packagist. In order to for this to work a custom repository must be added:

```json
{
  "repositories": [
    {
      "type": "composer",
      "url": "http://packagist.drupal-composer.org"
    }
  ]
}
```

This repository is generated using the [drupal-parse-composer project](https://github.com/drupal-composer/drupal-parse-composer).


### Project options

#### Version

You specify the version of each project using [Composer package version constraints](https://getcomposer.org/doc/01-basic-usage.md#package-versions).

In this example the following releases of Drupal 7 modules will be downloaded:

* Latest stable minor release of Chaos tool suite 1.x
* Latest stable release of Features
* Latest development release of Views 3.x

```json
{
  "require": {
    "drupal/ctools": "7.1.*",
    "drupal/features": "7.*",
    "drupal/views": "7.3-dev"
  }
}
```

#### Patch

Patching projects is supported by the [Netresearch patches Composer plugin](https://github.com/netresearch/composer-patches-plugin).

To apply a patch to a project a `patches` section must be added to the `extras` section of `composer.json`.

The following will patch the Chaos tool suite version 7.1.4 with [this patch](https://drupal.org/files/issues/ctools-deleted-not-needed-element-from-array-in-node-plugin.patch):

```json
{
  "repositories": [
    {
      "type": "package",
      "package": {
        "name": "reload/drupal-composer-project-patches",
        "version": "1.0.0",
        "type": "patches",
        "require": {
          "netresearch/composer-patches-plugin": "~1.0"
        },
        "extra": {
          "patches": {
            "drupal/ctools": {
              "7.1.4": [
                {
                  "title": "Delete not needed element from array in existing node plugin",
                  "url": "https://drupal.org/files/issues/ctools-deleted-not-needed-element-from-array-in-node-plugin.patch"
                }
              ]
            }
          }
        }
      }
    }
  ],
  "require": {
    "netresearch/composer-patches-plugin": "~1.0",
    "reload/drupal-composer-project-patches": "*"
  }
}
```

The important parts about a package containing patches are:

* It must have the `patches` type
* It must require the `netresearch/composer-patches-plugin` package
* It can contain multiple patches to multiple projects
* The root package should require the patches package
* When adding or removing patches to a package the package version must be updated as well

The plugin supports [other options for specifying patches](https://github.com/netresearch/composer-patches-plugin#providing-patches) as well.

##### Notes

Patching Drupal Core may not work since Drupal Core is handled using a custom Composer installer.

The plugin does not generate a `PATCHES.txt` file for each patched project as Drush Make does.

#### Subdir<a name="subdir"></a>

The location of projects can be changed in the `installer-paths` section of `composer.json` either by individual project or by type.

```json
{
  "extra": {
    "installer-paths": {
      "sites/all/modules/contrib/{$name}/": ["type:drupal-module"],
      "sites/all/themes/{$name}/": ["drupal/zen"]
    }
  }
}
```

Custom location of packages are handled by [the Composer Installers project](https://github.com/composer/installers).

#### Location

Specifying alternate sources of Drupal projects is not immediatly supported.

One approach to achieving would be to update [the Drupal.org packages.json generator](https://github.com/reload/drupal-packages-generator) to support other updates XML servers than `updates.drupal.org`, custom vendor names and add the resulting `packages.json` to the `repositories` section.

#### Type

To specify the type of a Drupal project set [the package type](https://getcomposer.org/doc/04-schema.md#type) to one of the types supported by [the Composer Installers project](https://github.com/composer/installers). This includes `drupal-module` and `drupal-theme`.

Note that for this to work the Composer package for the project must also require `"composer/installers": "~1.0"`.

#### Directory name

Projects can be placed in specific directories using the `installer-paths` section. See [Subdir](#subdir).

#### l10n_path

Composer does not support specifying custom paths for translations.

#### l10n_url

Composer does not support specifying custom translation servers.

#### overwrite

Composer does not have an option to specify that a package should be installed in a non-empty directory.

#### translations

Composer does not handle handle translations.


### Project download options<a name="project-download-options">

To download a project which is not on Drupal.org or a Composer package then define it as [a custom package repository](https://getcomposer.org/doc/05-repositories.md#package-2) and add it as a dependency.

This method supports version constrol checkouts from custom branches or tags as well as file downloads.


### Libraries

Non-Drupal non-Composer libraries can be retrieved by specifying them as custom Package repository. See [Project download options](#project-download-options).

Example downloading jQuery UI 1.10.4:

```json
{
  "repositories": [
    {
      "type": "package",
      "package": {
        "name": "jquery/jqueryui",
        "version": "1.10.4",
        "type": "drupal-library",
        "dist": {
          "url": "http://jqueryui.com/resources/download/jquery-ui-1.10.4.zip",
          "type": "zip"
        },
        "require": {
          "composer/installers": "~1.0"
        }
      }
    }
  ],
  "require": {
    "jquery/jquery.ui": "1.10.4"
  }
}
```

#### Notes

A different package type is introduced here: `drupal-library`. This allows Composer Installer to handle library placement different compared to modules and themes.

### Library options

Libraries are defined as Composer packages and thus support [the same options](https://getcomposer.org/doc/04-schema.md) as Drupal projects and Composer packages in gneeral.

#### Destination

Libraries can be placed in specific directories using the `installer-paths` section. See [Subdir](#subdir).

### Includes

Composer uses dependencies as includes. If a dependency has dependencies on its own and specifies these in its' `composer.json` file then these dependencies will be installed as well. Note that a Composer package does not have to contain any actual code - this project is an example of just that!

If the purpose of including additional files is to define packages then Composer has different option for including package defitions through [the `repositories` section](https://getcomposer.org/doc/04-schema.md#repositories).

### Defaults

Composer does not have the concept of user-defined default values for packages.

Composer Installer does support setting a standard directory for all packages of a specific type e.g. modules. See [Subdir](#subdir).

### Overriding properties

Composer does not support overriding individual properties for a package.

One approach to changing properties is to fork the package, update the `composer.json` for the package accordingly and [add a new repository](https://getcomposer.org/doc/04-schema.md#repositories) pointing to the fork in the root `composer.json`.

Packages overriding the Drupal projects repository should be placed before this repository due to the over in which Composer looks for packages.


## Recursion

Composer resolves [dependencies and a range of other properties](https://getcomposer.org/doc/04-schema.md) from `composer.json` files recursively.

A few properties are only defined by the [root package](https://getcomposer.org/doc/04-schema.md#root-package) - the `composer.json` for the project.


## Generate

Composer does not support generating a `composer.json` file form an existing project.

## FAQ

### How is this better than Drush Make?

Drush Make has its own problems which makes it difficult to work with e.g.:

* [Multiple `.make` files](http://drupalcode.org/project/openatrium.git/tree/refs/heads/7.x-2.x) to support downloading Drupal Core with custom code
* Tricky rebuild process leading to [custom workarounds](https://drupal.org/project/drush_situs)
* Build process makes it slow to use developer tools like [git bisect](http://git-scm.com/book/en/Git-Tools-Debugging-with-Git#Binary-Search).

Also Drush Make is a tool primarily built for Drupal. [The PHP community has matured a lot lately](http://programming.oreilly.com/2014/03/the-new-php.html). Using Composer means using the same tool as the rest of the community of the makes it easy to use other libraries with a Drupal project.

[It is time to get off the island!](http://www.garfieldtech.com/blog/off-the-island-2013)

### Should I commit the contrib modules I download

Composer recommends **no**. They provide [argumentation against but also workrounds if a project decides to do it anyway](https://getcomposer.org/doc/faqs/should-i-commit-the-dependencies-in-my-vendor-directory.md).


## Credit

Using Composer to manage a Drupal project would not be possible without the work of others:

* [Non-destructive archive installer](https://github.com/azt3k/non-destructive-archive-installer) used to install Drupal Core.
* [Composer installers](https://github.com/composer/installers) used to specify custom location of packages.
* [Netresearch patches plugin](https://github.com/netresearch/composer-patches-plugin) for applying patches to Composer projects.
