<?php

if (empty($_ENV['AH_SITE_ENVIRONMENT'])) {
  /**
   * For a single database configuration, the following is sufficient:
   **/
  $databases = array (
    'default' =>
      array (
        'default' =>
          array (
            'database' => '${db.name}',
            'username' => '${db.username}',
            'password' => '${db.password}',
            'host' => '${db.host}',
            'port' => '${db.port}',
            'namespace' => 'Drupal\\Core\\Database\\Driver\\mysql',
            'driver' => 'mysql',
            'prefix' => '',
          ),
      ),
  );

  $base_url = '${local_url}';

  // Configuration directories.
  $dir = dirname(DRUPAL_ROOT);
  $config_directories['vcs'] = $dir . '/config/default';
  $config_directories['sync'] = $dir . '/config/default';
}
