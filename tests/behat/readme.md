# Behat Testing
The following is a guide on how to bootstrap Behat testing.

## Local system

1. Execute `composer install` in project root. 
1. Execute behat tests from the root directory via `./vendor/bin/behat tests/behat/features --config=tests/behat/local.yml -p local`


## Using the VM

1. Go into the `box` directory
1. Execute `vagrant ssh` to SSH into the VM
1. Execute `sudo su` to switch to the root user
1. Execute `export PATH=/root/.composer/vendor/bin:$PATH` to load composer bins
1. Execute behat tests by running `behat /tests/behat/features --config=/tests/behat/vm.yml -p vm`