<?php

/**
 * For a single database configuration, the following is sufficient:
 **/
$databases = array (
  'default' =>
  array (
    'default' =>
    array (
      'database' => 'drupal',
      'username' => 'root',
      'password' => '',
      'host' => 'localhost',
      'port' => '3306',
      'namespace' => 'Drupal\\Core\\Database\\Driver\\mysql',
      'driver' => 'mysql',
      'prefix' => '',
    ),
  ),
);

$base_url = 'http://127.0.0.1:8080';

// Configuration directories.
$dir = dirname(DRUPAL_ROOT);
$config_directories['sync'] = $dir . '/config/default';

// Use development service parameters.
$settings['container_yamls'][] = $dir . '/sites/development.services.yml';
