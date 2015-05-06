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

    This directory contains a variety of utility scripts that are not part of the build process. These scripts may do things like generate release notes, execute local tests, pull a fresh db to a local machine, etc.

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

