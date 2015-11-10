# Generate Release Notes Script

## Overview
Use a script compiles PR comments for a project into a Markdown file that can
be copy and pasted into github release notes. This script is hosted in the 
Acquia PSO [bolt](https://github.com/acquia-pso/bolt/blob/7.x/scripts/release-notes/generate-release-notes.php) repository.

## Usage

### Inputs
- **username:** your github username
- **password:** your github password
- **repository:** the name of the github repository (e.g. `https://github.com/acquia-pso/my-repo`)

### Simple usage

    php generate-release-notes.php myname mypassword my-repo > release-notes.md

### Specify a start date

    php generate-release-notes.php myname mypassword my-repo 1 30 2014 > release-notes.md

### Specify a start date and number of PRs

    php generate-release-notes.php myname mypassword my-repo 1 30 2014 50 > release-notes.md

    # Example: Commit Message

In order to automatically [integrate with Jira](https://confluence.atlassian.com/display/AOD/Processing+JIRA+issues+with+commit+messages) via commit messages, a specific message format needs to be used -

# Example: Pull Request

    ## [Jira Issue Number]: [Pull request title]

    ### Changes
    - [Description for non-developers.]
    - [Another thing in the PR]

    ### Technical Details
    - [More technical description if needed.]

    ### Affected Pages
    - [Globally]
    - [example url]
    - [/product-finder/123]

    ### Key Affected Code
    - [example.php]
    - [example.scss]

    ### References
    - [drupal.org/node/123456]

    ### Notices
    - [If there is an important change developers should be aware of such as a change in architecture, the need for database updates, etc, put that information here.]

# Example: Release

    ## Main Additions
    - Addition 1
    - Addition 2

    ## Important Notices
    - Notice 1
    - Notice 2

    - - -
    # Pull Requests in Detail
    - - -

    _This is where you copy and paste the output from generate-release-notes.php_

