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
following from this repository's root directory:

### Create a new project, build all dependencies.

1. `composer install`
1. Run `./task.sh bolt:configure` to create your project-specific
   configuration files. After running, `project.yml`, `make.yml`,
   and `local.yml` should exist in the Bolt root directory.
1. Modify aforementioned .yml files with values for your new project.
1. Run `./task.sh bolt:create`. This will create a new directory for your new
   project.
1. Change directories to your new project directory.
   E.g., `cd /path/to/my/new/project`.
1. In your new project directory, run `./task.sh setup`.
   This will build dependencies in your make file and create required
   symlinks.
1. To read a full list of available tasks, run `./task.sh -list`.

## Next Steps

After Bolt has installed, there are several key activities to perform for your
project:

1. Update your project's make file.
  * Add contributed modules and themes to `make.yml`
  * Re-build docroot via `./task.sh setup:build:all`
1. Update your project README.md.
1. Update the project documentation (in `readme`).
1. Set up your local \*AMP stack using. See [Local Environment]
   (/readme/local-development.md). documentation.
1. Optionally, you may install Drupal locally via Phing. To do this, verify
   correct credentials in `local.yml` and then run:
   `./task.sh setup:drupal:install`

### Configure your CI solution

Travis CI is used for both automated testing and for deploying to Acquia Cloud.

Best practices dictate that the docroot should not be committed to the
repository. This allows the deployed site to have complete parity with the
project's make.yml, and avoids undocumented modifications to core and contrib.
As such, Travis is always used for deploying a built-docroot to the cloud.

Your GitHub repository should have Travis CI enabled when it is created. If it
is not enabled, contact your Technical Team Lead and have him/her enable it.

Once it is enabled, follow the steps under
"Setting Up Travis CI for automated deployments" in [build/README.md](/build/README.md)

### Visit the site!

  1. To visit the site locally via browser.
    * If you have a locally maintained LAMP stack (E.g., MAMP), do the following:
      * Verify correct db creds in `sites/default/settings/local.settings.php`
      * Configure your local LAMP stack such that the docroot is associated with
        the $base_url
      * Visit the local_url that you set in project.yml
    * If you used a different local development environment, visit the 
      configured `local_url` for the site.
