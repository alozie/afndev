# PROJECT Template

This repository is intended be be used as a template for all new Professional Services projects.

## Installation

When beginning a new Drupal project, do the follow:

* Clone the Project Template to a local directory
* Follow in the instructions in /install/readme.md. This will create a repository on your local machine for the new project, and handle building dependencies.

## Directory Structure

The overall working structure is made up of a few directories. The following
explains each of their purposes and example contents.

- **bin**

    Currently this directory contains only the scripts required for the build process.

- **conf**

    This directory contains site-level configuration files.
    _Examples: .htaccess, robots.txt, *.settings.php_

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

    Each individual site (other than all/) should be contained within this directory. Example:

        sites/
        ├── default
        │   └── settings.php
        └── example.com
            └── settings.php

- **tests**

    All test files should be placed into this directory.
    
        tests/
            ├── behat
            │   ├── behat.yml
            │   └── features
            │   │      └── Example.feature
            │   └── media
            ├── jmeter

