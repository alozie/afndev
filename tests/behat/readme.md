# Behat Testing

To configure Behat testing:

1. run `composer install` in project root. 
1. `cp tests/behat/behat.local.yml.example test/behat/behat.local.yml`
1. Update values in behat.local.yml.
1. Execute behat tests from `tests` directory via `./bin/behat/behat behat/features --config=behat/behat.local.yml`
