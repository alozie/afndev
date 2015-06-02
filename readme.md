# Project Template

This repository is the template for all new Professional Services projects.

## Installation

When beginning a new Drupal project, do the following:

* Clone this Project Template repository to a local directory
* Follow the instructions in [/install/readme.md](/install/readme.md). This will create a repository on your local machine for the new project, and handle building dependencies.

## Directory Structure

The following is an overview of the purpose of each top level directory:

    root
      ├── bin     - Contains binaries built by Composer, as well as installation binaries.
      ├── box     - Contains the virtual machine.
      ├── build   - Contains build config files for CI solutions. E.g., phing configuration.
      ├── docroot - The drupal docroot.
      ├── docs    - Contains high level project documentation. 
      ├── git     - Contains configuration files that will be copied into a new project's `.git` directory upon installation.
      ├── hooks   - Contains Acquia Cloud hooks.
      ├── install - Contains Project Template Installer, which will be removed after installation.
      ├── patches - Contains private patches to be used by scripts/project.make.yml.
      ├── scripts - Contains a variety of utility scripts that are not part of the build process.
      ├── tests   - Contains all test files.

# Contributing

Project Template work is currently being tracked in [Acquia's internal JIRA instance](https://backlog.acquia.com/browse/PPT).
