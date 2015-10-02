# Build files

This directory contains configuration files for running common project tasks. 

These may be used for running tasks locally, or for running automated builds via 
continuous integration solutions.

This directory should not contain any test files. Those exist in the 
/tests directory.

## Build Tasks

A large number of common build tasks are provided via Phing targets. These 
include tasks for things like code sniffing, executing tests, building 
dependencies, installing Drupal, etc.

Before attempting to execute any tasks, verify that composer dependencies
are built by running `composer install` in the project root.

### Executing Tasks

* For a full list of the available Phing targets, run `./task.sh -list`
* To manually test a phing target, run the following command matching the
  the following pattern: `./task.sh <target-name>`. 
  For example `./task.sh validate:all`
* To run Phing directly from the binary, simply run `./bin/phing -f build/phing/build.xml <arguments>`

## Continuous Integration

Integration with Travis CI is included, although Phing tasks can be used with
 any CI tool. The default Travis CI build process is as follows:

1. Pull request or commit to GitHub triggers Travis CI.
1. `.travis.yml` is read and executed by Travis CI. The environment is built
  by installing composer dependencies. Notable files involved in this step are:
  * `.travis.yml`
  * `composer.json`
1. Travis CI begins to builds in parallel:
  * The Phing 'validate:all' target is executed
  * The Phing 'setup:build:all' and 'tests:all' targets are executed
