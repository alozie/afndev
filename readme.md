# Project Template

This repository is the template for all new Professional Services projects.

## Table of Contents

* [Installation](#installation)
* [Features](#features)
  * [Virtual Machine](#virtual-machine)
  * [Documentation Templates](#documentation-templates)
  * [Git Hooks](#git-hooks)
  * [Testing Framework](#testing-framework)
* [Directory Structure](#directory-structure)
* [Contributing](#contributing) (to Project Template)

## Installation

When beginning a new Drupal project, do the following:

* Clone this Project Template repository to a local directory.
* Follow the instructions in [/install/readme.md](/install/readme.md).

## Features

### Virtual Machine

Project Template ships with the [Drupal VM](https://github.com/geerlingguy/drupal-vm), a simple virtual machine built on Ansible and Vagrant. The project template installer will generate all of the necessary vagrant configuration files in the `box` directory by default.

For full instructions on how to configure and install the packaged VM, see [Drupal VM](https://github.com/geerlingguy/drupal-vm) documentation.

### Documentation Templates

The following default documentation is included in Project Template:
* [Onboarding](/docs/onboarding.md)
* [Developer Guide](/docs/developer-guide.md)
* [Architecture Template](/docs/drupal-architecture-template.md)
* [Open Source Contribution Guide](/docs/os-contribution.md)
* [Project Readme](/docs/readme.md)
* [Theming Guide](/docs/theming.md)

### Git Hooks

Default [git hooks](https://git-scm.com/book/en/v2/Customizing-Git-Git-Hooks) are included with Project Template. These are copied into your local repository's `.git` directory upon intallation. All new developers onboarding onto a project should copy these hooks into their local repository's `.git/hooks` directory after cloning the project.

Please see the [Git Hooks Readme](/hooks/readme.md) for more information.

### Testing Framework

The project template includes both [example tests](/tests) and also [example
build configurations](/build) for Travis CI.

#### Example Tests
A set of example tests for the following testing technologies are included in the [tests](/tests) directory.
* Behat
* PHPUnit
* Jmeter

#### Example Builds
A starter configuration for running builds on Travis CI is included. The
configuration lives in [.travis.yml](/.travis.yml) and [build](/build). At a high level, the build
will do the following:
* Execute a Travis CI build when a Pull Request is submitted to GitHub.
  * Build dependencies (e.g., composer)
  * Run phing targets. Phing targets include:
    * setup:build:make           - executes drush make on [/scripts/project.make.yml]
    * setup:install:drupal - installs Drupal to Travis environment via `drush si`
    * validate:all         - runs code validation (e.g., code sniffer)
    * tests:all            - executes Behat and PHPUnit tests against installed Drupal instance

## Directory Structure

The following is an overview of the purpose of each top level directory:

    root
      ├── bin     - Contains binaries built by Composer, as well as installation binaries.
      ├── box     - Contains the virtual machine.
      ├── build   - Contains build config files for CI solutions. E.g., Phing configuration.
      ├── docroot - The drupal docroot. Intentionally .gitignored. Created only during builds.
      ├── docs    - Contains high level project documentation.
      ├── git     - Contains configuration files that will be copied into a new project's .git directory upon installation.
      ├── hooks   - Contains Acquia Cloud hooks.
      ├── install - Contains Project Template configuration files. Removed from child projects.
      ├── patches - Contains private patches to be used by make.yml.
      ├── scripts - Contains a variety of utility scripts that are not part of the build process.
      ├── tests   - Contains all test files and configuration.

# Contributing

Project Template work is currently being tracked in [Acquia's internal JIRA instance](https://backlog.acquia.com/browse/PPT).

[![Build Status](https://magnum.travis-ci.com/acquia-pso/project-template.svg?token=eFBAT6vQ9cqDh1Sed5Mw&branch=7.x)](https://magnum.travis-ci.com/acquia-pso/project-template)
