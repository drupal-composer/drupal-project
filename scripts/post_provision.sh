#!/bin/sh

BIN_DIR=/usr/local/bin

if [ ! -L ${BIN_DIR}/phantomjs ] ; then
   ln -s /home/vagrant/.npm-global/bin/phantomjs ${BIN_DIR}
fi
