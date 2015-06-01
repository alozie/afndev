# PROJECT Template

This repository is the template for all new Professional Services projects.

## Installation

When beginning a new Drupal project, do the following:

* Clone this Project Template repository to a local directory
* Follow the instructions in `/install/readme.md`. This will create a repository on your local machine for the new project, and handle building dependencies.

## Directory Structure

The following is an overview of the purpose of each top level directory:

    root
      ├── bin     - Contains scripts required for the build process.
      ├── box     - Contains the virtual machine.
      ├── docroot - The drupal docroot.
      ├── git     - Contains configuration files that will be copied into a new project's `.git` directory upon installation.
      ├── hooks   - Contains [Acquia Cloud hooks](https://docs.acquia.com/cloud/manage/cloud-hooks).
      ├── install - Contains Project Template Installer, which will be removed after installation.
      ├── scripts - Contains a variety of utility scripts that are not part of the build process.
      ├── sites   - The Drupal "sites" directory, symlinked to docroot/sites.
      ├── tests   - Contains all test files.
