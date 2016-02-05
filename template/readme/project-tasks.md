# Project Tasks

“how do I _____ on my local machine?”

* [(re)Install Drupal](#install-drupal)
* [Update dependencies (module, theme, core, etc.)](#update-dependency)
* [Patch a project](#patch)
* [Deploy to cloud](#deploy)
* [Run tests & code validation](#tests)
* [Build frontend assets](#frontend)

## <a name="install-drupal"></a>(re)Install Drupal

Pre-requisites to installation:

1. Ensure that `docroot/sites/default/settings/local.settings.php` exists by 
  executing `./task.sh setup:drupal:settings`. 
1. Verify that correct local database credentials are set in 
  `local.settings.php`.
1. Ensure that project dependencies have already been built via 
  `./task.sh setup:build:all`
   
To re-install Drupal, execute: `./task.sh setup:drupal:install`. Note that this
will drop the existing database tables and install Drupal from scratch!

## <a name="update-dependency"></a>Update dependencies (core, profile, module, theme, librarires)

To install or update contributed dependencies, simply update the dependency 
version(s) in composer.json and run `composer install` or `composer update`.

All contributed projects hosted on drupal.org, including Drupal core, profiles,
 modules, and themes, can be found on [Drupal packagist]
 (https://packagist.drupal-composer.org/).
non-Drupal libraries (hosted on Packagist). 

Note that Composer versioning is not identical to drupal.org versioning. See:

* [Composer Versions](https://getcomposer.org/doc/articles/versions.md) - Read up on how to specify versions.
* [Drupal packagist site](https://packagist.drupal-composer.org/) - Find packages and their current versions.
* [Drupal packagist project](https://github.com/drupal-composer/drupal-packagist) - Submit issues and pull requests to the engine that runs Drupal packagist.
* [Drupal packagist project](https://github.com/drupal-composer/drupal-packagist) - Submit issues and pull requests to the engine that runs Drupal packagist.
* [Packagist](http://packagist.com/) - Find non-drupal libraries and their current versions.

To update drupal core: 

1. Update the entry for `drupal/core` in the root composer.json. 
2. Run `composer update`. 
3. Run `./scripts/drupal/update-scaffold`. This will update the core files not included in `drupal/core`. 
4. Use git to review changes to committed files. E.g., changes to .htaccess, robots.txt, etc. 
5. Add and commit desired changes.

## <a name="patch"></a>Patch a project

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

## <a name="deploy"></a>Deploy to cloud

Please see [Deploy](deploy.md) for a detailed description of how to deploy to
 Acquia Cloud.

## <a name="tests"></a>Run tests & code validation

Please see [tests/README.md](../tests/README.md) for information on running
tests.

To execute PHP codesniffer and PHP lint against the project codebase, run:
`./task.sh validate:all`

## <a name="frontend"></a>Build front end assets

Ideally, you will be using a theme that uses SASS/SCSS, a styleguide, and other
tools that require compilation. Like dependencies, the compiled assets should
not be directly committed to the project repository. Instead, they should be 
built during the creation of a production-ready build artifact.

Bolt only natively supports the [Acquia PS Thunder](https://github.com/acquia-pso/thunder)
base theme.

To install Thunder's dependencies:

1. See [Acquia PS Thunder](https://github.com/acquia-pso/thunder) for system requirements. 
1. Execute `/.task frontend:install`.

To build Thunder's assets, execute:

`/.task frontend:build`.
