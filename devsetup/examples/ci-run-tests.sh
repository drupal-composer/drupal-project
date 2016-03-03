#!/bin/bash
cd ../behat/$1
source container-vars.sh

# Start within a CURL request to localhost to give the box some time to
# boot up and init caches.
ssh $deploy_name -C "curl http://localhost; mkdir -p ~/behat/$build_number; $apache_document_root/tests/behat/bin/behat -c $apache_document_root/tests/behat/behat.yml \
--format junit,pretty --out ~/behat/$build_number, -p ci" 
rsync -az $deploy_name:behat/$build_number/ .

ssh $deploy_name  "cd $apache_document_root; drush cache-clear drush; drush fra -y; drush vdel drupal_test_email_collector -y"
