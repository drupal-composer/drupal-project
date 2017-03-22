#!/bin/bash

case "$1" in
    PHP_CodeSniffer)
        cd $TRAVIS_BUILD_DIR
        ./vendor/bin/phpcs
        exit $?
        ;;
    PHPUnit)
        cd $TRAVIS_BUILD_DIR/web
        ../vendor/bin/phpunit
        exit $?
        ;;
    Behat)
        cd $TRAVIS_BUILD_DIR/tests
        ./behat
        exit $?
        ;;
    *)
        echo "Unknown test '$1'"
        exit 1
esac
