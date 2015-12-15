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
DIR=$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )


# Note that the canonical repository is watched. Commits to forked repositories
# will not trigger deployment unless DEPLOY_PR is true.
if [[ "${TRAVIS_PULL_REQUEST}" = "false" ]] || [[ "${DEPLOY_PR}" = "true" ]];
  then
    echo "Deployments will be triggered only for the \"${build_job}\" test suite on the \"${source_branch}\" branch."
    echo "Checking to see if this branch and build job should trigger deployment..."

    # Trigger deployment only if $build_job and $source_branch parameters match
    # current build variables.
    if [[ "${TRAVIS_BRANCH}" = $source_branch ]] && [[ "${TEST_SUITE}" = $build_job ]]
      then
        echo "Build artifact will be deployed."
        commit_msg="Automated commit by Travis CI for Build #${TRAVIS_BUILD_ID}";
        # Call the `deploy` Phing target, passing in required parameters.
        ${DIR}/../../task.sh deploy:artifact -Ddeploy.branch="${dest_branch}" -Ddeploy.commitMsg="${commit_msg}";
      else
        echo "Build artifact will NOT be deployed."
    fi
  else
    echo "Build artifacts are not deployed for Pull Requests."
fi
