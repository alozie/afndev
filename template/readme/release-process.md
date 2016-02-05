# Release Process

This document is a work in progress.

* [Generating a build artifact](deploy.md)
* Branching strategies
* Tagging
* Release Notes - Each release should be accompanied by a set of release notes, 
  which can be easily generated using the [Release Notes Script]
  (../scripts/release-notes/README.md)
* [Deploying tag and executing updates](#deploy-tag)
* Notifications (slack, hipchat, etc) - @todo

## Branching strategies

@todo Document this!

## Generating a build artifact

@todo Document this! [Generating a build artifact](deploy.md)

## Tagging

@todo Document this!

## Release notes

@todo Document this!

## <a name="deploy-tag"></a>Deploying tag and executing updates

Deploying Drupal across environments can be daunting, but if due diligence has 
been taken with configuration management, the process of deployment is actually 
quite simple.

No matter how many environments there are or whatever versioning workflow is 
being used, the actual deployment process will take approximately the following
form (please note the commands are examples):

1. Put the site into maintenance mode `drush vset maintenance_mode 1`   
2. Flush Caches to empty the cache tables and ensure maintenance mode is set. `drush cc all`   
3. Perform any necessary backups, notably the database `drush sql-dump > backup-yyyy-mm-dd.sql`   
4. Pull the latest code onto the server `git pull origin/master`   
5. Run update.php `drush updb -y`   
7. Take the site out of maintenance mode `drush vset maintenance_mode 0`   
8. Clear Drupal caches `drush cc all`   

A few things that you should (almost) never do on production:
1. Revert all features via `drush fra -y`. This poses a site stability risk and
also risks wiping a feature that may be been accidentally overridden in production. 
Feature should be explicitly reverted via a call to `features_revert_module()` 
in a `hook_update_N()` implementation.
1. Run `drush cc all`. Specific caches should be targeted whenever possible.
1. Utilize `drush use`. This introduces the risk that the release master will
accidentally run a command against prod after the release.

There might be some extra steps depending on the infrastructure and the extent 
of site changes. For example, a major application change might require a flush 
of other caches in the system such as Varnish or Memcached. 

# Notifications

@todo Document this!
