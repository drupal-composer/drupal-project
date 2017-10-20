#!/usr/bin/env bash

RTD_DIR=web/themes/rtdoked

mkdir $RTD_DIR
cp -R web/themes/contrib/bootstrap/starterkits/cdn  $RTD_DIR
cd $RTD_DIR
rename starterkit info THEMENAME.starterkit.yml 
find . -type f -name 'THEMENAME.*' -exec rename THEMENAME rtdoked '{}' \;
find . -type f -name '*.yml' -exec sed -i 's/THEMETITLE/ASU Theme/' {} \;
find . -name "*.yml" -exec sed -i  s/THEMENAME/rtdoked/g {} \;
