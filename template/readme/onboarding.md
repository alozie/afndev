# Onboarding

Here is a quick-start guide to getting your local development environment
set up and getting oriented with the project standards and workflows.

## Required SAAS Access:

Please ask the project's engagement manager for access to the following SAAS 
services:

* JIRA
* GitHub repository
* Acquia Cloud subscription

## Initial Setup

Each developer should [fork](https://help.github.com/articles/fork-a-repo) the 
primary Git repository for their work. All developers should then checkout a 
local copy of the master branch to begin work -

    git clone git@github.com:username/project-repo.git -b master
    git remote add upstream git@github.com:acquia-pso/project-repo.git

1. Clone your fork to your local machine.
1. Make sure you are on the `develop` branch for most work.
1. Run `composer install` (you must already have Composer installed).
1. Run `./task.sh setup:git-hooks`. This will install hooks that validate code
standards and commit message format prior to commiting.
1. Copy `sites/default/settings/default.local.settings.php` to 
`sites/default/settings/local.settings.php` and update the values with local 
database credentials.
1. Run `./task.sh setup:build:all`. This will run Drush make and place the built
site platform into `/docroot`. It will also symlink custom modules and themes
into place, so that changes you make will be reflected immediately on the site
and in Git.
1. Run `./task.sh setup:drupal:install`. This will create use values defined in
`local.settings.php`.

After this initial setup, you should only need to run `./task.sh setup:build:all`
when the make file is updated, and `./task.sh setup:drupal:install` when you
need to reinstall the site.

## Configure Local Environment

Please see [Local Development](/local-development.md) for detailed 
information on setting up a local LAMP stack. In addition to this, please
ensure also perform the configurations listed below.

### Local Git Configuration

For readability of commit history, set your name properly -

    git config user.name "Developer"

*You likely want to do this globally using `git config --global`.*

Ensure that your local email address correctly matches the email address for
 your Jira account -

    git config user.email developer@example.com

## GitHub Configuration

In order to more easily identify developers in a project, please be sure to set
a name and profile picture in your GitHub profile.

When working with GitHub, the [hub](https://github.com/github/hub) utility can 
be helpful when managing forks and pull requests. Installing hub largely depends
on your local environment, so please follow the [installation instructions]
(https://github.com/github/hub#installation) accordingly.

## Next steps

Review the [Agile Guide](agile-guide.md) and [Developer Workflow]
(developer-guide.md) for information on workflows and code standards.
