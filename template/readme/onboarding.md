# Onboarding

Here is a quick-start guide to getting your local development environment
set up and getting oriented with the project standards and workflows.

## Initial setup

Get your local environment set up. You can use a standard LAMP/MAMP stack,
Dev Desktop, or a custom environment such as Docker. Review the
[Local Development](local-development.md) guide for more information.

## Day-to-day development

* Clone this repository to your local machine.
* Make sure you are on the `develop` branch for most work.
* Run `composer install` (you must already have Composer installed).
* Run `./task.sh setup:git-hooks`. This will install hooks that validate code
standards and commit message format prior to commiting.
* You should now see a `local.yml` in the root directory. Update the values in
`local.yml` with local database credentials.
* Run `./task.sh setup:build:all`. This will run Drush make and place the built
site platform into `/docroot`. It will also symlink custom modules and themes
into place, so that changes you make will be reflected immediately on the site
and in Git.
* Run `./task.sh setup:drupal:install`. This will create `local.settings.php`
based on the values in `local.yml` and install the site.

After this initial setup, you should only need to run `./task.sh setup:build:all`
when the make file is updated, and `./task.sh setup:drupal:install` when you
need to reinstall the site.

## Next steps

Review the [Developer Guide](developer-guide.md) for information on workflows
and code standards.

Review the [Theming Guide](theming.md) for information specifically related to
theming standards.
