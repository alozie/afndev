## Overview

The project template has an installer which will do the following:

* Create new project directory. It will be a sibling of the project template repository.
* (optionally) Add Drupal VM for a local development environment
* (optionally) Add a testing framework
* Include custom settings in settings.php
* Remove installation artifacts

## Installation

From this repository's root directory:

  1. Copy `example.config.yml` to `config.yml` and configure the settings for your project.
     `cp example.config.yml config.yml`
  2. Run `composer install --working-dir=install` to install dependencies.
  3. Run `php bin/project-template-installer install` to create your project.

## Under the hood

The installer for Project Template uses an implementation of Symfony's console
component. Salient files for the installation process include:

* _src/ProjectTemplate/Installer/Installer.php_
* composer.json
* bin/project-template-installer
* bin/project-template-installer.php
