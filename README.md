# Bolt

Bolt will create a new Professional Services project using the project template 
located in `/template`.

It is proprietary software. Copyright 2016. Acquia, Inc.

## Philosophy and Purpose

Bolt is designed to improve efficiency and collaboration across Drupal projects 
by providing a common set of tools and standardized structure. It was born out 
of the need to reduce re-work, project set up time, and developer onboarding 
time.

Its explicit goals are to:

* Provide a standard project template for Drupal based projects
* Provide tools that automate much of the setup and maintenance work for 
  projects
* Document and enforce Drupal standards and best practices via default 
  configuration, automated testing, and continuous integration

It scope is discretely defined. It is *not* intended to provide:

* Drupal application features (e.g., workflow, media, layout, pre-fabbed content
  types, etc.)
* A local hosting environment
* A replacement for good judgement (as with Drupal, it leaves you the freedom to
  make mistakes)

## Creating a new project with Bolt

It isn’t accurate to say that you can “install” Bolt. Rather, you can use Bolt 
to generate a new project. Within that project, you can then perform common 
project tasks like build dependencies, install Drupal, run tests, etc.

Follow the instructions in [INSTALL.md](INSTALL.md) to generate a new project 
using Bolt.

## Features

* [Documentation templates](template/README.md)
* [Git Hooks](template/scripts/git-hooks)
    * pre-commit: Checks for Drupal coding standards compliance
    * commit-msg: Check for proper formatting and syntax
* [Acquia Cloud Hooks](template/hooks). Example integrations for third party services such as:
    * Slack
    * New Relic
    * HipChat
* [Testing Framework](template/testing). 
    * Behat: default `local.yml` configuration, example tests, `FeatureContext.php`
    * PHPUnit: default tests for ensuring proper functioning of Bolt provided components
* [Project tasks](template/readme/project-tasks.md)
    * Executing tests and validating code
    * Building dependencies
    * (Re)installation of Drupal
    * Production-safe artifact generation and deployment
* [Continuous Integration](template/build/README.md)
    * Travis CI
    * GitHub

## Keeping Bolt projects up-to-date

"How do I pull down upstream changes from Bolt to my Bolt-generated project?"

This is a popular question, and it's difficult to answer. 

Bolt is designed as a "starter kit" rather than a "distribution". It 
intentionally began with a "fork it and forget it" approach to updates. This is
largely due to the fact that Bolt generated files are templates that are meant 
to be customized, and pulling in upstream updates would wipe out those 
customizations.

That said, there are components of Bolt that could be treated as dependencies
that receive upstream updates. Those components include:

* Project tasks
* Scripts
* Acquia Cloud hooks

The ideal approach would be to split each of these into a separate, versioned
projects that could be treated as formal composer.json dependencies, but we 
don't currently have the resources to maintain all of those projects.

As a stopgap, you can run the following command to pull in upstream updates to 
specific files and directories in your Bolt generated project:

`./task.sh setup:bolt:update`

After running, you can review changes via `git diff` and decide what should be
committed.

# Contributing to Bolt

Bolt work is currently being tracked in the Bolt GitHub issue queue.

## Status:

* Bolt: [![Bolt Build Status](https://magnum.travis-ci.com/acquia/bolt.svg?token=eFBAT6vQ9cqDh1Sed5Mw&branch=7.x)](https://magnum.travis-ci.com/acquia/bolt)
* Bolted7: [![Bolted7 Build Status](https://travis-ci.com/acquia-pso/bolted7.svg?token=eFBAT6vQ9cqDh1Sed5Mw&branch=7.x-build)](https://magnum.travis-ci.com/acquia-pso/bolted7)


See [build/README.md](build) for more information on Bolt's CI process.
