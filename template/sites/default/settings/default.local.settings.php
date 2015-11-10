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
}
