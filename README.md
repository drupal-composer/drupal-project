# Kalamuna composer template for Drupal projects

This template is based on the [drupal-composer/drupal-project](https://github.com/drupal-composer/drupal-project) template, with additional tools and settings specific to the Kalamuna workflow.

The goal of this repository is to provide a clean installation with just the tools and files that we need for 95% of our Drupal projects. Alternate configurations, with lesser-used packages or frameworks, should be added as separate branches which can be used when needed for particular projects.

## Usage

1. Press the "Use this template" button in Github to create a new repository for your project with a copy of the necessary files, or clone this repository manually and remove the unneeded git history.
1. Create an environment for your project on Pantheon.
    1. Select the regular Drupal 8 upstream, so the hidden framework variable is properly set to Drupal.
    1. Run `terminus site:upstream:set my-site-name empty` from your command line to remove the unneeded upstream after the site has been initialized.
    1. Add the Kalamuna Commit Bot (or whatever account you want to be committing to the pantheon repo) under the Team tab for the project.
1. Log into CircleCI and add your github repo as a project, and then:
    1. Under the project settings, find the place to add an ssh key (varies between old and new interface), and add the public key associated with your pantheon user or the one added above, using `drush.in` as the domain.
    1. Under the environment variables tab in the project settings, add the url for the destination repository in the `PANTHEON_REPO` variable.
1. Install Drupal locally:
    1. Clone the github repository and run `composer install` to install Drupal. (You may need to increase your memory limit or execute `php -d memory_limit=3G /path/to/composer install`.)
    1. Commit the `composer.lock` file, and files that have been initialized for customization, like `robots.txt`.

## What does the original drupal-composer/drupal-project template do?

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
* Added standard configuration for circleci build process and deployment to pantheon.
* Added a .gitignore-deploy file that replaces the .gitignore file when deploying from circle to pantheon.
* The robots.txt file is installed initially from drupal scaffold, but any subsequent changes are not overwritten.

## What features have been removed or changed from the original drupal-composer/drupal-project repository?
* Removed unneeded .travis.yml and phpunit.xml.dist files.
* Not using .gitignore files created by Drupal Scaffold.
