# Testing

This directory contains all projects tests, grouped by testing technology. For
all configuration related to builds that actually run these tests, please see
the [build](/build) directory.

    tests
    ├── behat   - contains all Behat tests
    │    ├── features
    │    │   ├── bootstrap
    │    │   └── Example.feature 
    │    ├── behat.yml - contains behat configuration common to all behat profiles.
    │    └── integration.yml - contains behat configuration for the integration profile, which is used to run tests on the integration environment.
    ├── jmeter  - contains all jMeter tests
    └── phpunit - contains all PHP Unit tests
