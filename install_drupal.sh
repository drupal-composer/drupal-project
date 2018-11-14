#!/usr/bin/env bash
set -e

BIN_DIR=vendor/bin
TEST_VAR=install_drupal_backup_test
TEST_VAR_VALUE=TestValue
BACKUP_FILE=$(pwd -P)'/db/restore.sql'
BACKUP_FILE_GZ=${BACKUP_FILE}'.gz'
NC='\033[0m'
RED='\033[0;31m'
GREEN='\033[0;32m'
RED='\033[0;31m'
RED='\033[0;31m'
BROWN='\033[0;33m'
CYAN='\033[0;36m'

function drupal { php "${BIN_DIR}"/drupal "$@"; }
function drush { php "${BIN_DIR}"/drush "$@"; }
function error_exit {
    echo -e "${RED}\n$@\n${NC}" >&2
    exit 1
}
function success {
    echo -e "${GREEN}\n$@${NC}"
}
function assert {
    echo -e "${BROWN}\n$@${NC}"
}
function varvalue {
    echo -e "${CYAN}\n$@${NC}"
}
function get_var { drush state-get "${TEST_VAR}"; }
function set_var { drupal state:override "${TEST_VAR}" "$@"; }
function del_var { drupal state:delete "${TEST_VAR}" "$@"; }
function test_var {
    var=`get_var`
    if [[ ${var} != "$@" ]]; then
        varvalue "Test variable's value is '${var}' not '$@'"
        return 1
    else
        varvalue "Test variable's value is '$@'"
        return 0
    fi

}

drupal site:install --force --no-interaction
assert 'On new install, our test variable should not exist.'
test_var ${TEST_VAR_VALUE} && error_exit 'Failure' || success 'Success'

set_var ${TEST_VAR_VALUE}
assert "After setting the var to '${TEST_VAR_VALUE}', that should be the value if retrieved."
test_var ${TEST_VAR_VALUE} && success 'Success' || error_exit 'Failure'

drupal database:dump --file=${BACKUP_FILE} --gz
if [[ ! -f ${BACKUP_FILE_GZ} ]]; then
    error_exit "Backup not found: ${BACKUP_FILE_GZ}."
else
    success "Backup was found: ${BACKUP_FILE_GZ}."
fi

echo 'Reinstalling site to clear out our variable.'
drupal site:install --force --no-interaction
assert "After reinstall, we should not get '${TEST_VAR_VALUE}' as the value."
test_var ${TEST_VAR_VALUE} && error_exit 'Failure' || success 'Success'

echo "Restoring the database using the backup."
drupal database:restore --file=${BACKUP_FILE_GZ}
assert "After restoring the backup, the var should be '${TEST_VAR_VALUE}' again."
test_var ${TEST_VAR_VALUE} && success 'Success' || error_exit 'Failure'
