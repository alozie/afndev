# Behat Testing

To configure Behat testing:

1. run `composer install` in project root. 
1. `cp tests/behat/local.yml.example tests/behat/local.yml`
1. Update values in behat.local.yml.
1. Execute behat tests from the root directory via `./vendor/bin/behat/behat tests/behat/features --config=tests/behat/local.yml`
