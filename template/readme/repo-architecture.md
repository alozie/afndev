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

@todo Add directory structure.

## Dependency Management

@todo Document this.

For step-by-step instructions on how to update dependencies, see [Project Tasks]
(project-tasks.md).
