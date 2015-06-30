## Overview

The project template has an installer which will do the following:

* Create new project directory. It will be a sibling of the project template repository.
* (optionally) Add Drupal VM for a local development environment
* (optionally) Add a testing framework
* Include custom settings in settings.php
* Remove installation artifacts

## Prerequisites
  1. Install the latest version of VirtualBox.  Make sure that you have at least
     4.3.28. 
  1. Install the latest version of Vagrant. 
  1. If you are on OSX or Linux install ansible.  
     Run either `brew install ansible` (if you have homebrew) or `sudo pip install ansible`(if you have pip)

## Installation

From this repository's root directory:

  1. Run `cp example.config.yml config.yml` to create your configuration file.
  1. Modify `config.yml` with values for your new project.
  1. Run `composer install --working-dir=install` from the root directory
  1. Run `php bin/project-template-installer install` to create your project.

## Under the hood

The installer for Project Template uses an implementation of Symfony's console
component. Salient files for the installation process include:

* _src/ProjectTemplate/Installer/Installer.php_
* composer.json
* bin/project-template-installer
* bin/project-template-installer.php
