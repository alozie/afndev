#!/bin/sh
#
# db-copy Cloud hook: db-scrub
#
# Scrub important information from a Drupal database.
#
# Usage: db-scrub.sh site target-env db-name source-env

site="$1"
target_env="$2"
db_name="$3"
source_env="$4"
SCRIPTS="/var/www/html/$site.$target_env/hooks/scripts"

# Check that this is not production
if [ "$target_env" != "prod" ]; then
  # Run scrub sql commands
  echo "$site.$target_env: Scrubbing database $db_name"
  cat $SCRIPTS/db-scrub.sql | drush @$site.$target_env ah-sql-cli --db=$db_name
fi
