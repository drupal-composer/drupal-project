# Composer template for Drupal projects

This project template should provide a kickstart for managing your site 
dependencies with [Composer](https://getcomposer.org/).

If you want to now, how to use it as replacement for
[Drush Make](https://github.com/drush-ops/drush/blob/master/docs/make.md) visit
the [Documentation on drupal.org[(https://www.drupal.org/node/2471553).

## Quickstart

```
curl -sS https://getcomposer.org/installer | php
php composer.phar create-project drupal-composer/drupal-project:7.x-dev some-dir --stability dev --no-interaction
cd some-dir
php ../composer.phar require drupal/ctools:7.*
```

## Generate

With using [the "Composer Generate" drush extension](https://www.drupal.org/project/composer_generate)
you can now generate a basic `composer.json` file form an existing project.


## FAQ

### Should I commit the contrib modules I download

Composer recommends **no**. They provide [argumentation against but also workrounds if a project decides to do it anyway](https://getcomposer.org/doc/faqs/should-i-commit-the-dependencies-in-my-vendor-directory.md).

