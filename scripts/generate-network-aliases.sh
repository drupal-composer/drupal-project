#!/bin/bash -e

######
# Extends the main docker-compose file with network aliases for localdev inner-container hostname resolution.
# This allows containers in the 'traefik' network to connect to this app under the given hostnames.
######
source `dirname $0`/util/os-compat-helpers.sh
TEMPLATE_FILE=`os_compat_readlink -f $(dirname $0)`/generate-network-aliases.template.yml
cd `dirname $0`/..
source dotenv/loader.sh

# Only generate this for the localdev environment.
if [[ ! $PHAPP_ENV = "localdev" ]]; then
  rm -f docker-compose.overrides.aliases.yml
  exit 0
fi

PROJECT=$(basename $PWD)
HOSTS=''
for SITE in $APP_SITES; do
  # Re-run dotenv loader to determine SITE_HOST, without variant.
  SITE_VARIANT=''
  source dotenv/loader.sh
  HOSTS+="$SITE_HOST "
  for SITE_VARIANT in $APP_SITE_VARIANTS; do
    # Re-run dotenv loader to determine SITE_HOST.
    source dotenv/loader.sh
    HOSTS+="$SITE_HOST "
  done
done

## Generate the new file.
cat $TEMPLATE_FILE > docker-compose.overrides.aliases.yml

for HOST in $HOSTS; do
  echo "          - $HOST" >> docker-compose.overrides.aliases.yml
done

## Register the compose file.
os_compat_sed_i '/^COMPOSE_FILE/ s/ *$/:docker-compose.overrides.aliases.yml/' .env
echo "docker-compose.overrides.aliases.yml generated."
