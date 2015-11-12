## Overview

Bolt has an installer which will do the following:

* Create new project directory (sibling of the Bolt repository)
* Copies the Bolt template to the new directory, including:
** Git Hooks
** Acquia Cloud Hooks
** Project documentation
** Testing frameworks
** CI configuration
** Custom settings.php files
* Replaces tokens in copied files with project-specific strings

## Installer Requirements

* Composer [Install](https://getcomposer.org/doc/00-intro.md#globally)
* PHP 5.3.9+ (PHP 5.5 recommended)
  * [Homebrew Install](https://lastzero.net/2013/08/howto-install-php-5-5-and-phpunit-on-os-x-via-homebrew/)

## Create new project

To create a new repository using Bolt's installer, run the
following from this repository's root directory:

### Create a new project, build all dependencies.

  1. `composer install`
  1. Run `./task.sh bolt:configure` to create your
  project-specific configuration files. After running, `project.yml`,
  `local.yml`, and `make.yml` should exist in the Bolt root directory.
  1. Modify aforementioned .yml files with values for your new project.
  1. Run `./task.sh bolt:create` to create a new directory for your new project.
  1. Change directories to your new project directory. E.g.,
  `cd /path/to/my/new/project`.
  1. In your new project directory, run `./task.sh setup:build:all`.
  This will build dependencies in your make file and setup behat configuration.
  1. Install local git hooks `./task.sh setup:git-hooks`

## Next Steps

After Bolt has installed, there are several key activities to perform for your project:

  1. Update your project's make file
    * Add contributed modules and themes to `make.yml`
    * Build docroot via `./task.sh setup:build:all`
  1. Update your project readme.md
  1. Update the project documentation (in `readme`)
  1. Review and include common settings snippets (in `sites\default\settings`)
    * Review which settings snippets in `sites\default\settings` are relevant for your project
    * Include relevant settings within your site-specific by uncommenting require line(s)

### Optionally, install Drupal locally

  1. Optionally, you may install Drupal via Phing. To do this, verify correct
     credentials in `local.yml` and then run:
     `./task.sh setup:drupal:install`

### Optionally, integrate with 3rd party services

  1. Enable TravisCI
  2. Enable Slack integration with TravisCI

### Visit the site!

  1. To visit the site locally via browser.
    * If you have a locally maintained LAMP stack (E.g., MAMP), do the following:
      * Verify correct db creds in `sites/default/settings/local.settings.php`
      * Configure your local LAMP stack such that the docroot is associated with the $base_url
      * Visit the local_url that you set in project.yml
    * If you used a different local development environment, visit the configured `local_url` for the site.
