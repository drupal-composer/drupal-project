#!/bin/bash

echo "Starting a new shell at $SHELL with $(pwd)/vendor/bin added to it's path."
echo "Exit the shell to remove the path directory."
PATH=$(pwd)/vendor/bin:$PATH $SHELL
