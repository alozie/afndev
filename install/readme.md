## Overview

Bolt has an installer which will do the following:

* Create new project directory (sibling of the Bolt repository)
* Copies Bolt files to new directory, including:
  * Git Hooks
  * Acquia Cloud Hooks
  * Project documentation
  * Testing frameworks
  * CI configuration
  * Custom settings.php files
* Replaces tokens in copied files with project-specific strings
* Removes installation artifacts

## Installer Requirements

* Composer [Install](https://getcomposer.org/doc/00-intro.md#globally)
* PHP 5.3.9+ (PHP 5.5 recommended)
  * [Homebrew Install](https://lastzero.net/2013/08/howto-install-php-5-5-and-phpunit-on-os-x-via-homebrew/)

## Create new project

To create a new repository using Bolt's installer, run the
follow from this repository's root directory:

### Create a new project, build all dependencies.

  1. `composer install`
  1. Run `./task.sh bolt:configure` to create your
     project-specific configuration file. After running, `project.yml`, `make.yml`,
     and `local.yml` should exist in the Bolt root directory.
  1. Modify aforementioned .yml files with values for your new project.
  1. Run `./task.sh bolt:create`
     This will create a new directory for your new project.
  1. Change directories to your new project directory. E.g., `cd /path/to/my/new/project`.
  1. In your new project directory, run `./task.sh setup:build:all`.
     This will build dependencies in your make file and setup behat config.
  1. Install local git hooks `./task.sh setup:git-hooks`
  1. Setup Behat configuration ``./task.sh setup:behat`
     
## Next Steps

After Bolt has installed, there are several key activities to perform for your project:

1. Update your project's make file
  * Add contributed modules and themes to `make.yml`
  * Build docroot via `./task.sh setup:build:all`
1. Update your project readme.md
1. Update the project documentation (in `docs`)
1. Review and include common settings snippets (in `sites\all\settings`)
  * Review which settings snippets in `sites\all\settings` are relevant for your project
  * Include relevant settings within your site-specific by uncommenting require line(s)

### Optionally, install Drupal locally

  1. Optionally, you may install Drupal via Phing. To do this, verify correct
     credentials in `local.yml` and then run:
     `./task.sh setup:install:drupal`

### Optionally, download and bootstrap VM
 
  1. `./task.sh vm:add`
  
  See [box directory](/box) for more information.

### Optionally, integrate with 3rd party services

  1. Enable TravisCI
  2. Enable Slack integration with TravisCI

### Visit the site!

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
