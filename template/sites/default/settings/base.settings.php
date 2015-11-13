<?php

/**
 * Host detection.
 */
if (!empty($_SERVER['HTTP_X_FORWARDED_HOST'])) {
  $forwarded_host = $_SERVER['HTTP_X_FORWARDED_HOST'];
}
elseif(!empty($_SERVER['HTTP_HOST'])) {
  $forwarded_host = $_SERVER['HTTP_HOST'];
}
else {
  $forwarded_host = NULL;
}

$server_protocol = empty($_SERVER['HTTPS']) ? 'http' : 'https';
$forwarded_protocol = !empty($_ENV['HTTP_X_FORWARDED_PROTO']) ? $_ENV['HTTP_X_FORWARDED_PROTO'] : $server_protocol;

/**
 * Environment detection.
 *
 * Note that the values of enviromental variables are set differently on Acquia
 * Cloud Free tier vs Acquia Cloud Professional and Enterprise.
 */
$ah_env = isset($_ENV['AH_SITE_ENVIRONMENT']) ? $_ENV['AH_SITE_ENVIRONMENT'] : NULL;
$is_ah_env = (bool) $ah_env;
$is_ah_prod_env = ($ah_env == 'prod');
$is_ah_stage_env = ($ah_env == 'test');
$is_ah_free_tier = (!empty($_ENV['ACQUIA_HOSTING_DRUPAL_LOG']) && strstr($_ENV['ACQUIA_HOSTING_DRUPAL_LOG'], 'free'));
$is_ah_dev_env = (preg_match('/^dev[0-9]*$/', $ah_env) == TRUE);
$is_local_env = !$is_ah_env;

if ($ah_env) {
  switch ($_ENV['AH_SITE_ENVIRONMENT']) {
    default:
      // Dynamically set base url based on Acquia environment variable.
      $domain_prefix = $is_ah_free_tier ? 'devcloud' : 'prod';
      $domain = "{$_ENV['AH_SITE_NAME']}.$domain_prefix.acquia-sites.com";
      break;
  }

  /**
   * Base URL (optional).
   *
   * If Drupal is generating incorrect URLs on your site, which could
   * be in HTML headers (links to CSS and JS files) or visible links on pages
   * (such as in menus), uncomment the Base URL statement below (remove the
   * leading hash sign) and fill in the absolute URL to your Drupal installation.
   *
   * You might also want to force users to use a given domain.
   * See the .htaccess file for more information.
   *
   * Examples:
   *   $base_url = 'http://www.example.com';
   *   $base_url = 'http://www.example.com:8888';
   *   $base_url = 'http://www.example.com/drupal';
   *   $base_url = 'https://www.example.com:8888/drupal';
   *
   * It is not allowed to have a trailing slash; Drupal will add it
   * for you.
   */
  $protocol = 'http://';
  $base_url = $protocol . $domain;

  /**
   * Drupal automatically generates a unique session cookie name for each site
   * based on its full domain name. If you have multiple domains pointing at the
   * same Drupal site, you can either redirect them all to a single domain (see
   * comment in .htaccess), or uncomment the line below and specify their shared
   * base domain. Doing so assures that users remain logged in as they cross
   * between your various domains. Make sure to always start the $cookie_domain
   * with a leading dot, as per RFC 2109.
   */
  $cookie_domain = ".$domain";
}
