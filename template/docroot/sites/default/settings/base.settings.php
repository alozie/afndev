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
$is_ah_dev_cloud = (!empty($_SERVER['HTTP_HOST']) && strstr($_SERVER['HTTP_HOST'], 'devcloud'));
$is_ah_dev_env = (preg_match('/^dev[0-9]*$/', $ah_env) == TRUE);
$is_local_env = !$is_ah_env;

/**
 * Display all errors for all but tests and prod envs.
 */
if ($is_local_env || $is_ah_dev_env) {
  // Ultimately, EVERY compiler message represents a mistake in the code.
  // Acquia Cloud isn't quite ready for E_STRICT yet.
  error_reporting(E_ALL);
  // Print errors on WSOD.
  ini_set('display_errors', TRUE);
  ini_set('display_startup_errors', TRUE);
}
