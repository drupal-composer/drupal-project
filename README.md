# Composer template for Drupal projects

[![CI](https://github.com/drupal-composer/drupal-project/actions/workflows/ci.yml/badge.svg?branch=10.x)](https://github.com/drupal-composer/drupal-project/actions/workflows/ci.yml)
![LICENSE](https://img.shields.io/github/license/drupal-composer/drupal-project)

This project template provides a starter kit for managing your site
dependencies with [Composer](https://getcomposer.org/).

> [!IMPORTANT]
> [Drupal 11 branch](https://github.com/drupal-composer/drupal-project/tree/11.x) is available!

## What does the template do?

* Drupal will be installed in the `web` directory.
* Generated composer autoloader `vendor/autoload.php` is used  instead of
  `web/vendor/autoload.php` provided by Drupal core.
* Modules (packages of type `drupal-module`) will be placed in `web/modules/contrib` directory.
* Themes (packages of type `drupal-theme`) will be placed in `web/themes/contrib` directory.
* Profiles (packages of type `drupal-profile`) will be placed in `web/profiles/contrib` directory.
* Creates default writable versions of `settings.php` and `services.yml`.
* Creates `web/sites/default/files` directory.
* Drush is installed for use as `vendor/bin/drush`.
* Provides an [example](.env.example) of the `.env` file.

## Installing

> [!NOTE]
> The instructions below refer to the [global Composer installation](https://getcomposer.org/doc/00-intro.md#globally).
You might need to replace `composer` with `php composer.phar` (or similar)
for your setup.

Create your project:

```bash
composer create-project drupal-composer/drupal-project:10.x-dev some-dir --no-interaction
```

The `composer create-project` command passes ownership of all files to the
project that is created. You should create a new Git repository, and commit
all files not excluded by the `.gitignore` file.

## Usage

### Adding new dependencies

Use `composer require` to include and download dependencies for your project.

```bash
cd some-dir
composer require drupal/devel
```

By default, this project is set to install only stable releases of dependencies,
as specified by `"minimum-stability": "stable"` in `composer.json`. If you need 
to use non-stable releases (e.g., `alpha`, `beta`, `RC`), you can modify the 
version constraint to allow for such versions. For instance, to require a beta 
version of a module:

```bash
composer require drupal/devel:1.0.0-beta1
```

Alternatively, you can globally adjust the stability settings by modifying 
`composer.json` to include the desired stability level and explicitly allow it:

```json
{
    "minimum-stability": "beta",
    "prefer-stable": true
}
```

This configuration ensures that stable releases are preferred, but allows the 
installation of non-stable packages when necessary.

### Adding libraries

You can manage front-end asset libraries with Composer thanks to the
[asset-packagist repository](https://asset-packagist.org/). Composer will detect
and install new versions of a library that meet the stated constraints.

```bash
composer require bower-asset/dropzone
```

### Custom installation paths for libraries

The installation path of a specific library can be controlled by adding it to
the `extra.installer-paths` configuration preceding `web/libraries/{$name}`.
For example, the `chosen` Drupal module expects the `chosen` library to be
located on `web/libraries/chosen`, but `composer require npm-asset/chosen-js`
installs the library into `web/libraries/chosen-js`. The following configuration
overrides installation it into the expected directory:

```json
{
    "extra": {
        "installer-paths": {
            "web/libraries/chosen": [
                "npm-asset/chosen-js"
            ],
            "web/libraries/{$name}": [
                "type:drupal-library",
                "type:npm-asset",
                "type:bower-asset"
            ]
        }
    }
}
```

For more details, see https://asset-packagist.org/site/about

### Updating Drupal Core

This project will attempt to keep all of your Drupal Core files up-to-date; the
project [drupal/core-composer-scaffold](https://github.com/drupal/core-composer-scaffold)
is used to ensure that your scaffold files are updated every time `drupal/core`
is updated.

If you customize any of the "scaffolding" files (commonly `.htaccess`),
you may need to merge conflicts if any of your modified files are updated in a
new release of Drupal core.

Follow the steps below to update your Drupal core files.

1. Run `composer update "drupal/core-*" --with-dependencies` to update Drupal Core and its dependencies.
2. Run `git diff` to determine if any of the scaffolding files have changed.
   Review the files for any changes and restore any customizations to
  `.htaccess` or `robots.txt`.
3. Commit everything all together in a single commit, so `web` will remain in
   sync with the `core` when checking out branches or running `git bisect`.
4. In the event that there are non-trivial conflicts in step 2, you may wish
   to perform these steps on a branch, and use `git merge` to combine the
   updated core files with your customized files. This facilitates the use
   of a [three-way merge tool such as kdiff3](http://www.gitshah.com/2010/12/how-to-setup-kdiff-as-diff-tool-for-git.html). This setup is not necessary if your changes are simple;
   keeping all of your modifications at the beginning or end of the file is a
   good strategy to keep merges easy.

## FAQs

### Should I commit the contrib modules I download?

Composer recommends **no**. They provide [argumentation against but also
workarounds if a project decides to do it anyway](https://getcomposer.org/doc/faqs/should-i-commit-the-dependencies-in-my-vendor-directory.md).

### Should I commit the scaffolding files?

The [Drupal Composer Scaffold](https://github.com/drupal/core-composer-scaffold)
plugin can download the scaffold files (like `index.php`, `update.php` etc.) to
the `web` directory of your project. If you have not customized those files you
could choose to not check them into your version control system (e.g. git).
If that is the case for your project, it might be convenient to automatically
run the drupal-scaffold plugin after every install or update of your project.
You can achieve that by registering `@composer drupal:scaffold` as `post-install`
and `post-update` command in your `composer.json`:

```json
"scripts": {
    "post-install-cmd": [
        "@composer drupal:scaffold",
        "..."
    ],
    "post-update-cmd": [
        "@composer drupal:scaffold",
        "..."
    ]
},
```

### How can I apply patches to included dependencies?

If you need to apply patches, you can do so with the
[composer-patches](https://github.com/cweagans/composer-patches) plugin included
in this project.

To add a patch to Drupal module `foobar`, insert the `patches` section in the
`extra` section of `composer.json`:

```json
"extra": {
    "patches": {
        "drupal/foobar": {
            "Patch description": "URL or local path to patch"
        }
    }
}
```

### How do I specify a PHP version?

There are 2 places where Composer will be looking for PHP version requirements
when resolving dependencies:
1. The `require.php` version value in `composer.json`.
2. The `config.platform` version value in `composer.json`.

The purpose of `require.php` is to set the minimum PHP language requirements
for a package. For example, the minimum version required for Drupal 10.0 is
`8.0.2` or above, which can be specified as `>=8`.

The purpose of `config.platform` is to set the PHP language requirements for the
specific instance of the package running in the current environment. For
example, while the minimum version required for Drupal 10 is `8.0.2` or above,
the  actual PHP version on the hosting provider could be `8.1.0`. The value of
this field should provide your exact version of PHP with all 3 parts of the
version.

#### Which versions to specify in my Drupal site?

This project includes `drupal/core` which already has `require.php` added. Your
would inherit that constraint. There is no need to add `require.php` to your
`composer.json`.

`config.platform` is a platform-specific. It is recommended to specify
`config.platform` as a _specific version_ (e.g.`8.1.19`) constraint to ensure
that only the package versions supported by your current environment are used.

```json
"config": {
    "platform": {
        "php": "8.1.19"
    }
},
```
