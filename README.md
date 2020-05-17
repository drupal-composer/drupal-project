# Kalamuna composer template for Drupal projects with CircleCI and Pantheon integration

This template is based on the [drupal-composer/drupal-project](https://github.com/drupal-composer/drupal-project) template, with additional tools and settings specific to the Kalamuna workflow.

The goal of this repository is to provide a clean installation with just the tools and files that we need for 95% of our Drupal projects. Alternate configurations, with lesser-used packages or frameworks, should be included as sperate packages or moved to branches which can be used when needed for particular projects.

## Usage

### Create a new Github repo

Press the `Use this template` button in Github to create a new repository for your project based on this template, or clone this repository manually and remove any unneeded git history.

### Create Pantheon environment

1. Created a new pantheon site at [https://dashboard.pantheon.io/sites/create](https://dashboard.pantheon.io/sites/create), selecting the appropriate Organization. *Note: It may make more sense from a process perspective to have the client create the pantheon site and then add Kalamuna as a supporting organization.*
1. When prompted to select an upstream, choose the regular Drupal 8 option, so the hidden framework variable on Pantheon is properly set to Drupal.
1. Run `terminus site:upstream:set my-site-name empty` from your command line to remove the unneeded upstream after the site has been initialized.
1. Add the Kalamuna Commit Bot `kalacommitbot@kalamuna.com` under the `Team` tab for the project (or an alternate account you'd like to use for pushing to Pantheon).
1. Copy the location of the Pantheon git repo from the `Git SSH clone URL` field under the `Connection info` dropdown in the Pantheon site dashboard, which is the format `ssh://codeserver.dev.XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX@codeserver.dev.XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX.drush.in:2222/~/repository.git`. Discard the `git clone` and `site-name` parts from before and after the URL in the provided command.

### Initialize CircleCI integration

1. Log into CircleCI and add your github repo as an active project.
1. Under the `environment variables` tab in the project settings, create a new variable with `PANTHEON_REPO` as the key, and the `ssh://codeserver.dev.XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX@codeserver.dev.XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX.drush.in:2222/~/repository.git` repo url as the value.
1. Find the place to add ssh keys to the circle project, which is at `Project Settings` >  `SSH Keys` > `Additional SSH Keys` in the new circleci interface, and at `Project Settings` > `SSH Permissions` in the old interface.
1. And add a new private key with `drush.in` as the hostname and the private key text for `kalacommitbot@kalamuna.com`, or whichever user you added to the pantheon project for pushing commits. The private key should start with `-----BEGIN RSA PRIVATE KEY-----` and end with `-----END RSA PRIVATE KEY-----`. (Kalamuna devs should look in Lastpass for this info.)

### Install the codebase and deploy

1. Clone the github repository locally and run `composer install` to install Drupal. (You may need to increase your memory limit or execute `php -d memory_limit=3G /path/to/composer install`.)
1. Commit the `composer.lock` file, and files that have been initialized for customization, like `robots.txt` and `settings.php`.
1. Run `npm it` to install the node modules, and commit the `package.lock` file to the repository.
1. Push the changes to github, and check that the CircleCI workflow executes properly and the code is pushed to pantheon.

### Configure Drupal
1. Install Drupal in the Pantheon dev environment. (Note: If you want to run the Drupal installation process locally, you may need to re-enable some layers of caching in the `/web/sites/default/settings.local.php` file.)
1. Enable the included contrib modules, including `admin_toolbar_tools`, `metatag`, `pantheon_advanced_page_cache`, and `pathauto`.
1. Copy the database to your local environment, and run `drush cex` to export the configuration to the `config/sync` directory, and commit to git.

### Set up local development environment
1. If using lando, edit the `.lando.yml` file to set the appropriate `PROJECTNAME`, `PANTHEON_SITE_ID`, and `PANTHEON_SITE_MACHINE_NAME`. Database credentials are set automagically by the pantheon lando recipie.

    **or**

1. Create an `.env` file from `.env.example`, and set the appropriate database credentials and drush site url.

## What does the original drupal-composer/drupal-project template do?

When installing the given `composer.json` some tasks are taken care of:

* Drupal will be installed in the `web`-directory.
* Autoloader is implemented to use the generated composer autoloader in `vendor/autoload.php`,
  instead of the one provided by Drupal (`web/vendor/autoload.php`).
* Modules (packages of type `drupal-module`) will be placed in `web/modules/contrib/`
* Theme (packages of type `drupal-theme`) will be placed in `web/themes/contrib/`
* Profiles (packages of type `drupal-profile`) will be placed in `web/profiles/contrib/`
* Creates default writable versions of `settings.php`.
* Creates `web/sites/default/files`-directory.
* Creates environment variables based on your .env file. See [.env.example](.env.example).

## What Kalamuna-specific features have been added?
* Added standard configuration for circleci build process and deployment to pantheon.
* Added a `.gitignore-deploy` file that replaces the `.gitignore` file when deploying from circle to pantheon.
* Required the `pantheon-systems/drupal-integrations` package which contains additional scaffolding for pantheon sites.
* The `robots.txt` file is installed initially from drupal scaffold, but any subsequent changes are not overwritten.
* Provide default `development.services.yml` and `settings.local.php` files which will be created in web/sites if they don't already exist.
* Add local settings to keep kint from loading too many objects and crashing drupal.
* Require modules used on all sites, including `admin_toolbar`, `metatag`, `pantheon_advanced_page_cache`, and `pathauto`.
* Provide an install profile to enable the needed modules automatically.
* Provide a package.json file to install npm module.
* Add configuration for the sync directory to be located at `../config/sync`.

## What features have been removed or changed from the original drupal-composer/drupal-project repository?
* Removed unneeded .travis.yml and phpunit.xml.dist files.
* Not using .gitignore files created by Drupal Scaffold.
* Not requiring drush or DrupalConsole, since they are installed globally in Lando and on Pantheon.
* Remove `services.yml` from the list of things that drupal-project adds, since doing that is no longer advised.

## Potential improvements
* Build out the `package.json` file with the configuration for compiling themes with Gulp.
* Have `composer install` call `npm install` automatically.
* Require additional contrib modules we use on most sites.
* Don't hardcode the Kalamuna Commit Bot user in the cricleci config.
* Determine standard process for using the `config_split` module.
* Include common configuration, either as part of the install profile or using other import methods.
