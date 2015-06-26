# PROJECT NAME

This document outlines technical onboarding for the PROJECT NAME project.

{{md docs/readme.md}}

## Architectural Summary
* Drupal 7
* Distribution name
* Base theme
* Acquia Cloud / Self-Hosted

## Resources
* [Github](https://github.com/acquia-pso/PROJECT)
* [Backlog](http://backlog.acquia.com/PROJECT) 
* [Docs](http://drive.google.com/PROJECT)
* [VM Quick Start](https://github.com/geerlingguy/drupal-vm#quick-start-guide)
* [Using the VM](https://github.com/geerlingguy/drupal-vm#using-drupal-vm)
* [Slack Channel](https://slack.com/project-channel)

## Environments

### Local
* Web Server: [http://project.dev](http://project.dev)
* XHProf: [http://xhprof.project.dev](http://xhprof.project.dev)
* Pimp My Log: [http://pimpmylog.project.dev](http://pimpmylog.project.dev)
* PHPMyAdmin: [http://phpmyadmin.project.dev](http://phpmyadmin.project.dev)
* SOLR: [http://solr.project.dev](http://solr.project.dev)

### Remote
* Dev: [http://projectdev.prod.acquia-sites.com](http://projectdev.prod.acquia-sites.com)
* Test: [http://projecttest.prod.acquia-sites.com](http://projecttest.prod.acquia-sites.com/)
* UAT: [http://projectuat.prod.acquia-sites.com](http://projectuat.prod.acquia-sites.com)
* Prod: [http://www.project.com](http://www.project.com)

## Development Prerequisites

### Environment
* VirtualBox [download](https://www.virtualbox.org/wiki/Downloads)
* Vagrant [download](http://www.vagrantup.com/downloads.html)
* Ansible [download](http://docs.ansible.com/intro_installation.html)
* Git [downoad](https://git-scm.com/downloads)

### Development Tools
* Drush [install](http://docs.drush.org/en/master/install/)
* Drush Aliases [instructions](https://docs.acquia.com/cloud/drush-aliases)
* FRONT END TOOLS
* BACKEND TOOLS

## Onboarding
* Fork repo to your own repository
* Use of branches and pull requests

### Installing VM
Make sure to set up your environment first. You should successfully install VirtualBox, Vagrant via the download links above.
* Run either `brew install ansible` (if you have homebrew) or `sudo pip install ansible`(if you have pip)
* Run `vagrant plugin install vagrant-hostsupdater`
* Run `sudo ansible-galaxy install -r provisioning/requirements.txt` from box directory
* Run `vagrant up` from box directory

### Development Workflow
* Pull down most recent code from `upstream`
* Create a new branch with the ticket number assigned
* Execute development task
* Test locally, attach screenshot and/or testing criteria to ticket
* Evaluate automated tests; create or modify existing tests as needed
* Mark ticket as development complete
* Create pull request from your remote
* Add pull request link to ticket and assign to release manager

## Feature Notes

### Behat testing
Behat testing is enabled for local development and regression testing on the `test` environment
* `drush @project.local brm` - This will register the behat tests.
* `drush @project.local brun` - This runs the behat tests.

### Migration
The following commands are necessary to debug migrations
* Source files are located under the `migration-sources` directory
* Sources can be controlled by the `project_migration_path` variable in Drupal
* Enable the migration module:`drush en project_migration -y`
* Ensure the migrations are registered: `drush migrate-register`
* View the current state of the migrations: `drush ms`
* Execute one of the migrations: `drush mi TestMigration`
* Debug one of the migrations: `drush mi TestMigration --limit="100 items" --stop --rollback`

### Single Sign On
SSO leverages simplesamlphp and the simplesamlphp_auth Drupal module.
* SSO occurs on UAT, Test, and Production environments
* SimpleSAMLPHP library and the IdP metadata is stored outside of the docroot at `/simplesamlphp`
* Drupal configuration options are set within settings.php

### Search
Search is built on top of Apache SOLR
* The local administration interface is available at http://solr.project.dev/solr/core0/admin/
* Starting the local SOLR instance: `sudo service tomcat6 start`
* Stopping the local SOLR instance: `sudo service tomcat6 stop`
* Enable the local SOLR index in Drupal: `drush solr-set-env-url --id=local`
