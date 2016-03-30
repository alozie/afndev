# Bolt Build Process

Given that Bolt generates child projects which must be tested, the CI flow is a 
little confusing. The CI configuration is detailed below.

## CI Flow

When a pull request is submitted a travis build is run against Bolt. This tests 
Bolt's ability to generate a new project. After a successful build, the new 
project will be deployed to Acquia Cloud and to a GitHub repository, where 
another child Travis build is subsequently executed. Here is the step-by-step
breakdown:

1. Bolt 7.x Pull Request is submitted
2. Travis Build *against Bolt* creates Bolted7 child project
    * Tests are run to assert that project was created
    * Tests are run against the child project (install, behat, phpunit, etc.)
3. Upon success, Bolted7 child project is pushed to ACE bolted 7 subscription 
   and to GitHub acquia-pso/bolted7. 
    * Tests assert that deployment to remote(s) was successful
4. Travis Build *against Bolted7* begins. Sadly, failure of this build
   has no impact on the success of Bolt's builds. Status of child builds should
   be checked periodically to verify that Bolt is generating a working build
   process for child projects out of the box.

Likewise, this process occurs for pull requests submitted to Bolt 8.x with 
Bolted8 as a companion project.

## SSH Access

SSH access between projects is managed by the creation of two dummy users, one 
for GitHub and one for Acquia Cloud. SSH keys are added to each user, and each 
user is added with required role(s) to the relevant project on Acquia Cloud and 
GitHub.

GitHub User: acquia-pso-ci
Acquia Cloud User: matthew.grasmick+acquia-pso-ci@acquia.com

This requires the following steps for configuration:

1. Generate id_rsa_bolt and id_rsa_bolt.pub key pair.
1. Add id_rsa_bolt _private_ key to https://travis-ci.com/acquia/bolt/settings.
1. Add id_rsa_bolt _private_ key to https://travis-ci.com/acquia-pso/bolted7/settings.
1. Add id_rsa_bolt _private_ key to https://travis-ci.com/acquia-pso/bolted8/settings.
1. Add id_rsa_bolt.pub key to acquia-pso-ci user on GitHub.
1. Add acquia-pso-ci user to acquia/bolt project on GitHub
1. Add acquia-pso-ci user to acquia-pso/bolted7 project on GitHub
1. Add acquia-pso-ci user to acquia-pso/bolted8 project on GitHub
1. Add id_rsa_bolt.pub key to matthew.grasmick+acquia-pso-ci@acquia.com on
   Acquia Cloud.
1. Add matthew.grasmick+acquia-pso-ci@acquia.com to "Bolted" team on Acquia 
   Cloud.

## Resources

* [GitHub Bolt](https://github.com/acquia/bolt)
* [GitHub Bolted7](https://github.com/acquia-pso/bolted7)
* [GitHub Bolted8](https://github.com/acquia-pso/bolted8)
* [GitHub Dummy User](https://github.com/acquia-pso-ci)
* [Acquia Bolted 7](https://insight.acquia.com/cloud/workflow?s=2919001)
* [Acquia Bolted 8](https://insight.acquia.com/cloud/workflow?s=2918916)
* [Acquia Bolted Team](https://insight.acquia.com/teams/9dacf306-b37f-406b-aa7b-28991601aa16/members)
* [Travis Bolt](https://travis-ci.com/acquia/bolt/settings)
* [Travis Bolted7](https://travis-ci.com/acquia-pso/bolted7/settings)
* [Travis Bolted8](https://travis-ci.com/acquia-pso/bolted8/settings)
