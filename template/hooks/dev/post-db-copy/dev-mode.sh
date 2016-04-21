#!/bin/sh
#
# Cloud Hook: post-db-copy
#
# The post-db-copy hook is run whenever you use the Workflow page to copy a
# database from one environment to another. See ../README.md for
# details.
#
# Usage: post-db-copy site target-env db-name source-env

site="$1"
target_env="$2"
db_name="$3"
source_env="$4"

echo "$site.$target_env: Received copy of database $db_name from $source_env."

# Enable UI modules.
echo "Enabling UI modules."
drush @$site.$target_env en field_ui views_ui -y

# Disable unnecessary modules.
drush @$site.$target_env dis paranoia -y

# Enable database logging.
echo "Enabling development modules."
drush @$site.$target_env en devel -y

# Clear all caches
drush @$site.$target_env cc all

# Disable cron.
drush @$site.$target_env cron 0
