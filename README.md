[![CircleCI](https://circleci.com/gh/thinkshout/drupal-project/tree/8.x-pantheon.svg?style=svg)](https://circleci.com/gh/thinkshout/drupal-project/tree/8.x-pantheon)

# Drupal Project (Pantheon edition)

**Note**: This project was forked from the [drupal-project](https://github.com/drupal-composer/drupal-project) repository.

## Development set-up

This is a Drupal 8 site built using the [robo taskrunner](http://robo.li/). As such, it does not require separate `/project` and `~/Sites` folders. Install the repo directly into `~/Sites` using `git clone` and you will be ready to begin.  

First you need to [install composer](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-osx).

`brew install composer`

Next add `./vendor/bin` to your PATH, at the beginning of your PATH variable, if it is not already there.

Check with:
`echo $PATH`

Update with:
`export PATH=./vendor/bin:$PATH`

You can also make this change permanent by editing your `~/.zshrc` file:
`export PATH="./vendor/bin:...`

Add the custom git repo to your list of available composer paths by opening (or creating) `~/.composer/config.json`
and adding these lines:

```
{
    "config": {
    },
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/thinkshout/drupal-project"
        }
    ]
}
```

### Initial build (new repo)

Start inside the ~/Sites directory and build your site (replace 'new-project-name' with the name of the project folder):

```
composer create-project thinkshout/drupal-project:8.x-pantheon-dev new-project-name --stability dev --no-interaction
```

Enter the new site folder:

```
cd new-project-name
```

Initialize a repository on github https://github.com/new that matches your project name and connect your local directory to it:

```
git init
git add .
git commit -m "Initial commit."
git remote add origin git@github.com:thinkshout/new-project-name.git
git push -u origin master
```

To initialize the project name in several of the files run:

```
composer install
robo init
```

### Initial build (existing repo)

From within your `~/Sites` directory run:

```
git clone git@github.com:thinkshout/new-project-name.git
cd new-project-name
composer install
```

### Building

Running the `robo configure` command will read the .env.dist, cli arguments and
your local environment (`DEFAULT_PRESSFLOW_SETTINGS`) to generate a .env file. This file will be used to set
the database and other standard configuration options. If no database name is provided, the project name and the git branch name will be used. If no database name is provided, the project name and the git branch name will be used. Note the argument to pass to robo configure can include: --db-pass; --db-user; --db-name; --db-host.

```
robo configure
# Use an alternate DB password
robo configure --db-pass=<YOUR LOCAL DATABASE PASSWORD>
# Use an alternate DB name
robo configure --db-name=<YOUR DATABASE NAME>
```

The structure of `DEFAULT_PRESSFLOW_SETTINGS` if you want to set it locally is:

```
DEFAULT_PRESSFLOW_SETTINGS_={"databases":{"default":{"default":{"driver":"mysql","prefix":"","database":"","username":"root","password":"root","host":"localhost","port":3306}}},"conf":{"pressflow_smart_start":true,"pantheon_binding":null,"pantheon_site_uuid":null,"pantheon_environment":"local","pantheon_tier":"local","pantheon_index_host":"localhost","pantheon_index_port":8983,"redis_client_host":"localhost","redis_client_port":6379,"redis_client_password":"","file_public_path":"sites\/default\/files","file_private_path":"sites\/default\/files\/private","file_directory_path":"site\/default\/files","file_temporary_path":"\/tmp","file_directory_temp":"\/tmp","css_gzip_compression":false,"js_gzip_compression":false,"page_compression":false},"hash_salt":"","config_directory_name":"sites\/default\/config","drupal_hash_salt":""}
```

### Installing

Running the robo install command will run composer install to add all required
dependencies and then install the site and import the exported configuration.

```
robo install
```

### Testing

Test are run automatically on CircleCI, but can be run locally as well with:

```
robo test
```

## Updating contributed code

### Updating contrib modules

With `composer require drupal/{module_name}` you can download new dependencies to your
installation.

```
composer require drupal/devel:8.*
```

### Applying patches to contrib modules

If you need to apply patches (depending on the project being modified, a pull
request is often a better solution), you can do so with the
[composer-patches](https://github.com/cweagans/composer-patches) plugin.

To add a patch to drupal module "foobar" insert the patches section in the `extra`
section of composer.json:
```json
"extra": {
    "patches": {
        "drupal/foobar": {
            "Patch description": "URL to patch"
        }
    }
}
```

### Updating Drupal Core

This project will attempt to keep all of your Drupal Core files up-to-date; the
project [drupal-composer/drupal-scaffold](https://github.com/drupal-composer/drupal-scaffold)
is used to ensure that your scaffold files are updated every time drupal/core is
updated. If you customize any of the "scaffolding" files (commonly .htaccess),
you may need to merge conflicts if any of your modfied files are updated in a
new release of Drupal core.

Follow the steps below to update your core files.

1. Run `composer update drupal/core --with-dependencies` to update Drupal Core and its dependencies.
1. Run `git diff` to determine if any of the scaffolding files have changed.
   Review the files for any changes and restore any customizations to
  `.htaccess` or `robots.txt`.
1. Commit everything all together in a single commit, so `web` will remain in
   sync with the `core` when checking out branches or running `git bisect`.
1. In the event that there are non-trivial conflicts in step 2, you may wish
   to perform these steps on a branch, and use `git merge` to combine the
   updated core files with your customized files. This facilitates the use
   of a [three-way merge tool such as kdiff3](http://www.gitshah.com/2010/12/how-to-setup-kdiff-as-diff-tool-for-git.html). This setup is not necessary if your changes are simple;
   keeping all of your modifications at the beginning or end of the file is a
   good strategy to keep merges easy.
