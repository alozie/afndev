<?php

/**
 * @file
 * Drupal site-specific configuration file. See documentation and additional
 * settings in default.settings.php.
 */

/**
 * Access control for update.php script.
 */
$update_free_access = FALSE;

/**
 * Salt for one-time login links and cancel links, form tokens, etc.
 */
$drupal_hash_salt = '';

/**
 * PHP ini overrides.
 */
ini_set('session.gc_probability', 1);
ini_set('session.gc_divisor', 100);
ini_set('session.gc_maxlifetime', 200000);
ini_set('session.cookie_lifetime', 2000000);

/**
 * Fast 404 pages.
 */
$conf['404_fast_paths_exclude'] = '/\/(?:styles)\//';
$conf['404_fast_paths'] = '/\.(?:txt|png|gif|jpe?g|css|js|ico|swf|flv|cgi|bat|pl|dll|exe|asp)$/i';
$conf['404_fast_html'] = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+RDFa 1.0//EN" "http://www.w3.org/MarkUp/DTD/xhtml-rdfa-1.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><title>404 Not Found</title></head><body><h1>Not Found</h1><p>The requested URL "@path" was not found on this server.</p></body></html>';

/**
 * Conditional memory allocation
 */
if (drupal_is_cli()) {
  ini_set('memory_limit', '1024M');
}
else {
  ini_set('memory_limit', '192M');
}

/**
 * Variable overrides.
 */

// General config.
$conf['site_name'] = '{site}.{env}';
$conf['token_tree_recursion_limit'] = 2;

// Search and Acquia Network
$conf['acquia_identifier'] = '';
$conf['acquia_key'] = '';
$conf['apachesolr_default_environment'] = 'acquia_search_server_1';

// Analytics and Mollom
$conf['googleanalytics_account'] = '';
$conf['mollom_private_key'] = '';
$conf['mollom_public_key'] = '';

/**
 * Acquia environment variables.
 */
// Basic http auth credential options.
$conf['ah_basic_auth_credentials'] = array(
  'Username' => 'Password',
  // 'Additional Username' => 'Additional Password',
);

// Whitelisted CIDR-compatible IP ranges.
$conf['ah_whitelist'] = array(
  // Localhost
  '127.0.0.0/8',
  // Acquia: US East
  '107.0.255.0/24',
  '107.0.255.128/27',
  '64.119.156.88/29',
  '64.119.149.232/29',
  '107.20.238.9/29',
  // Acquia: US West
  '70.102.97.2/30',
  '50.196.16.189/29',
  // Acquia: EU
  '212.36.36.218/29',
  '46.137.113.132/29',
  // Client (Example)
  '16.7.209.4-16.7.211.11',
  // Partner (Example)
  '140.199.6.1',
);

// Blacklisted CIDR-compatible IP ranges.
$conf['ah_blacklist'] = array(
  '42.121.69.2/32',
  '9.80.226.4',
  '121.91.2.5-121-121.91.3.4',
  '36.222.120.*',
);

// Do not cache these paths on Acquia Cloud.
$conf['ah_paths_no_cache'] = array(
  // 'user',
  // 'user/login',
);

// A list of paths requiring the user to be on the whitelist to access.
$conf['ah_restricted_paths'] = array(
  'user',
  'user/*',
  'admin',
  'admin/*',
);

// Do not password protect these paths even in a protected environment.
$conf['ah_paths_skip_auth'] = array(
  // 'api',
);

/**
 * Acquia Cloud configuration settings.
 */
if (file_exists('/var/www/site-php')) {
  require('/var/www/site-php/{site}/{site}-settings.inc');

  // Use the wrapper protection methods to apply basic auth and IP whitelisting.
  if(!defined('DRUPAL_ROOT')) {
    define('DRUPAL_ROOT', getcwd());
  }

  if (file_exists(DRUPAL_ROOT . '/sites/acquia.inc')) {
    require DRUPAL_ROOT . '/sites/acquia.inc';
    ac_protect_this_site();
  }

  /**
   * Acquia Cloud: PROD-specific configuration
   */
  if (isset($_ENV['AH_SITE_ENVIRONMENT']) && $_ENV['AH_SITE_ENVIRONMENT'] == 'prod') {
    require './sites/default/acquia-env.prod.settings.php';
  }

  /**
   * Acquia Cloud: TEST/STAGE-specific configuration
   */
  else if (isset($_ENV['AH_SITE_ENVIRONMENT']) && $_ENV['AH_SITE_ENVIRONMENT'] == 'test') {
    require './sites/default/acquia-env.test.settings.php';
  }

  /**
   * Acquia Cloud: DEV-specific configuration
   */
  else if (isset($_ENV['AH_SITE_ENVIRONMENT']) && $_ENV['AH_SITE_ENVIRONMENT'] == 'dev') {
    require './sites/default/acquia-env.dev.settings.php';
  }
}

/**
 * Config specific to local development environments.
 */
else {
  
}

