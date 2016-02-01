# Bolt

Bolt will create a new Professional Services project using the project template located in /template.

It is proprietary software. Copyright 2016. Acquia, Inc.

## Philosophy and Purpose

Bolt is designed to improve efficiency and collaboration across Drupal projects 
by providing a common set of tools and standardized structure. It was born out 
of the need to reduce re-work, project set up time, and developer onboarding 
time.

Its explicit goals are:

* Provide a standard project template for all Drupal based projects
* Provide tools that automate much of the setup and maintenance work for 
  projects
* Document and enforce Drupal standards and best practices via default 
  configuration, automated testing, and continuous integration

It scope is discretely defined. It is not intended to provide:

* Drupal application features (e.g., workflow, media, layout, pre-fabbed content
  types, etc.)
* A local hosting environment
* A replacement for good judgement (as with Drupal, it leaves you the freedom to
  make mistakes)

## Creating a new project with Bolt

It isn’t accurate to say that you can “install” Bolt. Rather, you can use Bolt 
to generate a new project. Within that project, you can then do common project 
tasks like build dependencies, install Drupal, run tests, etc.

Follow the instructions in [INSTALL.md](INSTALL.md) to “install” Bolt.

## Features

* Documentation templates
    * Project Readme
    * Onboarding
    * Developer Guide
    * Architecture Template
    * Open Source Contribution Guide
    * Theming Guide
    * Local Development
* Git Hooks
    * pre-commit: Checks for Drupal coding standards compliance
    * commit-msg: Check for proper formatting and syntax
* Acquia Cloud Hooks. Example integrations for third party services such as
    * Slack
    * New Relic
    * HipChat
* Testing Framework. 
    * Behat: default local.yml configuration, example tests, FeatureContext.php
    * PHPUnit: default tests for ensuring proper functioning of Bolt provided components
* Project tasks
    * Executing tests and validating code
    * Building dependencies
    * (Re)installation of Drupal
    * Production-safe artifact generation and deployment
* Continuous Integration
    * Travis CI
    * GitHub


# Contributing to Bolt

Bolt work is currently being tracked in the Bolt GitHub issue queue.

## Status:

* Bolt: [![Bolt Build Status](https://magnum.travis-ci.com/acquia/bolt.svg?token=eFBAT6vQ9cqDh1Sed5Mw&branch=8.x)](https://magnum.travis-ci.com/acquia/bolt)
* Bolted8: [![Bolted8 Build Status](https://travis-ci.com/acquia-pso/bolted8.svg?token=eFBAT6vQ9cqDh1Sed5Mw&branch=8.x-build)](https://magnum.travis-ci.com/acquia-pso/bolted7)

See [build/README.md](build) for more information on Bolt's CI process.
