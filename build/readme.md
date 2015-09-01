# Build files

This directory contains configuration files for running common project tasks. 

These may be used for running tasks locally, or for running automated builds via 
continuous integration solutions. Additionally, the configuration file for 
Travis CI lives in the the project root at /.travis.yml. It is required by 
Travis CI that the file live there.

This directory should not contain any test files. Those exist in the 
/tests directory.

## Continuous Integration Build Process

1. `.travis.yml` is read and executed by Travis CI. The environment is built
  by installing composer dependencies, apache, mysql, etc. Notable files
  involved in this step are:
  * `.travis.yml`
  * `composer.json`
1. Two phing targets are executed in parallel, validate:all and pt:self-test.
   The pt:self-test target calls a number of other targets to simulated the 
   creation of a new project via Project Template, and to run all automated
   tests. Notable files include:
  * `install/example.*.yml` files used for default configuration
  * `build/phing/*` files

## Troubleshooting

### Phing

To manually test a phing target, run the following command in the docroot:
````
composer install
./bin/phing -f build/phing/build.xml target-name
````
