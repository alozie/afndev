#!/bin/bash -e

DIR=$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )
GIT_ROOT=$(git rev-parse --show-toplevel)
DRUSH=$GIT_ROOT/vendor/bin/drush
DRUPAL_TEMP=$(mktemp -d)
rm -rf $DRUPAL_TEMP

$DRUSH make make.yml $DRUPAL_TEMP --quiet -y --projects=drupal --no-gitinfofile --concurrency=10
rsync -a --no-g --no-p --delete --exclude-from=$DIR/rsync-exclude.txt --include=default.settings.php $DRUPAL_TEMP/* $GIT_ROOT/docroot

rm -rf $DRUPAL_TEMP
