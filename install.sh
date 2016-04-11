#!/bin/bash
git clone https://github.com/poetic/drupal-project.git $1

cd $1
composer install

