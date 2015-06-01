#! /bin/bash -e

# Rebuilds the patches applied to the repo. Won't build core.

GIT_ROOT=$(git rev-parse --show-toplevel)
cd $GIT_ROOT/sites/all

drush make $GIT_ROOT/scripts/contrib.make -y --no-core --no-gitinfofile --contrib-destination=. --concurrency=8
