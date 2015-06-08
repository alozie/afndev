# Build files

This directory contains configuration files required for running automated
builds via continuous integration solutions.

Additionally, the configuration file for Travis CI lives in the the project
root at /.travis.yml. It is required by Travis CI that the file live there.

This directory should not contain any test files. Those exist in the 
/tests directory.

## Build Process

1. `.travis.yml` is read and executed by Travis CI. The environment is built
  by installing composer dependencies, apache, mysql, etc. Notable files
  involved in this step are:
  * `.travis.yml`
  * `composer.json`
  * `install/composer.json`
  * `build/travis/travis.config.yml`, which is copied to `config.yml` by Phing
1. The phing `build` target is executed, causing Project Template to run its
  installer and create a new project in `tmp/ps_project`. Drupal is installed
  to the MySQL DB. Notable files involved include:
  * `config.yml` created from the previous step
  * `build/phing/build.xml`
1. The phing `run-tests` target is executed, which runs various validations 
  tools to verify that the code meets PHP and Drupal coding standards. Notable 
  files involved include:
  * `tests/behat/example.local.yml` which is copied to `tmp/ps_project/tests/behat/local.yml` by the installer
  * All tests located in `tests`
  * `build/phing/build.xml`
