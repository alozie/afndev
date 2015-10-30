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
            'driver' => 'mysql',
            'prefix' => '',
          ),
      ),
  );

  $base_url = '${project.local_url}';

  // Disable virus scanning on uploaded files.
  $conf['clamav_enabled'] = 0;
  $conf['clamav_enable_element_image_widget'] = 0;
  $conf['clamav_enable_element_file_widget'] = 0;
  $conf['cron_safe_threshold'] = 0;

  $conf['stage_file_proxy_origin'] = '';
}
