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

@todo Document this.

## <a name="patch"></a>Patch a project

Please see [patches/README.md](../patches/README.md) for information on patch 
naming, patch application, and patch contribution guidance.

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
