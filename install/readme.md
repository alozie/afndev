## Overview

The project template has an installer which will do the following:

* Create new project directory. It will be a sibling of the project template repository.
* (optionally) Adds a Drupal VM for a local development environment
* (optionally) Adds specified testing frameworks
* (optionally) Adds specified project documentation
* Include custom settings in settings.php
* Remove installation artifacts

## Installer Requirements

* Composer [Install](https://getcomposer.org/doc/00-intro.md#globally)
* PHP 5.3.9+ (PHP 5.5 recommended)
  * [Homebrew Install](https://lastzero.net/2013/08/howto-install-php-5-5-and-phpunit-on-os-x-via-homebrew/)
* PHP modules
  * ctype
  * json
  * pcntl

### Virtual Machine Requirements

If you'd like to use the included Ansible Drupal VM, you will need to install
the following dependencies:

* VirtualBox 4.3.x [Download](https://www.virtualbox.org/wiki/Downloads) 
  * Drupal VM also works with Parallels or VMware if you have the [Vagrant VMware integration plugin](http://www.vagrantup.com/vmware))
* Vagrant 1.7.2 or higher [Download](http://www.vagrantup.com/downloads.html)
* Vagrant Host Updater
  * Instructions: `vagrant plugin install vagrant-hostsupdater`
* Ansible 1.9.2 or higher [Install](http://docs.ansible.com/intro_installation.html). 
  * Mac / Linux only
  * OSX instructions from Homebrew: `brew install ansible`
  * Linux instructions:
    * `sudo easy_install pip`
    * `sudo CFLAGS=-Qunused-arguments CPPFLAGS=-Qunused-arguments pip install ansible`

## Configure

To create a new repository using Project Template's installer, run the 
follow from this repository's root directory:

  1. Run `cp example.config.yml config.yml` to create your project-specific configuration file.
  1. Modify `config.yml` with values for your new project.
  
Please note the importance of updating config.yml for the needs of your project.
     
## Installation

To create a new repository using Project Template's installer, run the 
follow from this repository's root directory:

  1. Run `composer install --working-dir=install` from the root directory
  1. Run `php bin/project-template-installer install` to create your project.
  1. Installation is complete! You now have a new repository that is a sibling
     of the project template on your local machine.
     
## Next Steps

After project template has installed, there are several key activities to perform for your project:

  1. Review the console output from the installer
    * Virtual machine requirements
       * The installer will check for specific versions of dependent software
       * The installer will not bootstrap a VM if the project has a shared hostname or IP
         * To manually install the VM, make adjustments to `config.yml` and `box/config.yml`
         * Re-run the DrupalVM installation by following steps 5 and 6 of https://github.com/geerlingguy/drupal-vm/#2---build-the-virtual-machine
    * Virtual machine installation
       * If bootstrap is enabled, the installer will output all DrupalVM installation logs
       * Check for errors and create JIRA tickets with any issues you believe to be a bug
  1. Update your project's make file
    * Add contributed modules and themes to `scripts/project.make.yml`
    * Execute Drush Make from the project's docroot (e.g. `drush make ../scripts/project.make.yml`) 
  1. Update your project readme.md (in project root)
    * Review each section and update the examples for your project needs
  1. Update the configured project documentation (in `docs`)
    * Architecture template, review each section and begin to specify your projects architecture
    * Open Source Contribution template, review the contents and ensure the contents meet the needs of your project
  1. Review and include common settings snippets (in `docroot\sites\all\settings`)
    * Review which settings snippets in `docroot\sites\all\settings` are relevant for your project
    * Update the contents of each relevant setting in `docroot\sites\all\settings`
    * Include relevant settings within your site-specific `settings.php` file (e.g. `require_once ../all/settings/base.settings.php`)  
  1. Configure hooks for your project
    * Update the git hooks found in `git` directory
    * Update the Acquia Cloud deployment hooks found in `hooks` directory

## Verification
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
      * Verify your Drush alias has been created for your project, `drush sa`
  1. To push this to Acquia Cloud
    * `git checkout -b 7.x`
    * `git add -A`
    * `git commit -m 'Initial commit'`
    * `git push acquia -f`
  1. Enable TravisCI for your project.
  
  
## Project Removal

If you wish to remove a project, it is best to follow these steps:

  1. Destroy the VM (run `vagrant destroy` from the `box` directory)
  1. Remove project files (run `sudo rm -rf /path/to/project`)
  

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
