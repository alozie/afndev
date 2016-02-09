# Repository architecture

“How is the code organized, and why?”

The repository architecture is driven by a set of core principles:

* Project dependencies should never be committed directly to the repository
* The code that is deployed to production should be fully validated, tested, 
  sanitized, and free of non-production tools
* Common project tasks should be fully automated and repeatable, independent of 
  environment

Consequently, there are a few aspects of this project’s architecture and 
workflow that may be unfamiliar to you.

* Drupal core, contrib modules, themes, and third parties libraries are not 
  committed to the repository. Contrib directories .gitignored and populated
  during [build artifact](deploy.md) generation.
* The repository is never pushed directly to the cloud. Instead, changes to the
  repository on GitHub trigger tests to be run via [Continuous Integration]
  (../build/README.md#ci). Changes that pass testing will automatically cause a 
  [build artifact](deploy.md) to be created and deployed to the cloud.
* [Common project tasks](project-tasks.md) are executed via a build tool (Phing)
  so that they can be executed exactly the same in all circumstances.

## Directory structure

The following is an overview of the purpose of each top level directory in the
project template:

    root
      ├── build    - Contains build config files for CI solutions. E.g., Phing configuration.
      ├── docroot  - The drupal docroot. Intentionally .gitignored. Created only during builds.
      ├── hooks    - Contains Acquia Cloud hooks.
      ├── patches  - Contains private patches to be used by make.yml.
      ├── profiles - Contains custom profiles; symlinked to docroot/profiles.
      ├── readme   - Contains high level project documentation.
      ├── reports  - Contains output of automated tests; is .gitignored.
      ├── scripts  - Contains a variety of utility scripts that are not part of the build process.
      ├── sites    - Subdirectories within sites are symlinked to /docroot/sites.
      ├── tests    - Contains all test files and configuration.
      ├── vendor   - Contains built composer dependencies; is .gitignored.

## Dependency Management

All dependencies are managed via [Drush Make](http://www.drush.org/en/master/make/).

For step-by-step instructions on how to update dependencies, see [Project Tasks]
(project-tasks.md#update-dependency).
