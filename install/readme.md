## Overview

The project template has an installer which will do the following:

* Create new project directory. It will be a sibling of the project template repository.
* (optionally) Add Drupal VM for a local development environment
* (optionally) Add a testing framework
* Include custom settings in settings.php
* Remove installation artifacts

## System Requirements

* Composer

### For Virtual Machine

If you'd like to use the included Ansible Drupal VM, you will need to install
the following dependencies:

* Download and install [VirtualBox](https://www.virtualbox.org/wiki/Downloads) (Drupal VM also works with Parallels or VMware, if you have the [Vagrant VMware integration plugin](http://www.vagrantup.com/vmware)).
* Download and install [Vagrant](http://www.vagrantup.com/downloads.html).
* [Mac/Linux only] Install [Ansible](http://docs.ansible.com/intro_installation.html. 
  * OSX instructions: `brew install ansible`
  * Linux instructions:
    * `sudo easy_install pip`
    * `sudo CFLAGS=-Qunused-arguments CPPFLAGS=-Qunused-arguments pip install ansible`

## Installation

To create a new repository using Project Template's installer, run the 
follow from this repository's root directory:

  1. Run `cp example.config.yml config.yml` to create your configuration file.
  1. Modify `config.yml` with values for your new project.
  1. Run `composer install --working-dir=install` from the root directory
  1. Run `php bin/project-template-installer install` to create your project.
  1. Installation is complete! You now have a new repository that is a sibling
     of the project template on your local machine.

## Next steps.
  1. To visit the site locally via browser.
    * If you have a locally maintained LAMP stack (E.g., MAMP), do the following:
      * Configure your local database credentials in `sites/default/local.settings.php`
      * Set up your local_url to work on your local LAMP stack by making an entry in `/etc/hosts`
      * Visit the local_url that you set in config.yml
    * If you used the included Ansible virtual machine ...
      * `cd box`
      * `vagrant plugin install vagrant-hostsupdater`
      * `sudo ansible-galaxy install -r provisioning/requirements.txt --force`
      * `vagrant up` This will take about 5 minutes. It will provision your
         virtual machine and install Drupal.
      * Your local hosts file should already be configured such that your
        local development URL points to your Ansible machine's VM. So, open
        your native browser and navigate to the local_url specified in
        `config.yml`
  1. To push this to Acquia Cloud
    * `git checkout -b 7.x`
    * `git add -A`
    * `git commit -m 'Initial commit'`
    * `git push acquia -f`
  1. Enable TravisCI for your project.

## Explore what you have

Read "Exploring project template"

## Under the hood

If you're interested in modifying the installer and contributing 

The installer for Project Template uses an implementation of Symfony's console
component. Salient files for the installation process include:

* _src/ProjectTemplate/Installer/Installer.php_
* composer.json
* bin/project-template-installer
* bin/project-template-installer.php
