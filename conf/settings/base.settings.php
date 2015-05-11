<?php

// @todo Include settings from https://github.com/acquia/PS-Tools/tree/master/scripts/site-protection.

/**
 * Acquia Cloud settings.
 */
if (file_exists('/var/www/site-php')) {
  require "/var/www/site-php/$ac_subscription/$ac_domain-settings.inc";
}

if (!empty($_ENV['AH_SITE_ENVIRONMENT'])) {
  switch ($ac_domain) {
    case 'default':
      // Dynamically set base url based on Acquia environment varaible.
      $base_url = "https://{$_ENV['AH_SITE_NAME']}.prod.acquia-sites.com";
      break;
    case 'multisite.example.com':
      $base_url = 'http://multisite.example.com';
      break;
  }
}

/**
 * Require various settings includes.
 */
$includes = array(
  'cache' => TRUE,
  'cron' => TRUE,
  'files' => TRUE,
  'testing' => TRUE,
  'akamai' => FALSE,
  'edit-domain' => FALSE,
  'site-protection' => FALSE,
);
foreach ($includes as $key => $enabled) {
  if ($enabled) {
    require $key . '.settings.php';
  }
}

/**
 * Settings for local development.
 */
if (file_exists(__DIR__ . '/settings.local.php')) {
  require __DIR__ . '/settings.local.php';
}
