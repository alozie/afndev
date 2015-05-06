# PROJECT Template

This repository is the template for all new Professional Services projects.

## Installation

When beginning a new Drupal project, do the following:

* Clone this Project Template reposistory to a local directory
* Follow the instructions in /install/readme.md. This will create a repository on your local machine for the new project, and handle building dependencies.

## Directory Structure

The overall working structure is made up of a few directories. The following
explains each of their purposes and example contents.

- **bin**

    This directory contains scripts required for the build process.

- **conf**

    This directory contains site-level configuration files.
    _Examples: .htaccess, robots.txt, *.settings.php_

- **docs**

    This directory contains overall documentation.

- **scripts**

    This directory contains a variety of utility scrips that are not part of the build process. The current script types are `prebuild` and `postbuild`. These run before Drush make operations and after, respectively. Within each directory, any number of scripts is supported. As long as the file is executable, it can also be of any language. For example -

        scripts/
        ├── postbuild/
        │   └── post.sh
        └── prebuild/
            └── pre.sh

- **sites**

    This directory contains each individual site (other than all/). Example:

        sites/
        ├── default
        │   └── settings.php
        └── example.com
            └── settings.php

- **tests**

    This directory contains all test files.

        tests/
            ├── behat
            │   ├── behat.yml
            │   └── features
            │   │      └── Example.feature
            │   └── media
            ├── jmeter

