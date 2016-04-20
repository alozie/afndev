# ACSF Setup with Bolt

To configure a project to run on ACSF, perform the following steps after initially setting up Bolt:

1.  Add the ACSF module to your `composer.json`:

    `"drupal/acsf": "~8.1.0"` 
2.  Add the following ACSF patch to `composer.json`:

    `"drupal/acsf": {"2706365": "https://www.drupal.org/files/issues/acsf-2706365-2.patch"}`

    This will patch the acsf_init script to ignore the settings.php hash check on deployments, which would otherwise cause deployments to fail.
3.  Run `composer update`.
4.  In the docroot, run the following command:

    `drush --include=modules/contrib/acsf/acsf_init acsf-init --skip-default-settings`

    This will create a number of new files in your repo that are required by ACSF, including custom cloud hooks, `sites.php`, a `sites/g` directory, and an `.htaccess` patch: 
5. Add acsf as a dependency to your profile.

Your project should now be able to be deployed on either ACSF or ACE.
