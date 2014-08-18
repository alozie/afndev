#!/bin/sh
#
# Cloud Hook: drush-cache-clear
#
# Run drush cache-clear all in the target environment. This script works as
# any Cloud hook.


# Map the script inputs to convenient names.
site=$1
target_env=$2

# Clear cache
echo "$site.$target_env: Clearing all drupal caches"
AH_SITE_ENVIRONMENT=$target_env drush @$site.$target_env cc all
