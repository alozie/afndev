<?php

if (!empty($_ENV['AH_SITE_ENVIRONMENT'])) {
  switch ($_ENV['AH_SITE_ENVIRONMENT']) {
    default:
      // Dynamically set base url based on Acquia environment variable.
      $domain = $_ENV['AH_SITE_NAME'] . ".prod.acquia-sites.com";
      $base_url = "https://$domain";
      $cookie_domain = ".$domain";

      break;
    /*
    case 'multisite.example.com':
      $base_url = 'http://multisite.example.com';
      break;
    */
  }
}
