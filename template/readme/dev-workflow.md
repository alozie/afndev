# Development Workflow

No direct changes should be pushed to the Acquia repository. The process of 
syncing these repositories is managed transparently in the background.

The recommended workflow resembles a [Gitflow Workflow]
(https://www.atlassian.com/git/workflows#!workflow-gitflow) with the follow 
specifics -

* All development is performed against a `develop` branch.
* Completed features are merged into a `release` branch until a new release 
  needs to be made. Additional QA testing should be made against this branch 
  and fixed inline, if needed.
* Each commit to the `master` branch is tagged with a release number, named 
  either based on sprints (e.g. `24.0`) or date (e.g. `2014-08-19.0`).
* Any hotfixes are merged directly into a `hotfix` branch, which can then be 
  merged to `master`.

## Beginning work

When you pull a Jira ticket and begin work on a feature, you should create a 
new feature branch named according to the following pattern: 
`abc-123-short-desc`.

All commits to the branch should have commit messages following the pattern:
"ABC-123 A gramatically correct sentence ending within punctiation."

Where "ABC" is the Jira prefix of your Jira project and "123" is the ticket
number for which the work is being performed.

## Creating a Pull Request

For any work, pull requests must be created for individual tasks and submitted 
for review. Before submitting a pull request, be sure to [sync the local branch]
(https://help.github.com/articles/syncing-a-fork) with the upstream primary 
branch -

    git checkout develop
    git pull upstream develop
    git push origin develop
    git checkout -b XXX-<new-issue-branch> develop

Using [Hub](https://github.com/github/hub), it is very easy to create a new PR 
based on the current feature -

    hub pull-request

In order to enforce consistency on a project, a pull request template can also 
be configured using `hub` -

    git config --global --add hub.pull-request-template-path ~/.pr-template

## Cleaning a Pull Request

If you created many small commits locally while working through a ticket, you 
should clean the history so that it can be easily reviewed. You can combine 
these commits using `git rebase`.

    git rebase -i upstream/master

Pull requests should never contain merge commits from upstream changes.

## Integration (merging pull requests)

Two versions of the integration workflow are recommended -

1. Integration manager
1. Peer review

**In either workflow, no one should ever commit their own code to the primary 
working branch.**

### Integration Manager

This model requires one (or more) lead developers to take the responsibility of 
merging all pull requests. This ensures consistency in quality control as well 
as identifying any potential issues with related, open pull requests.

A small group of one or more person(s) is selected to be integrators. All 
commits are reviewed by this group. If work is done by an integrator, their work
should be reviewed by a fellow integrator (as if they were a developer).

### Peer Review

This model removes the bottleneck of designated integrators, but still 
eliminates commits directly to the working branch. In short, every commit is 
reviewed by a developer other than the one submitting the original commit.

## Continuous Integration

After a Pull Request has been submitted or merged, our continous integration
solution will automatically build a site artifact, install an ephemeral instance
of Drupal, and execute tests against it. For more information on the build 
process, please see the [build directory](../build/README.md).

## Release Process

See the [Release Process document](release-process.md) for detailed information.
