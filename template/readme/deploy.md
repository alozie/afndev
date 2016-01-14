# Deployment workflow

This document outlines the workflow to build a complete Drupal docroot (plus 
supporting features, such as Cloud Hooks) which can be deployed directly to 
Acquia Cloud. Collectively, this bundle of code is referred to as the "build 
artifact".

The most important thing to remember about this workflow is that the Github and 
ACE repos are _not_ clones of one another. Github only stores the source code, 
and ACE only stores the production code (i.e. the build artifacts).

Currently, this workflow can either be followed manually, or integrated into a 
CI solution such as Jenkins or Travis. Eventually, Build Steps will enable it to
run directly on Acquia Cloud, obviating the need to maintain separate 
repositories for source code (Github) and production code (ACE).

## First time setup

You should have your Github repository (where this document is stored) checked 
out locally. Your ACE repository should be empty, or nearly empty.

Check out a new branch to match whatever branch you are working on in Github 
(typically `develop`).

Ensure your ACE remote is listed in project.yml under git:remotes.

## Creating the build artifact

In order to create the build artifact in `/deploy`, simply run
```
./task.sh deploy:build:artifact
```

This task is analogous to `setup:build:all` but with a few critical differences:
* The docroot is created at `/deploy/docroot`.
* Custom files (settings, modules, themes) are copied into place instead of
being symlinked.
* The hooks directory is copied from the repo root into `deploy`.
* (planned) CSS / JS are compiled in production mode (compressed / minified)
* (planned) Sensitive files, such as CHANGELOG.txt, are removed.

## Deploying the build artifact

After the build artifact is created, you can simply run:
````
./task.sh deploy:artifact -Ddeploy.branch=develop-build -Ddeploy.commitMsg='BLT-123: The commit message.'
````

This command will commit the artifact to the `develop-build` branch with the
specified commit message and push it to the remotes defined in project.yml.

## Build + Deploy

You can build and deploy an artifact in a single command:


## Continuous integration

Instead of performing these deployments manually, you can enlist the help of a 
CI tool such as Travis or Jenkins.

### Travis CI

Access to Travis is already provided by Acquia PS, which makes this option 
appealing on a cost basis. It will automtically deploy new commits after they 
are merged and tests pass. However, it's somewhat insecure (you have to create 
an SSH key for deployments that can be accessed by any developer), and it's 
impossible to schedule regular deployments or perform more advanced 
integrations.

### Jenkins (Cloudbees)

Jenkins is more expensive and time-consuming to set up, but offers much more 
flexibility. For instance, you can add a Slack slash command (such as `/deploy`)
that allows your QA team to deploy specific branches to ACE on demand. See the 
forthcoming README on Jenkins integration for more information.

Note, we previously used Engineering's Jenkins instance for CI (ci.acquia.com), 
but this should be avoided going forward. Instead, a starter account with 
Cloudbees is recommended. There's a two-week free trial, and after that the 
service is ~$60 per month, and must be paid for on a per-project basis.
