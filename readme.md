# Project Name

The [Website Description](http://website.com/) is a type of site.

## Local Environment Setup

All code is developed from a [GitHub repository](https://github.com/acquia-pso/project-repo/). **No direct changes should be pushed to the Acquia repository.**

Each developer should [fork](https://help.github.com/articles/fork-a-repo) the primary Git repository for their work. All developers should then checkout a local copy of the `dev` branch to begin work -

    git clone git@github.com:username/project-repo.git -b dev
    git remote add upstream git@github.com:acquia-pso/project-repo.git

For any work, [pull requests](https://help.github.com/articles/using-pull-requests) must be created for individual tasks and submitted for review -

    git checkout dev
    git pull upstream dev
    git push origin dev
    git checkout -b <new-issue-branch> dev

## Deployment

The deployment process follows a simplified Gitflow workflow -

1. All pull requests are submitted against the `dev` branch.
2. All recent changes are merged to a release branch (e.g. `releases/sprint/11`) via a PR, then deployed to the staging environment.
3. After client testing, successful changes are merged to the `master` branch via a PR, tagged, then deployed to the production environment.

The GitHub repository is automatically synced to the Acquia repository through a background Jenkins process. Tags, however, are not managed automatically and must be pushed manually -

    git remote add acquia docrootname@server.hosting.acquia.com:project-repo.git
    git push acquia --tags

### Releases

Releases are tagged with the branch name, current date, and release number -

    <branch>-<date>-<release #>

1. The *branch* is the branch from which this tag was created, e.g. `uat`.
2. The *date* is the current date in `Y-m-d` format, e.g. `2014-04-12`.
3. The *release #* is an incremented number to represent multiple tags created on a specific date, starting at `1`.

### Hotfixes

Any emergency bug fixes can be pushed to the appropriate branch (usually `uat` or `master`) and deployed directly to the required environment.

### Environments

- Dev - Used primarily for basic integration. The `dev` branch is deployed here and automatically updated. NOTE: This environment has a limited database that may not accurately represent issues found in production.
- Stage - Used for internal testing by the development team. The current release branch is deployed here and automatically updated.
- Perf - Temporarily created for performance testing. This will likely be deleted in the future.
- RA
- Prod - The public-facing, production environment.

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