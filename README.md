# Composer template for drunomics Drupal projects

[![Build Status](https://www.travis-ci.org/drunomics/drupal-project.svg?branch=3.x)](https://www.travis-ci.org/drunomics/drupal-project)

Builds upon https://github.com/drupal-composer/drupal-project.

## Usage

First you need to [install composer](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-osx).

> Note: The instructions below refer to the [global composer installation](https://getcomposer.org/doc/00-intro.md#globally).
You might need to replace `composer` with `php composer.phar` (or similar) 
for your setup.

After that you can create the project:

```
// Last stable version:
composer create-project drunomics/drupal-project:3.* PROJECT
// Last development version:
composer create-project drunomics/drupal-project:3.*@dev --stability dev PROJECT
```

With `composer require ...` you can download new dependencies to your 
installation.

```
cd PROJECT
composer require drupal/devel:~1.0
```

The `composer create-project` command passes ownership of all files to the 
project that is created. You should create a new git repository, and commit 
all files not excluded by the .gitignore file.

## Documentation overview

The template builds upon [drupal-composer/drupal-project](https://github.com/drupal-composer/drupal-project).
Please follow the README of https://github.com/drupal-composer/drupal-project
for general documentation.

## Optional features

Some optional features can be enabled by setting some environment variables
when creating the project. To set a variable, you can set the variable when
invoking composer; e.g.:

    VARIABLE=1 composer create-project drunomics/drupal-project:3.* PROJECT

The following variables are supported:

Variable | Description | Example value |
--- | --- | --- |
| PROJECT_ADD_DRUNOMICS_DEVSETUP       | Whether the drunomics devsetup should be added. | "1" or "" |
