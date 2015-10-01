## Overview

Bolt has an installer which will do the following:

* Create new project directory. It will be a sibling of the project template repository.
* (optionally) Adds a Drupal VM for a local development environment
* Adds testing frameworks
* Adds project documentation
* Includes custom settings in sites/all/settings
* Removes installation artifacts

## Installer Requirements

* Composer [Install](https://getcomposer.org/doc/00-intro.md#globally)
* PHP 5.3.9+ (PHP 5.5 recommended)
  * [Homebrew Install](https://lastzero.net/2013/08/howto-install-php-5-5-and-phpunit-on-os-x-via-homebrew/)

### Virtual Machine Requirements

If you'd like to use the included Ansible Drupal VM, you will need to install
the following dependencies:

* VirtualBox 4.3.x [Download](https://www.virtualbox.org/wiki/Downloads)
  * Drupal VM also works with Parallels or VMware if you have the [Vagrant VMware integration plugin](http://www.vagrantup.com/vmware))
* Vagrant 1.7.2 or higher [Download](http://www.vagrantup.com/downloads.html)
* Vagrant Host Updater
  * Instructions: `vagrant plugin install vagrant-hostsupdater`
* Ansible 1.9.2 or higher [Install](http://docs.ansible.com/intro_installation.html).
  * Mac / Linux only
  * OSX instructions from Homebrew: `brew install ansible`
  * Linux instructions:
    * `sudo easy_install pip`
    * `sudo CFLAGS=-Qunused-arguments CPPFLAGS=-Qunused-arguments pip install ansible`

## Create new project

To create a new repository using Bolt's installer, run the
follow from this repository's root directory:

### Create a new project, build all dependencies.

  1. `composer install`
  1. Run `./task.sh pt:configure` to create your
     project-specific configuration file. After running, `project.yml`, `make.yml`,
     and `local.yml` should exist in the Bolt root directory.
  1. Modify aforementioned .yml files with values for your new project.
  1. Run `./task.sh pt:create`
     This will create a new directory for your new project.
  1. Change directories to your new project directory. E.g., `cd /path/to/my/new/project`.
  1. In your new project directory, run `./task.sh setup:build:all`.
     This will install git hooks, build dependencies in your make file, and setup behat configuration.
  1. Install local git hooks `./task.sh setup:git-hooks`
  1. Setup Behat configuration ``./task.sh setup:behat`

### Optionally, install Drupal

  1. Optionally, you may install Drupal via Phing. To do this, verify correct
     credentials in `local.yml` and then run:
     `./task.sh setup:install:drupal`

### Optionally, execute tests

  1. To run code validation (phpcs, phpmd, pdepend) against your new project
     run: `./task.sh validate:all`
  1. To run automated tests (behat, phpunit) against your new project, run:
     `./task.sh tests:all`. Please note that Behat
     tests will only run successfully if Drupal is installed.
     
## Next Steps

After Bolt has installed, there are several key activities to perform for your project:

  1. Update your project's make file
    * Add contributed modules and themes to `make.yml`
    * Build docroot via `./task.sh setup:build:all`
  1. Update your project readme.md
  1. Update the project documentation (in `docs`)
  1. Review and include common settings snippets (in `sites\default\settings`)
    * Review which settings snippets in `sites\default\settings` are relevant for your project
    * Include relevant settings within your site-specific by uncommenting require line(s)
  1. (optional) Bootstrap the virtual machine
     * `.task.sh vm:add`

## Integration with 3rd Party Services

  1. Enable TravisCI
  2. Enable Slack integration with TravisCI

## Verification

  1. To visit the site locally via browser.
    * If you have a locally maintained LAMP stack (E.g., MAMP), do the following:
      * Verify correct db creds in `sites/all/settings/local.settings.php`
      * Configure your local LAMP stack such that the docroot is associated with the $base_url
      * Visit the local_url that you set in project.yml
    * If you used the included Ansible virtual machine ...
      * `cd box`
      * `vagrant plugin install vagrant-hostsupdater`
      * `sudo ansible-galaxy install -r provisioning/requirements.txt --force`
      * `vagrant up` This will take about 5 minutes. It will provision your
         virtual machine and install Drupal.
      * Your local hosts file should already be configured such that your
        local development URL points to your Ansible machine's VM. So, open
        your native browser and navigate to the local_url specified in
        `project.yml`
      * Verify your Drush alias has been created for your project, `drush sa`
