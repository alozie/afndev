# ${project.human_name}

* Overview
    * [Description](#description)
    * [Project Architecture](#architecture)
* Project Setup
    * [Onboarding](#onboarding)
    * [Directory Structure](#dir-structure)
* Development Standards and Best Practices
    * [Backend Development](#backend)
    * [Frontend Development](#frontend)
    * [Automated Testing](#testing)
* Workflow
    * [Agile Process](readme/agile-guide.md)
    * [Development Workflow](readme/dev-workflow.md)
    * [Continuous Integration](#ci)
    * [Release Process](#release-process)
    * [Open Source Contribution](readme/os-contribution.md)

## Overview

### <a name="description"></a>Description

Replace this with a brief description of the ${project.human_name} project.

### <a name="architecture"></a>Project Architecture

The application architecture is documented in the [Project Architecture]
(readme/architecture.md). This includes an overview of things like:
 * Infrastructure
 * High level technical requirements
 * Deployment strategy
 * User Roles
 * Content architecture
 * Etc.

## Project Setup

### <a name="onboarding"></a>Onboarding

Please see [Onboarding](readme/onboarding.md) for detailed information. This
will provide instructions for things like:
 * Gaining access to required resources 
 * Configuring your local environment to begin project work

## <a name="dir-structure"></a>Directory Structure

The following is an overview of the purpose of each top level directory in the 
project template:

    root
      ├── bin     - Contains binaries built by Composer, as well as installation binaries.
      ├── build   - Contains build config files for CI solutions. E.g., Phing configuration.
      ├── docroot - The drupal docroot; is .gitignored. Created only during builds.
      ├── readme  - Contains high level project documentation.
      ├── git     - Contains configuration files that will be copied into a new project's .git directory upon installation.
      ├── hooks   - Contains Acquia Cloud hooks.
      ├── install - Contains Bolt configuration files. Removed from child projects.
      ├── modules - Contains custom and contrib modules. Symlinked to docroot/modules.
      ├── patches - Contains private patches to be used by make.yml.
      ├── reports - Contains output of automated tests; is .gitignored.
      ├── scripts - Contains a variety of utility scripts that are not part of the build process.
      ├── tests   - Contains all test files and configuration.
      ├── themes  - Contains custom and contrib themes. Symlinked to docroot/themes.
      ├── vendor  - Contains built composer dependencies; is .gitignored.

## Development Standards and Best Practices

### <a name="backend"></a>Backend Development

All work must conform to established best practices and code standards. Code
quality is ensured in a variety of ways:

1. All backend code must conform to [Drupal Coding 
Standards](https://www.drupal.org/coding-standards). This is enforced via 
local [git hooks](scripts/git-hooks) and code checks performed during 
[continuous integration](build/README.md).
1. All code must be reviewed by a peer or established integrator before being
merged into the master branch. See [development workflow]
(readme/dev-workflow.md) for more information.
1. Inclusion of automated tests that mirror acceptance criteria.

Please peruse the [examples](examples/README.md) directory for examples 
of various coding best practices.

### <a name="frontend"></a>Frontend Development

Please see [Drupal Theming Best Practices](theming.md) for detailed information.

### <a name="testing"></a>Automated Testing

Proper usage of automated testing is a cornerstone of sound software 
development. 

We follow a [Behavioral Driven Development[(http://guide.agilealliance.org/guide/bdd.html)
methodology to ensure that all acceptance criteria for all
user stories are closely mapped to automated tests. This both ensures 
accurate delivery of functionality and prevents regressions. BDD is 
implemented via the use of [Behat](http://docs.behat.org/) in combination 
with the [Behat Drupal Extension](https://www.drupal
.org/project/drupalextension).

Additionally, we use [PHPUnit](https://phpunit.de/) to implement [Unit 
Testing](https://en.wikipedia.org/wiki/Unit_testing) and [Functional testing]
(https://en.wikipedia.org/wiki/Functional_testing) in scenarios where Behat 
is not the proper testing tool for a given feature.

For more information our our testing architecture, see the [tests]
(tests/README.md) directory.

## <a name="ci"></a>Continuous Integration

Tests may be executed either locally, in the cloud, or via our Continuous 
Integration solution. Please see (build/README.md) for more information on 
executing testing and Continuous Integration.

## <a name="workflow"></a>Workflow

The project workflow is described in the following two documents:

* [Agile Workflow](agile-guide.md) - Outlines the organization of project work
  within JIRA and the proper flow of tickets from state to state.
* [Development Workflow](dev-workflow.md) - Outlines the technical tasks
  required for a developer to complete work on a given ticket from beginning
  to end.

### <a name="release-process"></a>Release Process

Please see [Release Process](/release-process.md) for detailed information.


----------------

[![Build Status](https://magnum.travis-ci.com/acquia-pso/${project.acquia_subname}.svg?token=eFBAT6vQ9cqDh1Sed5Mw&branch=${git.default_branch})](https://magnum.travis-ci.com/acquia-pso/${project.acquia_subname})
