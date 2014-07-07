# PROJECT NAME

_QUICK PROJECT OVERVIEW_

## Documentation

- Link to Discovery Report
- Link to Architecture Document
- Links to other valuable documentation (Confluence pages, requirements docs, etc)


## Team 

- **Acquia Technical Architect:** TA NAME <<EMAIL@acquia.com>>
- **Acquia Engagement Manager:** EM NAME <<EMAIL@acquia.com>>
- **Development Lead:** DL NAME <<EMAIL@EMAIL.com>>
- **CLIENT-CONTACT:** NAME <<EMAIL@EMAIL.com>>
- ...


## Environments

<!-- Customize this section to ensure it matches your project's setup -->

### Source Control

<!-- Specify source control system (git/svn/etc) and any/all locations. Outline -->

### Dev

<!-- <http://INSERT_DEV_URL> -->

Used primarily for basic integration. The `dev` branch is deployed here and automatically updated. NOTE: This environment has a limited database that may not accurately represent issues found in production.


### Stage

<!-- <http://INSERT_STAGE_URL> -->

Used for internal testing by the development team and UAT . The current release branch is deployed here and automatically updated.


### Prod

<!-- <http://INSERT_PROD_URL> -->

The public-facing, production environment.



    
## Configurations

<!-- Any special setup overview -->

## Directory Structure

The overall working structure is made up of a few directories. The following
explains each of their purposes and example contents.

- **bin**

    Currently this directory contains only the scripts required for the build process.

- **conf**

    Some docroot-level files must be substituted atop a base Drupal installation. The contents of this directory will overwrite any of these files. This folder can contain subdirectories. 
    *Examples: .htaccess, robots.txt*

- **docs**

    The overall documentation is included within this directory.

- **scripts**

    The current script types are `prebuild` and `postbuild`. These run before Drush make operations and after, respectively. Within each directory, any number of scripts is supported. As long as the file is executable, it can also be of any language. For example -

        scripts/
        ├── postbuild/
        │   └── post.sh
        └── prebuild/
            └── pre.sh

- **sites**

    Each individual site (other than all/) should be contained within this directory. As this is copied into place for the new build, any files in this directory will be moved to the build. The entire directory is copied to the build, so custom modules or additional configuration files can be included here. In addition, any *.make files included in the top-level of each site folder will be executed. For example -

        sites/
        ├── default
        │   ├── default.make
        │   └── settings.php
        ├── example.com
        │   ├── example.com.make
        │   └── settings.php
        └── example.net
            ├── example.net.make
            └── settings.php

- **tests**

    All test files should be placed into this directory.
    

### Local Environment Setup

All code is developed from a [GitHub repository](https://github.com/acquia-pso/project-repo/). **No direct changes should be pushed to the Acquia repository.**

Each developer should [fork](https://help.github.com/articles/fork-a-repo) the primary Git repository for their work. All developers should then checkout a local copy of the `dev` branch to begin work -

    git clone git@github.com:username/project-repo.git -b dev
    git remote add upstream git@github.com:acquia-pso/project-repo.git

For any work, [pull requests](https://help.github.com/articles/using-pull-requests) must be created for individual tasks and submitted for review -

    git checkout dev
    git pull upstream dev
    git push origin dev
    git checkout -b <new-issue-branch> dev
    
    
### External Systems

<!-- Customize this section with details on additional configurations that are required to develop -->

- SSO
- VPN
- etc


### External Tools

<!-- Call out all external tools in use here and any relevant usage specifics-->

- Compass
- Grunt
- Behat
- etc

    

## Workflow

<!-- Customize the workflow details to match the agreed upon workflow for this project-->


## Testing Requirements

<!-- Customize this section to cover testing requirements; include details on automated code review processes, behat integration, unit testing, etc. -->

Each developer is expected to fully test their own code according to the following standards before submitting a pull request. 

    
## Deployment

<!-- Customize this section based on the client's deployment. Specifically, How Github and Acquia's repo are kept in sync.  -->


### Releases

<!-- Specify how releases are tagged and deployed. Include any specific rollback processes as well. -->


### Hotfixes

<!-- Specify the process for creating and deploying hotfixes. -->



