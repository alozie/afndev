## Overview

The project template has an installer which will do the following:

* Create new project directory. It will be a sibling of the project template repository.
* (optionally) Add Ubuia virtual box
* (optionally) Build a specified make file
* Include custom settings in settings.php
* Add a testing framework

## Installation

From this repository's root directory, run:

    composer install --working-dir=install
    php bin/project-template-installer install

This will guide you through the installation process. To specify a custom make
file for installation, pass the make file location as an argument:

    php bin/project-template-installer install [make-file]

## Under the hood

The installer for Project Template uses an implementation of Symfony's console
component. Salient files for the installation process include:

* _src/ProjectTemplate/Installer/Installer.php_
* composer.json
* bin/project-template-installer
* bin/project-template-installer.php
