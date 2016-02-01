# Project Tasks

“how do I _____ on my local machine?”

* [(re)Installing Drupal](#install-drupal)
* [Updating dependencies (module, theme, core, etc.)](#update-dependency)
* [Patching a project](#patch)
* [Deploying to Cloud](#deploy)
* [Tests * code validation](#tests)

## <a name="install-drupal"></a>(re)Installing Drupal

Pre-requisites to installation:

1. Ensure that `docroot/sites/default/settings/local.settings.php` exists by 
  executing `./task.sh setup:drupal:settings`. 
1. Verify that correct local database credentials are set in 
  `local.settings.php`.
1. Ensure that project dependencies have already been built via 
  `./task.sh setup:build:all`
   
To re-install Drupal, execute: `./task.sh setup:drupal:install`. Note that this
will drop the existing database tables and install Drupal from scratch!

## <a name="update-dependency"></a>Updating dependencies (module, theme, core, etc.)

To install or update contributed dependencies, simply update the dependency 
version(s) in composer.json and run `composer install` or `composer update`.

Note that Composer versioning is not identical to drupal.org versioning. See:

* [Composer Versions](https://getcomposer.org/doc/articles/versions.md) - Read up on how to specify versions.
* [Drupal packagist site](https://packagist.drupal-composer.org/) - Find packages and their current versions.
* [Drupal packagist project](https://github.com/drupal-composer/drupal-packagist) - Submit issues and pull requests to the engine that runs Drupal packagist.

To update drupal core: 

1. Update the entry for `drupal/core` in the root composer.json. 
2. Run `composer update`. 
3. Run `./scripts/drupal/update-scaffold`. This will update the core files not included in `drupal/core`. 
4. Use git to review changes to committed files. E.g., changes to .htaccess, robots.txt, etc. 
5. Add and commit desired changes.

## <a name="patch"></a>Patching a project

Drupal core and contrib can be patched in `composer.json` using 
`cweagans/composer-patches`, which is required by default. Patch information
should be specified in the JSON array in accordance with the following schema:

    "extra": {
      "patches": {
        "drupal/core": {
          "Ignore front end vendor folders to improve directory search performance": "https://www.drupal.org/files/issues/ignore_front_end_vendor-2329453-116.patch"
        }
      }
    },

## <a name="deploy"></a>Deploying to Cloud

Please see [Deploy](deploy.md) for a detailed description of how to deploy to
 Acquia Cloud.

## <a name="tests"></a>Tests & code Validation

Please see [tests/README.md](../tests/README.md) for information on running
tests.

To execute PHP codesniffer and PHP lint against the project codebase, run:
`./task.sh validate:all`

## Build front end assets

@todo document this!
