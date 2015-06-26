<?php

/**
 * Acquia Cloud settings.
 */
if (file_exists('/var/www/site-php')) {
  require "/var/www/site-php/$ac_subscription/$ac_domain-settings.inc";
}

if (!empty($_ENV['AH_SITE_ENVIRONMENT'])) {
  switch ($ac_domain) {
    case 'default':
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
