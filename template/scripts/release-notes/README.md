# Generate Release Notes Script

## Overview
Use a script compiles PR comments for a project into a Markdown file that can
be copy and pasted into github release notes. This script is hosted in the
Acquia PSO [bolt](generate-release-notes.php) repository.

## Usage

### Inputs
- **username:** your github username
- **password:** your github password
- **repository:** the name of the github repository (e.g. `https://github.com/acquia-pso/my-repo`)

### Simple usage

    php generate-release-notes.php github username:password org:repo-name > release-notes.md

### Specify a start date

    php generate-release-notes.php github username:password org:repo-name 1/30/2014 > release-notes.md

### Specify a start date and number of PRs

    php generate-release-notes.php github username:password org:repo-name 1/30/2014 50 > release-notes.md

    # Example: Commit Message
