# Bolt

Bolt will create a new Professional Services project using the project template
located in /template.

## Table of Contents

* [Installation](#installation)
* [Features](#features)
  * [Documentation Templates](#documentation-templates)
  * [Git Hooks](#git-hooks)
  * [Acquia Cloud Hooks](#acquia-cloud-hooks)
  * [Testing Framework](#testing-framework)
  * [Continuous Integration](#continuous-integration)
* [Contributing to Bolt](#contributing-to-bolt)

## Installation

When beginning a new Drupal project, do the following:

* Clone this Bolt repository to a local directory.
* Follow the instructions in [/install/README.md](/install/README.md).

## Features

### Documentation Templates

The following default documentation is included in the project template:
* [Onboarding](/template/readme/onboarding.md)
* [Developer Guide](/template/readme/dev-workflow.md)
* [Architecture Template](/template/readme/architecture.md)
* [Open Source Contribution Guide](/template/readme/os-contribution.md)
* [Project Readme](/template/README.md)
* [Theming Guide](/template/readme/theming.md)
* [Local Development](/template/readme/local-development.md)

### Git Hooks

Default [git hooks](https://git-scm.com/book/en/v2/Customizing-Git-Git-Hooks)
are included with Bolt. These should be symlinked into your local repository's 
`.git` directory using the `setup:git-hooks` task during the 
[onboarding process](/template/readme/onboarding.md). 

Please see the [Git Hooks Readme](/template/scripts/git-hooks/README.md) for
more information.

### Acquia Cloud Hooks

Sample Acquia Cloud Hooks are included in the [hooks directory](/template/hooks). These
include example integrations for third party services such as Slack, New Relic,
and HipChat. See [hooks](/template/hooks/README.md) for more information.

### Testing Framework

Bolt includes example tests and configurations for various testing tools,
including Behat and PHPUnit. See [tests directory](/template/tests) for more 
information. Tasks for executing these tests are included in the 
[continuous integration](#continuous-integration) tools.

### Continuous Integration

A large number of common build tasks are provided via Phing targets. These 
include tasks for things like code sniffing, executing tests, building 
dependencies, installing Drupal, etc.

A starter configuration for running builds on Travis CI is included. The
configuration lives in [.travis.yml](/template/.travis.yml) and [build](/template/build). 
At a high level, the default CI build will do the following:
* Execute a Travis CI build when a Pull Request is submitted to GitHub.
  * Build dependencies (e.g., composer)
  * Run phing targets. Phing targets include:
    * validate:all         - runs code validation (e.g., code sniffer)
    * setup:build:all      - executes drush make on [make.yml](/install/example.make.yml)
    * setup:drupal:install - installs Drupal to Travis environment via `drush si`
    * tests:all            - executes Behat and PHPUnit tests against installed Drupal instance
    * deploy:*             - commits and pushed build artifact to Acquia Cloud for deployment

### Local Development

Bolt does not ship with any local development environment configuration, but 
there are two recommended solutions, both of which can be used with 
Bolt-generated Drupal projects:

  - [Drupal VM](http://www.drupalvm.com/)
  - [Acquia Dev Desktop](https://www.acquia.com/products-services/dev-desktop)

Please read the included [Local Development](/template/readme/local-development.md)
documentation for instructions for using Drupal VM with a Bolt-generated Drupal 
project.

# Contributing to Bolt

Bolt work is currently being tracked in the Bolt GitHub issue queue.

## Status:

* Bolt: [![Bolt Build Status](https://magnum.travis-ci.com/acquia/bolt.svg?token=eFBAT6vQ9cqDh1Sed5Mw&branch=7.x)](https://magnum.travis-ci.com/acquia/bolt)
* Bolted7: [![Bolted7 Build Status](https://travis-ci.com/acquia-pso/bolted7.svg?token=eFBAT6vQ9cqDh1Sed5Mw&branch=7.x-build)](https://magnum.travis-ci.com/acquia-pso/bolted7)

See [build README.md](/build) for more information on Bolt's CI process.
