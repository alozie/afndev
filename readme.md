# Bolt

(formerly Project Template)

This repository is the template for all new Professional Services projects.

## Table of Contents

* [Installation](#installation)
* [Features](#features)
  * [Git Hooks](#git-hooks)
  * [Acquia Cloud Hooks](#acquia-cloud-hooks)
  * [Documentation Templates](#documentation-templates)
  * [Testing Framework](#testing-framework)
  * [Continuous Integration](#continuous-integration)
  * [Virtual Machine](#virtual-machine)
* [Directory Structure](#directory-structure)
* [Contributing](#contributing) (to Bolt)

## Installation

When beginning a new Drupal project, do the following:

* Clone this Bolt repository to a local directory.
* Follow the instructions in [/install/readme.md](/install/readme.md).

## Features

### Documentation Templates

The following default documentation is included in Bolt:
* [Onboarding](/readme/onboarding.md)
* [Developer Guide](/readme/developer-guide.md)
* [Architecture Template](/readme/drupal-architecture-template.md)
* [Open Source Contribution Guide](/readme/os-contribution.md)
* [Project Readme](/readme/readme.md)
* [Theming Guide](/readme/theming.md)
* [Local Development](/readme/local-development.md)

### Git Hooks

Default [git hooks](https://git-scm.com/book/en/v2/Customizing-Git-Git-Hooks) are included with Bolt. These are copied into your local repository's `.git` directory upon intallation. All new developers onboarding onto a project should copy these hooks into their local repository's `.git/hooks` directory after cloning the project.

Please see the [Git Hooks Readme](/git/readme.md) for more information.

### Acquia Cloud Hooks

Sample Acquia Cloud Hooks are included in the [hooks directory](/hooks). These
include example integrations for third party services such as Slack, New Relic,
and HipChat. See [hooks](/hooks/readme.md) for more information.

### Testing Framework

Bolt includes example tests and configurations for various testing tools,
 include Behat and PHPUnit. See [tests directory](/tests) for more information.
Tasks for executing these tests are included in the [continuous integration](#continuous-integration)
tools.

### Continuous Integration

A large number of common build tasks are provided via Phing targets. These 
include tasks for things like code sniffing, executing tests, building 
dependencies, installing Drupal, etc.

A starter configuration for running builds on Travis CI is included. The
configuration lives in [.travis.yml](/.travis.yml) and [build](/build). At a high level, the build
will do the following:
* Execute a Travis CI build when a Pull Request is submitted to GitHub.
  * Build dependencies (e.g., composer)
  * Run phing targets. Phing targets include:
    * validate:all         - runs code validation (e.g., code sniffer)
    * setup:build:make     - executes drush make on [make.yml]
    * setup:install:drupal - installs Drupal to Travis environment via `drush si`
    * tests:all            - executes Behat and PHPUnit tests against installed Drupal instance

### Local Development

Bolt does not ship with any local development environment configuration, but there are two recommended solutions, both of which can be used with Bolt-generated Drupal projects:

  - [Drupal VM](http://www.drupalvm.com/)
  - [Acquia Dev Desktop](https://www.acquia.com/products-services/dev-desktop)

Please read the included [Local Development](/readme/local-development.md) documentation for instructions for using Drupal VM with a Bolt-generated Drupal project.

## Directory Structure

The following is an overview of the purpose of each top level directory:

    root
      ├── bin     - Contains binaries built by Composer, as well as installation binaries.
      ├── build   - Contains build config files for CI solutions. E.g., Phing configuration.
      ├── docroot - The drupal docroot. Intentionally .gitignored. Created only during builds.
      ├── readme    - Contains high level project documentation.
      ├── git     - Contains configuration files that will be copied into a new project's .git directory upon installation.
      ├── hooks   - Contains Acquia Cloud hooks.
      ├── install - Contains Bolt configuration files. Removed from child projects.
      ├── patches - Contains private patches to be used by make.yml.
      ├── reports - Contains output of automated tests; is .gitignored.
      ├── scripts - Contains a variety of utility scripts that are not part of the build process.
      ├── tests   - Contains all test files and configuration.
      ├── vendor  - Contains built composer dependencies; is .gitignored.

# Contributing

Bolt work is currently being tracked in [Acquia's internal JIRA instance](https://backlog.acquia.com/browse/PPT).

[![Build Status](https://magnum.travis-ci.com/acquia/bolt.svg?token=eFBAT6vQ9cqDh1Sed5Mw&branch=7.x)](https://magnum.travis-ci.com/acquia/bolt)
