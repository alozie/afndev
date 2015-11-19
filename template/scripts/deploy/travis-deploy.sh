#!/usr/bin/env bash

# What does this script do?
# This script will watch for a specific Travis $build_job on a specific
# $source_branch on the canonical GitHub repository and deploy build artifacts
# to $dest_branch on the git.remote specified in project.yml.

# How to use this script?
# This script should be executed in the `after_success` section of .travis.yml.
# It requires three arguments. Example call:
# `scripts/deploy/travis-deploy.sh build:validate:test master master-build`

build_job=$1      # The build job to to watch.
source_branch=$2  # The branch to watch.
dest_branch=$3    # The branch to which the build artifact should be committed and deployed.

# Note that the canonical repository is watched. Commits to forked repositories
# will not trigger deployment. This is the purpose of the check that
# $TRAVIS_PULL_REQUEST is false.
if [ "${TRAVIS_PULL_REQUEST}" = "false" ] && [ "${TRAVIS_BRANCH}" = $source_branch ] && [ "${TEST_SUITE}" = $build_job ];
  then
    # Call the `deploy:build` Phing target, passing in required parameters.
    ../../task.sh deploy:build -Ddeploy.branch=$dest_branch -Ddeploy.buildID=$TRAVIS_BUILD_ID;
fi;
