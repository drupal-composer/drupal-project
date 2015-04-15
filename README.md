# Composer template for Drupal projects

**TL;DR**: Use [Composer](https://getcomposer.org/) instead of [Drush Make](https://github.com/drush-ops/drush/blob/master/docs/make.md) for Drupal 7 projects:

```sh
curl -sS https://getcomposer.org/installer | php
php composer.phar create-project drupal-composer/drupal-project:7.x-dev some-dir --stability dev --no-interaction
cd some-dir
php ../composer.phar require drupal/ctools:7.*
```

## Background

A Drupal project usually consists of the following:

* Drupal Core
* A number of modules and maybe a base theme downloaded from Drupal.org
* Perhaps even some PHP libraries found on GitHub
* Custom code written by you and your team mates

The most popular approach to assembling these parts is using [Drush Make](https://github.com/drush-ops/drush/blob/master/docs/make.md).

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

The following aims to explain Composer in relation to Drush Make based on the structure of [the Drush Make documentation](https://github.com/drush-ops/drush/blob/master/docs/make.md).

Composer has [a great introduction](https://getcomposer.org/doc/00-intro.md) and [thorough documentation of the composer.json format](https://getcomposer.org/doc/04-schema.md).

### Packagist

Composer retrieves information from packagist.org to download packages.

The Drupal community created a "Drupal Packagist" on http://packagist.drupal-composer.org
that aims to provide package information of all Projects hosted on drupal.org.
This way, modules, themes and other projects do not have to be registered on
packagist.org to be easily used in composer.

For adding the additional packagist server, simply add it to `repositories` of
your `composer.json`:

```json
  "repositories": [
    {
      "type": "composer",
      "url": "http://packagist.drupal-composer.org"
    }
  ],
```

This packagist is generated using the [drupal-parse-composer project](https://github.com/drupal-composer/drupal-parse-composer).

### Core version<a name="core"/>

In Composer, Drupal core is a package like any other. So it is added to your 
project by adding a dependency to your `composer.json`. 

Adding the following information to the `composer.json` will download the latest
 Drupal 7 release and place it in the `web/`-folder of the Composer project.

```json
{
  "require": {
    "composer/installers": "~1.0",
    "drupal/drupal": "7.*"
  },
  "extra": {
    "installer-paths": {
      "web/": ["type:drupal-core"],
    }
  }
}
```

With Drupal 8 we can choose the same approach (`"drupal/drupal": "7.*"`) or
we can use the subtree-split of the `core`-directory (`"drupal/core": "8.*"`).

Drupal 6 [will not be supported by "Drupal Packagist"](https://github.com/drupal-composer/drupal-packagist/issues/19).


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

You can also run `php composer.phar require drupal/ctools` from the command line
in the root directory of the project. This is prompt for any additional information
needed and update `composer.json` accordingly.

Drupal projects are normally not available from the default Composer package
repository _Packagist_. In order for this to work you need to add the
_Drupal Packagist_ to your `repositories` (see [Packagist](#Packagist)).


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

Patching is not natively supported by _Composer_. For implementing patches in
the composer workflow you can use a composer plugin. Currently there is discussion
on [Standarize on patch workflow/plugins](https://github.com/drupal-composer/drupal-project/issues/14).

* [Netresearch composer-patches-plugin](https://github.com/netresearch/composer-patches-plugin)
* [jpstacey / webflo's composer-patcher](https://github.com/webflo/composer-patcher)


##### Example

The following example shows usage with composer-patches-plugin.

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

The plugin does not generate a `PATCHES.txt` file for each patched project as Drush Make does.


#### Subdir<a name="subdir"></a>

With using [the Composer Installers plugin](https://github.com/composer/installers)
 you can customize the path of individual projects or by type. Therefore you will
 need to specifiy the `installer-paths` section of `composer.json`.

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

#### Location

Specifying alternate release information for the projects is possible by setting
up [a custom repository with help of Satis](https://getcomposer.org/doc/articles/handling-private-packages-with-satis.md).

#### Type

The type has to be specified in the composer.json of the Project (Module, Theme,...).
Drupal Packagist takes care of that for Drupal.org Projects. If you are maintaining
a custom composer.json you have to specify the [the package type](https://getcomposer.org/doc/04-schema.md#type)
on your own to one of the types supported by [the Composer Installers plugin](https://github.com/composer/installers).
This includes `drupal-module` and `drupal-theme`.

This is necessary to let the installer place the project in the correct directory,
when it is required `"composer/installers": "~1.0"`.

#### Directory name

Projects can be placed in specific directories when using the using
[Composer installers](https://github.com/composer/installers). See [Subdir](#subdir).

#### l10n_path

Composer does not support specifying custom paths for translations.

#### l10n_url

Composer does not support specifying custom translation servers.

#### overwrite

Composer always does install or update packages in the given path, when necessary.
So there is no need for an `overwrite` flag.

On the other hand, if you want to make sure certain files or folders should not
be overwritten, you could use [the Composer preserve paths plugin](https://github.com/derhasi/composer-preserve-paths).

#### translations

Composer does not handle handle translations.


### Project download options<a name="project-download-options">

To download a project which is not on Drupal.org or a Composer package then define
it as [a custom package repository](https://getcomposer.org/doc/05-repositories.md#package-2)
and add it as a dependency.

This method supports version constrol checkouts from custom branches or tags as
well as file downloads.


### Libraries

If libraries are not available at packagist.org, they can be retrieved by specifying
custom packages in the `repository` section. See [Project download options](#project-download-options).

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

Note: A different package type is introduced here: `drupal-library`. This allows
Composer Installer to handle library placement different compared to modules and
themes.

### Library options

Libraries are defined as Composer packages and thus support [the same options](https://getcomposer.org/doc/04-schema.md)
as Drupal projects and Composer packages in general.

#### Destination

Libraries can be placed in specific directories using the `installer-paths` section.
See [Subdir](#subdir).

### Includes

Composer uses dependencies as includes. If a dependency has dependencies on its
own and specifies these in its' `composer.json` file then these dependencies will
be installed as well. Note that a Composer package does not have to contain any
actual code. You even can specify [packages of type `metapackage`](https://getcomposer.org/doc/04-schema.md#type)
to specify dependency-wrappers that do not even install and only hold additional
dependencies in the `require` section.

Note: some schema properties are only used in the root composer.json. They are
marked as _root-only_ [in the documentation](https://getcomposer.org/doc/04-schema.md).

### Defaults

Composer does not have the concept of user-defined default values for packages.
Nonetheless, _Composer Installer_ does support setting a standard directory for
all packages of a specific type (e.g. drupal-module). See [Subdir](#subdir).

### Overriding properties

Composer does not support overriding individual properties for a package.

One approach to changing properties is to fork the package, update the `composer.json`
for the package accordingly and [add a new repository](https://getcomposer.org/doc/04-schema.md#repositories)
pointing to the fork in the root `composer.json`.

Packages overriding the Drupal projects repository should be placed before 
_Drupal Packagist_, due to the order in which Composer looks for packages.


## Recursion

Composer resolves dependencies only in the context of the provided composer.json
package information. It does not look for dependencies in other composer.json
files, for example in subdirectories of the downloaded packages.

In addition a few properties are only defined by the
[root package](https://getcomposer.org/doc/04-schema.md#root-package) - the `composer.json` for the project.

## Generate

With using [the "Composer Generate" drush extension](https://www.drupal.org/project/composer_generate)
you can now generate a basic `composer.json` file form an existing project.


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

Using Composer to manage a Drupal project would not be possible without the work
of others. Some projects are mentioned above.
