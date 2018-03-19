#!/usr/bin/env bash

if [ ! -f docroot/sites/default/settings.php ];
then
    fin init
else
    echo "Skipping fin init since the project may has been setted up already."
fi