# Testing

This directory contains all projects tests, grouped by testing technology. For
all configuration related to builds that actually run these tests, please see
the [build](/build) directory.

    tests
    ├── behat - contains all Behat tests
    │    ├── features
    │    │   ├── bootstrap
    │    │   └── Example.feature
    │    ├── behat.yml - contains behat configuration common to all behat profiles.
    │    └── integration.yml - contains behat configuration for the integration profile, which is used to run tests on the integration environment.
    ├── jmeter  - contains all jMeter tests
    └── phpunit - contains all PHP Unit tests

# Executing tests

Before attempting to execute any tests, verify that composer dependencies
are built by running `composer install` in the project root.

Each testing type can be either executed directly, or via a corresponding Phing
target. Phing will execute the tests with default values defined in your
project's yaml configuration files (project.yml). Examples:

* `./task.sh tests:all`
* `./task.sh tests:behat`
* `./task.sh tests:phpunit`

To execute the tests directly (without Phing) see the following examples:

* `./vendor/bin/behat -c tests/behat/local.yml tests/behat/features/Examples.feature`
* `./vendor/bin/phpunit tests/phpunit/BoltTest.php`

For more information on the commands, run:

* `./vendor/bin/phpunit --help`
* `./vendor/bin/behat --help`

## Behat

Behat test standards:

* Behat tests must be used behaviorally. I.E., they must use business domain 
  language. See the following articles:
    * [Cucumber - Where to start?](https://github.com/cucumber/cucumber/wiki/Cucumber-Backgrounder#where-to-start) 
    Note that Cucumber is simply a Ruby based BDD library, whereas Behat is a 
    PHP based BDD library. Best practices for tests writing apply to both
    * [The training wheels came off](http://aslakhellesoy.com/post/11055981222/the-training-wheels-came-off)
* Each test should be isolated. E.g., it should not depend on conditions created
  by another test. In pratice, this means:
    * Resetting testing environment via CI after test suite runs
    * Defining explicit cleanup tasks in features
* @ todo add examples of good and bad features


## PHPUnit

Project level, functional PHPUnit tests are included in `tests/phpunit`. Any
PHPUnit tests that affect specific modules or application level features
should be placed in the same directory as that module, not in this directory.

PHPUnit test standards:

* Tests should not contain any control statements
* Be careful to make both positive and negative assertions of expectations
* @todo add examples of good and bad tests
