<?php

/**
 * Host detection.
 */
$forwarded_host = !empty($_SERVER['HTTP_X_FORWARDED_HOST']) ? $_SERVER['HTTP_X_FORWARDED_HOST'] : $_SERVER['HTTP_HOST'];
$server_protocol = empty($_SERVER['HTTPS']) ? 'http' : 'https';
$forwarded_protocol = !empty($_ENV['HTTP_X_FORWARDED_PROTO']) ? $_ENV['HTTP_X_FORWARDED_PROTO'] : $server_protocol;

/**
 * Environment detection.
 */
$ah_env = isset($_ENV['AH_SITE_ENVIRONMENT']) ? $_ENV['AH_SITE_ENVIRONMENT'] : NULL;
$is_ah_env = (bool) $ah_env;
$is_ah_prod_env = ($ah_env == 'prod');
$is_ah_stage_env = ($ah_env == 'test');
$is_ah_dev_env = (preg_match('/^dev[0-9]*$/', $ah_env) == TRUE);
$is_local_env = !$is_ah_env;

if ($ah_env) {
  switch ($_ENV['AH_SITE_ENVIRONMENT']) {
    default:
      // Dynamically set base url based on Acquia environment variable.
      $domain = "https://" . $_ENV['AH_SITE_NAME'] . ".prod.acquia-sites.com";

      break;
  }
}
