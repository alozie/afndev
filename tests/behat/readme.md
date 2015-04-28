# Behat Testing

To configure Behat testing:

1. composer install
1. `cp behat.local.yml.example behat.local.yml`
1. Update values in behat.local.yml.
1. Execute behat tests from `tests` directory via `./bin/behat/behat behat/features --config=behat/behat.local.yml`
