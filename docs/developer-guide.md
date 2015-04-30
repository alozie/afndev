# Acquia Professional Services Developer Guide

## Intended Audience

- Acquia PS Technical Consultants and Technical Architects
- Partner Developers
- Acquia Technical Account Managers working on PS led projects

## Overview

This guide contains best practices in the form of examples and guidelines. It is meant both to onboard new developers and as a reference tool.

## How to Read

This repository is formatted for reading via GitBook, which is easy to install and use:

1. Install npm (`brew install npm`) if it's not already present.
2. Install `gitbook` (`npm install gitbook -g`).
3. From within the directory with `book.json`, run the command `gitbook serve`.

GitBook will output a URL you can use to read the book in a web browser.

### Documentation format

This guide is written in Markdown and hosted on Github. It is written in Markdown because Markdown has awesome support for code highlighting and is pretty easy to read as raw text and on Github. We are hosting this on Github so we can easily collaborate and because out of the box, Github supports displaying markup as HTML when you browse the repository.

see: [https://help.github.com/articles/github-flavored-markdown](https://help.github.com/articles/github-flavored-markdown)

## Who writes this?

This is a collaborative document written by Acquia's Professional Services organization. If you are a member of PS, please feel free to put in a pull request if you think your changes / additions are controversial, or just commit if they aren't.

If you are a partner developer and see some areas for improvement, any comments, questions, and additional writing is appreciated.

# Setup

## Development Environment

We recommend using [Acquia Dev Desktop](https://docs.acquia.com/dev-desktop2) for Windows/Mac development. No matter what local environment you choose to use, the following guidelines should be followed -

* In order to guarantee similar behavior, use Apache as your web server.
* If your project is hosted on Acquia Cloud, please ensure to match [our software versions](https://docs.acquia.com/cloud/arch/tech-platform).

Acquia developers use [PHPStorm](http://www.jetbrains.com/phpstorm/) and recommend it for local development environments. Acquia has written [several articles](https://docs.acquia.com/search/site/phpstorm) on effectively using PHPStorm for Drupal development.

## Frontend Development

*TODO: Add JSHint snippet*

## Git

For readability of commit history, set your name properly -

    git config user.name "Developer"

*You likely want to do this globally using `git config --global`.*

Ensure that your local email address correctly matches the email address for your Jira account -

    git config user.email developer@example.com

Although the workflow does not use Branch-per-Feature, we use  the [`git-bpf` gem](https://github.com/affinitybridge/git-bpf) to more easily manage merge conflicts within long-running branches -

    gem install git_bpf

### GitHub

In order to more easily identify developers in a project, please be sure to set a name and profile picture in your GitHub profile.

When working with GitHub, the [hub](https://github.com/github/hub) utility can be helpful when managing forks and pull requests. Installing hub largely depends on your local environment, so please follow the [installation instructions](https://github.com/github/hub#installation) accordingly.

# Code Management
# Git Workflow

When hosting with Acquia, all code is typically developed in a [GitHub repository](https://github.com/acquia-pso). No direct changes should be pushed to the Acquia repository. The process of syncing these repositories is managed transparently in the background.

The recommended workflow resembles a [Gitflow Workflow](https://www.atlassian.com/git/workflows#!workflow-gitflow) with the follow specifics -

* All development is performed against a `develop` branch.
* Completed features are merged into a `release` branch until a new release needs to be made. Additional QA testing should be made against this branch and fixed inline, if needed.
* Each commit to the `master` branch is tagged with a release number, named either based on sprints (e.g. `24.0`) or date (e.g. `2014-08-19.0`).
  * **In order to maintain consistency between repositories, no tags should be created directly within the Acquia repository.**
* Any hotfixes are merged directly into a `hotfix` branch, which can then be merged to `master`.

![Gitflow Workflow](https://www.atlassian.com/git/workflows/pageSections/00/contentFullWidth/0/tabs/02/pageSections/010/contentFullWidth/0/content_files/file0/document/git-workflow-release-cycle-4maintenance.png) *Credit: Atlassian*

## Git Resources


* [GitHub Help](https://help.github.com/)
* [Git Reference](http://gitref.org/)
* [Pro Git](http://www.git-scm.com/book)
* [How to use a scalable Git branching model called Gitflow [BuildAModule]](http://buildamodule.com/video/change-management-and-version-control-deploying-releases-features-and-fixes-with-git-how-to-use-a-scalable-git-branching-model-called-gitflow)

# Git Workflow: Developer

Each developer should setup a GitHub account that follows several key guidelines -

* Provide a real user name in their profile
* Links to the email address used to login to Jira

## Project Setup

Each developer should [fork](https://help.github.com/articles/fork-a-repo) the primary Git repository for their work. All developers should then checkout a local copy of the master branch to begin work -

    git clone git@github.com:username/project-repo.git -b master
    git remote add upstream git@github.com:acquia-pso/project-repo.git

For any work, pull requests must be created for individual tasks and submitted for review. Before submitting a pull request, be sure to [sync the local branch](https://help.github.com/articles/syncing-a-fork) with the upstream primary branch -

    git checkout master
    git pull upstream master
    git push origin master
    git checkout -b <new-issue-branch> master

## Workflow

### Creating a PR

Using [Hub](https://github.com/github/hub), it is very easy to create a new PR based on the current feature -

    hub pull-request

In order to enforce consistency on a project, a pull request template can also be configured using `hub` -

    git config --global --add hub.pull-request-template-path ~/.pr-template

### Cleaning a PR

If you created many small commits locally while working through a ticket, you may want to clean the history to be more understandable later. You can combine these commits using `git rebase` (**only if no one else has checked out your fork**) -

    git rebase -i upstream/master

# Git Workflow: Integration

Two versions of the integration workflow are recommended -

1. Integration manager
1. Peer review

**In either workflow, no one should ever commit their own code to the primary working branch.**

## Integration Manager

![Integration manager workflow](http://git-scm.com/figures/18333fig0502-tn.png)
*Source: Pro Git*

This model requires one (or more) lead developers to take the responsibility of merging all pull requests. This ensures consistency in quality control as well as identifying any potential issues with related, open pull requests.

A small group of one or more person(s) is selected to be integrators. All commits are reviewed by this group. If work is done by an integrator, their work should be reviewed by a fellow integrator (as if they were a developer).

## Peer Review

This model removes the bottleneck of designated integrators, but still eliminates commits directly to the working branch. In short, every commit is reviewed by a developer other than the one submitting the original commit.

## Workflow

### Testing a PR Locally

Using [Hub](https://github.com/github/hub), it is very easy to merge a PR locally without adding another remote or messing with branches. For example, using the URL used to view a pull request, simply run `hub checkout` to test these changes locally -

    hub checkout https://github.com/acquia-pso/project-template/pull/1

Alternatively, you can use `hub merge` to merge the changes -

    hub merge https://github.com/acquia-pso/project-template/pull/1

*NOTE: This command implies the `--no-ff` flag like GitHub uses when merging pull requests using the web interface.*

# Generate Release Notes Script

## Overview
Use a script compiles PR comments for a project into a Markdown file that can be copy and pasted into github release notes. This script is hosted in the Acquia PSO [project-template](https://github.com/acquia-pso/project-template/blob/master/scripts/generate_release_notes.php) repository.

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

# Backend

# php

## Coding Standards

All Drupal php code should conform to the [Drupal Coding Standards](https://www.drupal.org/coding-standards).

# Install Profiles

The general idea behind using an install profile while developing a Drupal site is that a distributed/multi-member development team can:
1. develop a site without relying on a centralized database, and
2. not worry about polluting the database with items while in the midst of developing.

## What this means in practice.

Before a site goes "live" every instance of the Drupal site will be installed from its install profile into a blank database. This installation will occur on every local development environment and on all remote servers. Every time a material change occurs to the codebase a new site-install will be performed.

## How is this done?

1. All site configuration will be made in code. This means a heavy reliance on the features module.
2. After a site install all features should be in the default state.
3. The dependencies of the site, and therefore the modules enabled on install, will be placed in the .info file of the profile or within a module's .info file.

**Reference**

https://www.drupal.org/developing/distributions


# Post “go-live”

This install practice will only work up to a certain point. After the site is "live" [update hooks](https://api.drupal.org/api/drupal/modules%21system%21system.api.php/function/hook_update_N/7) will need to be used to update the database with any new configuration. In practice, these update hooks should be placed in the specific module that they pertain to (or the install profile itself if the update is larger than just one module).

## Definition

Going “live” is when the production database is the authoritative source for content/configuration. Once "live" a site's database can no longer be discarded.

# Features

[Features](http://www.drupal.org/project/features) modules are like any other custom module. The only difference is that some or all of the configuration stored in the module is machine generated. As such, Features modules should be stored in the same location as all of your other custom modules.

**example**
profiles/[PROFILE NAME]/modules/custom

## Best Practices

* All Features should contain their appropriate dependencies.
* Keep Features semantic.
  * For example a Basic Page feature may contain:
    * A basic page content type,
    * A view displaying basic pages for admin purposes,
    * Permissions surrounding the CRUD of the basic page,
    * A migration of basic page content into the site.
* Use your Feature module to hold more than just machine generated configuration.

# Theming

See [torq](https://github.com/torq)'s preliminary guide, [Drupal Theming Best Practices](https://github.com/torq/Drupal-Theming-Best-Practices)

# Frontend Set Up

Frontend development tools have become increasingly complex. Coding standards are not enough, we need development tool standards. Consistent set-ups allow us to jump-start development partners and avoid spending time troubleshooting tools instead of the project.

## Tools used

### Ruby
#### About
Programming language used to run Compass & SASS.

#### Ruby Version Manager
We are using Ruby Version Manager (RVM) to manage Ruby. This allows projects to use different versions of Ruby and Gems without causing issues.

**Information**
[http://rvm.io](http://rvm.io)

**Installation**
(on OSX, you need to install Xcode and Xcode's command line tools first)

`\curl -sSL https://get.rvm.io | bash -s stable`

*You can specify a specific version of Ruby for a project*
`\curl -sSL https://get.rvm.io | bash -s stable --ruby=1.9.3`

##### Ruby Version File
Inside your theme folder, you will create a `.ruby-version` file. The contents of the file is a single line with the version of Ruby you are using such as `1.9.3`.

This ensures all developers are using the same version of Ruby.

##### Ruby Gemfile
Inside your theme folder, you will create a `Gemfile` file. This file contains all the Gems (libraries) and supported versions of those libraries for the project.

**Example**
```
source 'https://rubygems.org'

group :development do

  # Sass, Compass and extensions.
  gem 'sass', '3.2.14'              # Sass.
  gem 'sass-globbing', '1.1.0'      # Import Sass files based on globbing pattern.
  gem 'compass', '0.12.2'           # Framework built on Sass.

end
```

##### Ruby Gemfile Lock
Once you install your Ruby gems, you will have a `Gemfile.lock` in your theme folder. This specifies the exact versions of gems to use. You should include this in your projects repository.


### SASS, Compass & Bundler
#### About
SASS is an extension of CSS that allows for functions and nesting amoung other features. It is compiled into CSS by programs like Compass. Bundler is a dependency and gem environment manager. All gems are maintained and updated via bundler.

##### Compass Configuration File
You should create a `config.rb` file inside your theme folder. This file will contain all of the site specific configuration for Compass.

##### Install
`gem install bundler`

### Node.js
#### About
Node.js enables programs written in javascript to run locally as applications. It is required for Grunt and Bower.

#### Install
Installers at [http://nodejs.org](http://nodejs.org)

### Grunt
#### About
Grunt is an automation and task running tool. We are using it to download libraries and compile SASS to CSS in addition to other automation.

#### Install
`npm install -g grunt-cli`

#### Package.json
You should create a `package.json` file in your theme folder. This file is used to describe all the Node.js libraries such as Grunt.

**Example**

```
{
  "name": "Base Theme",
  "description": "Default theme for the Brand Site",
  "version": "1.0.0",
  "devDependencies": {
    "grunt": "~0.4.0",
    "grunt-bless": "~0.1.1"
  }
}
```

## Running the build scripts
These scripts will download the necessary modules to your local setup. They should be run from the brand theme folder `docroot/sites/mybrand/themes/brand_theme`. They will then prepare the brand's theme by renaming the folder and files as necessary.

- `npm install`
- `bundle install`
- `grunt`

### Grunt contib task documentation
[https://github.com/gruntjs](https://github.com/gruntjs)

# Agile Guide

## Organization of Epics / Stories / Tasks

Work should be organized into Epics, Stories, and Tasks. These categories match the Acquia Jira configuration and many common set-ups.

### Epic

An Epic is a high level task such as "Product Ratings and Reviews". Depending on the project, the level of detail for an epic may vary. However, this can be seen as a category or larger task. Development work is not done directly against the Epic, it is just a place to describe the big picture.

An Epic is targeted at the client.

### Story

A Story is the basic unit of work for a project. Each Story should be independent in that it can be developed and tested by itself. Stories should also focus on the site user when possible. As such, each Story should define in a single short sentence exactly one thing that a site user would do, such as "As a consumer, when I view a product page, I can view product reviews."

The Story structure defines who is performing a task ("a consumer"), the context of the task ("when I view a product page"), and the task itself ("I can view product reviews").

A Story should focus on the task as outlined by the client. They should be descriptive and not proscriptive. A good rule of thumb is to ask, "Does this make any sense to a site consumer?".

Stories should also be written without specific technical requirements. Stories (and Acceptance Criteria) that are too technical are a red flag, because they often leave little room for the Acquia PS Team to provide guidance.

#### Acceptance Criteria

The Acceptance Criteria area of a story is the place for providing clarification of a Story. This should remain as user focused as possible and should be as brief as possible.

For example,

    h2. Story

    As a consumer, when I am on the homepage, I can log in to the site.

    h2. Acceptance Criteria

    - As a consumer, when I log into the site, my credentials are transmitted securely.
    - As a consumer, when I log into the site, if I enter incorrect credentials, I am notified that I need to try again.

In this example, bad Acceptance Criteria is too technical such as, "As a consumer, when I log into the site, I see a red asterisk to the right of the password input box that indicates the password field is a required field."

#### Testing Instructions

The testing instructions should be completed after development and when Stories are ready for User Acceptance Testing. These should always be provided so that clients can easily UAT the development work and so that there is a record of how that Story was actually completed.

### Tasks

Tasks solely for the development team. Clients should not even look at Tasks. They should be written with a technical focus to translate more user focused Stories into something a developer could use. Tasks are where the PS team can provide detailed implementation instructions.

Tasks should not be User Acceptance Tested. They can be QA'd by the development team, then marked as complete. The client should only be concerned with Stories.

# Example: Bug Report

    h2. Date
    -/--/2014

    h2. Versions
    *Browser Name and Version:* 7.x-1.x
    *OS Name and Version:* Chrome on OS X

    h2. Bug Description
    A concise description. Pure description, no narrative or conversational language.

    h2. Steps to Reproduce
    Step by step instructions on how to reproduce this bug. Screenshots, URLs and links are very useful.

    h2. Actual Behavior
    What happens when you follow the instructions. This is the manifestation of the bug.

    h2. Expected Behavior
    What you expected to happen when you followed the instructions. This is important, because there may be a misunderstanding on how something is implemented.

    h2. References
    - Chat Logs
    - Other descriptions of the bug

# Example: Story

    h2. Story

    As a consumer, when I view a product page, I can view product reviews.

    h2. Acceptance Criteria

    - As a consumer, when I am viewing a product review, I see a product rating (5 star system).
    - As a consumer, when I am viewing a product review, I see the authors nickname.

    h2. Testing Instructions

    - Go to /products/sample-product
    - Click 'Read Reviews'

    h2. Technical Guidance

    We will use a view to list "product review" nodes as a sorted list.

    h2. References

# Behavior Driven Development Testing

We are using Behat for automated testing. Behat will coordinate tests using a number of tools, including Selenium Webdriver which uses an actual browser to test the site.

Our testing components are located in the `/tests` folder of a project's repository.

## Initial set-up
### Install required components

#### Composer
##### About
Composer is a package manager for php

##### Install (OSX and Homebrew)

    brew install composer

#### Selenium Server

##### About

A testing tool that interfaces with a web browser (webdriver) to automate testing when Javascript and other browser specifics are needed.

##### Install (OSX and Homebrew)

    brew install selenium-server-standalone

##### Start Selenium (OSX and Homebrew)

    selenium-server -p 4444

##### Install (manually)

Go to http://docs.seleniumhq.org/download/ and find the Selenium Server download towards the middle.

You will be downloading a `.jar` (Java) file.

To start the server, you will run:

    java -jar [the-name-of-the-jar-file]

example:

    java -jar ~/Selenium/selenium-server-standalone-2.39.0.jar

#### PhantomJS

##### About
PhantomJS is a headless WebKit scriptable with a JavaScript API. It has fast and native support for various web standards: DOM handling, CSS selector, JSON, Canvas, and SVG.

##### Install (OSX and Homebrew)

    brew install phantomjs

##### Start PhantomJS (OSX and Homebrew)

    phantomjs --webdriver=8643

##### Install

Go to http://phantomjs.org/download.html and find the PhantomJS download towards the middle. Follow the instructions for your OS.

#### Chromedriver for Selenium (Optional to use Chrome instead of Firefox)

##### About

A web driver replacement that uses Chrome instead of Firefox

##### Install (OSX and Homebrew)

    brew install chromedriver

## Setting up Behat

Using a terminal, go to: `/tests/code`

Make a copy of `behat.local.yml.example` for your own settings and name it`behat.local.yml`.

Open the new `.yml` config file and fill in your local site information according to the comments. This file tells Behat how to connect to your local site.

Run Composer to download and install all the Behat libraries

    composer install

Test if Behat is working

    bin/behat -dl

example:

    bin/behat -dl

Run a sample test

    bin/behat features/seleniumtest.feature

Test if Selenium is also working

    bin/behat features/seleniumtest.feature

This test should pass if your Chrome (or Firefox) browser pops up quickly navigates to the page and closes.

# Continuous Integration

# Example: Jenkins Repository Sync

The following describes a generic Jenkins synchronization process that will track two remote repository branches against each other. In order to start, the following Jenkins plugins need to be installed:

* Git - https://wiki.jenkins-ci.org/display/JENKINS/Git+Plugin

    Adds in the ability for Jenkins to use Git as its SCM

* Copy project link - https://wiki.jenkins-ci.org/display/JENKINS/Copy+project+link+plugin

    Allows for a link to be added to projects that allow the SCM configuration to be easily copied and duplicated for a new set of repositories.

In a new project, the following key items need to be in place in the configuration -

* Source Code Management
    * Git
    * Add two repositories with the appropriate credentials to connect to each. One can be Github and the other can be Acquia Cloud.
    * Branches to Build - Set to the source branch to be tracked (ex: github/integration)
    * Additional Behaviors
      * Add “Merge before build”
        * Name - The remote repository (ex: acquia)
        * Branch to merge to - (ex:integration)
        * Merge strategy - default
* Build Triggers
  * Poll SCM
    * Schedule = * * * * *
* Build
  * Execute Shell
    * Command - exit 0
* Post-build Actions
  * Git Publisher
    * Push Only if Build Succeeds checked
    * Branches
      * Branch to push (ex: integration)
      * Target remote name (ex: acquia)

# Performance
XHProf, Caching, Database optimization.

# Testing
Behat, Mink, PhantomJS, Selenium.
