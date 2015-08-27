<?php

/**
 * Acquia Cloud settings.
 */
if (file_exists('/var/www/site-php') && isset($_ENV['AH_SITE_GROUP'])) {
  require "/var/www/site-php/" . $_ENV['AH_SITE_GROUP'] . "/$ac_domain-settings.inc";
}

if (!empty($_ENV['AH_SITE_ENVIRONMENT'])) {
  switch ($ac_domain) {
    default:
      // Dynamically set base url based on Acquia environment variable.
      $base_url = "https://{$_ENV['AH_SITE_NAME']}.prod.acquia-sites.com";
      break;
    /*
    case 'multisite.example.com':
      $base_url = 'http://multisite.example.com';
      break;
    */
  }
}
