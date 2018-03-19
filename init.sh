#!/usr/bin/env bash

if fin ps | grep -vq "Up"
then
    echo "Skipping fin init, project is already installed"
else
    fin init
fi