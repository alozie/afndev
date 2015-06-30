# Git Configuration

This directory contains [git hooks](https://git-scm.com/book/en/v2/Customizing-Git-Git-Hooks) that should be copied into your local repository's `.git` directory. All new developers onboarding onto a project should copy these hooks into their local repository's `.git/hooks` directory after cloning the project. 

`cp -R git/* .git/hooks/`

## Provided Hooks

Two default hooks are provided:

* commit-msg - This validates the syntax of a git commit message before it is committed locally.
* pre-commit - This runs Drupal Code Sniffer on committed code before it is committed locally.
