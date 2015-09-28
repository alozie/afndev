<?php

// Set this to the proper Acquia subscription. Subsequent includes rely
// upon this variable being set correctly.
$ac_subname = '${project.acquia_subname}';

/**
 * Acquia Cloud settings.
 */
if (file_exists('/var/www/site-php') && isset($_ENV['AH_SITE_GROUP'])) {
  require "/var/www/site-php/" . $_ENV['AH_SITE_GROUP'] . "/$ac_subname-settings.inc";
}

if (!empty($_ENV['AH_SITE_ENVIRONMENT'])) {
  switch ($_ENV['AH_SITE_ENVIRONMENT']) {
    default:
      // Dynamically set base url based on Acquia environment variable.
      $domain = "https://" . $_ENV['AH_SITE_NAME'] . ".prod.acquia-sites.com";

      break;
  }
}
