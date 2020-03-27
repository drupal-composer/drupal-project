# Kalamuna composer template for Drupal projects

This template is based on the [drupal-composer/drupal-project](https://github.com/drupal-composer/drupal-project) template, with additional tools and settings specific to the Kalamuna workflow.

The goal of this repository is to provide a clean installation with just the tools and files that we need for 95% of our Drupal projects. Alternate configurations, with lesser-used packages or frameworks, should be added as separate branches which can be used when needed for particular projects.

## Usage

1. Press the "Use this template" button in Github to create a new repository for your project with a copy of the necessary files, or clone this repository manually and remove the unneeded git history.

## What does the drupal-composer/drupal-project template do?

When installing the given `composer.json` some tasks are taken care of:

* Drupal will be installed in the `web`-directory.
* Autoloader is implemented to use the generated composer autoloader in `vendor/autoload.php`,
  instead of the one provided by Drupal (`web/vendor/autoload.php`).
* Modules (packages of type `drupal-module`) will be placed in `web/modules/contrib/`
* Theme (packages of type `drupal-theme`) will be placed in `web/themes/contrib/`
* Profiles (packages of type `drupal-profile`) will be placed in `web/profiles/contrib/`
* Creates default writable versions of `settings.php` and `services.yml`.
* Creates `web/sites/default/files`-directory.
* Latest version of drush is installed locally for use at `vendor/bin/drush`.
* Latest version of DrupalConsole is installed locally for use at `vendor/bin/drupal`.
* Creates environment variables based on your .env file. See [.env.example](.env.example).

## What Kalamuna-specific features have been added?

## What features have been removed or changed from the original drupal-composer/drupal-project repository?
