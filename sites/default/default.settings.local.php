<?php

$databases['default']['default'] = array(
  'driver' => 'mysql',
  'host' => 'localhost',
  'prefix' => '',
  'database' => 'projecttemplate',
  'username' => 'root',
  'password' => 'mamp',
);


// Disable virus scanning on uploaded files.
$conf['clamav_enabled'] = 0;
$conf['clamav_enable_element_image_widget'] = 0;
$conf['clamav_enable_element_file_widget'] = 0;
$conf['cron_safe_threshold'] = 0;

// $conf['stage_file_proxy_origin'] = 'http://www.example.com';

// Akamai CCU connection.
$conf['akamai_disabled'] = TRUE;

// Set file directories.
$conf['file_temporary_path'] = '/tmp';
