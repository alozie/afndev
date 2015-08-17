<?php

/**
 * @file
 * Examples and dependent methods for IP address and basic authentication
 * application restrictions on Acquia Cloud environments.
 *
 * The site lockdown process happens by calling ac_protect_this_site();
 * after defining desired $conf variables.
 *
 * Whitelist / blacklist IPs may use any of the following syntax:
 * - CIDR (107.0.255.128/27)
 * - Range (121.91.2.5-121-121.91.3.4)
 * - Wildcard (36.222.120.*)
 * - Single  (9.80.226.4)
 *
 * Business logic:
 * - With no $conf values set, ``ac_protect_this_site();`` will do nothing.
 * - If the path is marked as restricted, all users not on the whitelist will
 *   receive access denied.
 * - If a user's IP is on the blacklist and not on the whitelist they will
 *   receive access denied.
 * - Securing the site requires entries in both $conf['ah_whitelist'] and
 *   $conf['ah_restricted_paths'].
 */

/**
 * Basic auth credentials: an array of username / password combinations.
 *
 * Examples:
 *
 * @code
 * $conf['ah_basic_auth_credentials'] = array(
 *   'Editor' => 'Password',
 *   'Admin' => 'P455w0rd',
 * );
 * @endcode
 */
$conf['ah_basic_auth_credentials'] = array();

/**
 * Whitelisted IP addresses: An array of IP addresses to allow on to the site.
 *
 * Examples:
 *
 * @code
 * $conf['ah_whitelist'] = array(
 *   '36.222.120.*',
 *   '107.0.255.128/27',
 * );
 * @endcode
 *
 * @code
 * $conf['ah_whitelist'] = array(
 *   '107.0.255.128/27',
 *   '64.119.156.88/29',
 *   '64.119.149.232/29',
 *   '107.20.238.9',
 *   '70.102.97.2/30',
 *   '50.196.16.189/29',
 * );
 */
$conf['ah_whitelist'] = array();

/**
 * Blacklisted IP addresses: An array of IP addresses to deny access to the site.
 *
 * Examples:
 *
 * @code
 * $conf['ah_blacklist'] = array(
 *   '12.13.14.15',
 * );
 * @endcode
 */
$conf['ah_blacklist'] = array();

/**
 * Restricted paths: Paths which may not be accessed unless the user is on the
 * IP whitelist.
 *
 * Examples:
 *
 * @code
 * $conf['ah_restricted_paths'] = array(
 *   '*',
 * );
 * @endcode
 *
 * @code
 * $conf['ah_restricted_paths'] = array(
 *   'user',
 *   'user/*',
 *   'admin',
 *   'admin/*',
 * );
 * @endcode
 */
$conf['ah_restricted_paths'] = array();

/**
 * No-cache paths: Paths we should explicitly never cache.
 *
 * Examples:
 *
 * @code
 * $conf['ah_paths_no_cache'] = array(
 *   'api',
 * );
 * @endcode
 */
$conf['ah_paths_no_cache'] = array();

/**
 * Skip-authentication paths: Skip basic authentication for these paths.
 *
 * Examples:
 *
 * @code
 * $conf['ah_paths_skip_auth'] = array(
 *   'api',
 * );
 * @endcode
 */
$conf['ah_paths_skip_auth'] = array();

/**
 * Protect this site in non-production Acquia environments.
 */
if (isset($_ENV['AH_NON_PRODUCTION']) && $_ENV['AH_NON_PRODUCTION']) {

  if (file_exists(DRUPAL_ROOT . '/sites/acquia.inc')) {
    require DRUPAL_ROOT . '/sites/acquia.inc';
    ac_protect_this_site();
  }
}
