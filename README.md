# Template for composer-based Drupal 8 projects with Drupal VM

This template is the fork of [drupal-composer](https://github.com/drupal-composer/drupal-project) and also contains 
[Drupal VM](https://github.com/geerlingguy/drupal-vm) as a git submodule. 

For further information about these projects check the project pages.

## Requirements

* Install (latest stable) [Virtualbox](https://www.virtualbox.org/wiki/Downloads) on your host machine.
* Install [Vagrant](https://www.vagrantup.com/downloads.html) on your host machine with some of its plugins:
  * `vagrant plugin install vagrant-hostsupdater`
  * `vagrant plugin install vagrant-auto_network`
  * `vagrant plugin install vagrant-vbguest`
  * `vagrant plugin install vagrant-cachier`
  
## Good to know

* Composer installs the following files (these should NOT be committed):
  * Drupal core (latest stable drupal-8.x.x) in `docroot/` folder
  * Drupal libraries and contrib modules/profiles/themes in `docroot/` folder
  * PHP libs in the `vendor/` folder
  * PHP libs' binaries in the `bin/` folder
* As the gitroot will be NFS-mounted into the VM, any changes made to the files in it here will be available there 
  as well.
* You can use XDebug.
* You can use `vagrant ssh` to SSH into a running Vagrant machine.
  
## Working with an already existing project

1. Clone the project `git clone some-project --recursive` (the `--recursive` switch is needed to have Drupal VM cloned as well).
2. Start the VM: `vagrant up`
3. For adding custom/contrib modules or patches, follow the below instructions.
  
## Creating a new project

1. Clone this repository: `git clone https://github.com/Pronovix/drupal-project.git . --recursive`
2. Update the `vagrant_hostname` and `vagrant_machine_name` in `/config/config.yml` according to your project 
(these two must be unique on your host)
3. Start the VM: `vagrant up`
  * It takes quite a bit of time to do it for the first time on your host, as it has to download the base image which is 
  about 700MB (or more). Later on, provisioning a (new) VM is usually just a matter of 5-15 minutes, depending on 
  the config of it (amount of packages to install, the number of Drupal modules, composer vs bandwidth, etc.).
4. For adding custom/contrib modules or patches, follow the below instructions.
 
### Notes

* The `.htaccess` and `robots.txt` files are ignored by `drupal-scaffold` by default (so they get updated instead of 
getting overridden) and they are committed to the repository to make sure they exist in the file system. 
* The scaffold files (except for `.htaccess` and `robots.txt`) and the other files that shouldn't be committed in a Drupal 
project (e.g. settings.php) are in the `.gitignore` file by default.

## Adding a new contrib module

1. SSH into the machine: `vagrant ssh`
2. In the VM, run `composer require drupal/some_module --no-interaction --prefer-dist -o`
  * Adds a `some_module` module to `docroot/modules/contrib/` (per pronovix/drupal-project's `composer.json`)
  * Adds the contrib modules that are dependencies of this module to the same folder (per `some_module.info.yml`)
  * Adds the PHP libraries that are dependencies of this module to `vendor/` (per the module's own `composer.json`)
  * The autoloader will be optimized (because of `-o`)
3. Add Composer files to git: `git add composer.json composer.lock`
  * Do NOT add the code itself as it should be built by composer automatically
  * The Pronovix/drupal-project MUST NOT have composer.lock, as it's a skeleton for other/real projects
  * The other/real projects MUST have composer.lock committed, as it's the only way to ensure the very same codebase is 
  built by composer in whatever environments
4. Commit these files: `git commit -m "Commit message."`.

## Adding a contrib submodule (don't do it)

So you don't know the machine name of the project on drupal.org that provides a certain (sub)module you want to have? 
Not a problem, drupal.org's composer repository can resolve it for you:
1. In the VM: `composer require drupal/admin_toolbar_tools --no-interaction --prefer-dist -o`
  * However, this approach is not recommended, as the submodule is the one that gets added to composer.json instead of 
  the drupal.org project, which might be confusing. This above command will display something like "Installing 
  drupal/admin_toolbar (1.19.0)", which is the machine name of the drupal.org project – let's stick to that.
2. `git reset --hard`
3. `composer require drupal/admin_toolbar --no-interaction --prefer-dist -o`
  * Well, that module is already part of the base package, but anyway :)
4. `git add composer.json composer.lock`
5. `git commit -m "New contrib: admin_toolbar.module."`

## Adding a new custom module

1. Copy it into the `docroot/modules/custom/` folder.
2. Make sure its composer.json has the appropriate type info: `"type": "drupal-custom-module"`
3. Add the module to git: `git add modules/custom/custom_module` (custom modules SHOULD be committed to the repo)
4. Commit the module: `git commit -m "New custom module: custom_module."`

## Applying patches to core or any contrib module

1. Add a `patches` section into the `extra` section of your composer.json (if it hasn't been added yet) for each project 
you want to patch; each Drupal project should have a separate section and each patch should have a separate line 
(within the project section). So the end of your composer.json looks something like this:
```
"extra": {
    "installer-paths": {
        "docroot/core": ["type:drupal-core"],
        "docroot/libraries/{$name}": ["type:drupal-library"],
        "docroot/modules/contrib/{$name}": ["type:drupal-module"],
        "docroot/profiles/contrib/{$name}": ["type:drupal-profile"],
        "docroot/themes/contrib/{$name}": ["type:drupal-theme"],
        "drush/contrib/{$name}": ["type:drupal-drush"]
    },
    "patches": {
        "drupal/core": {
            "Use ROW_FORMAT=dynamic with InnoDB [#2857359]": "https://www.drupal.org/files/issues/row_format_dynamic_innodb_0.patch"
        },
        "drupal/node_clone": {
            "Configurations cannot be changed and publishing options missing [#2724919]": "https://www.drupal.org/files/issues/fix_set_data_on_admin_page-2724919-2.patch",
            "Warning: Invalid argument supplied for foreach() [#2712079]": "https://www.drupal.org/files/issues/update_settings_and_schema-2712079-3.patch"
        }
    }
}
```
2. Run `composer install` in the VM to apply the patch.

## Writing tests

Put your tests into the features directory. Then run `vagrant ssh` and `cd .. && bin/behat`. If you need a specially prepared 
database to run tests, copy the dump to `docroot/mock.sql`.

## Acknowledgements

This is basically Proudly Found Elsewhere:
* [drupal-composer/drupal-project](https://github.com/drupal-composer/drupal-project) (our own pronovix/drupal-project is a fork of it)
* [Drupal VM](https://github.com/geerlingguy/drupal-vm) (it's added to our own repo as a git submodule)
* [Composer howto](https://www.drupal.org/docs/develop/using-composer) on drupal.org
* etc.
