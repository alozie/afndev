#! /bin/bash -e

GIT_ROOT=$(git rev-parse --show-toplevel)
cd $GIT_ROOT

echo "Rebuilding docroot..."
rm -r docroot
drush make $GIT_ROOT/scripts/project.make.yml docroot -y --no-gitinfofile --concurrency=8 --projects=drupal
cp docroot/sites/default/default.settings.php sites/default

echo "Building contrib projects..."
rm -r docroot/sites
cd docroot
ln -s ../sites
cd sites/all
drush make $GIT_ROOT/scripts/project.make.yml -y --no-core --no-gitinfofile --contrib-destination=. --concurrency=8

echo "Complete"
