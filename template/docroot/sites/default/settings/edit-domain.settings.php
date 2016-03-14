<?php

// Create edit domain variables.
$forwarded_host = (empty($_SERVER['HTTP_X_FORWARDED_HOST'])) ? $_SERVER['HTTP_HOST'] : $_SERVER['HTTP_X_FORWARDED_HOST'];
$conf['is_edit_domain'] = FALSE;
if (strpos($forwarded_host, 'edit') !== FALSE) {
  $conf['is_edit_domain'] = TRUE;
}

// Enable HTTPS for all acquia domain names, which are used exclusively for
// authenticated traffic.
if ($conf['is_edit_domain']) {
  $conf['https'] = TRUE;
}

// If we're in the Acquia Cloud, require setting and set up a few variables.
if ($is_ah_env) {
  switch ($ah_env) {
    case 'prod':
      $conf['reverse_proxy_header'] = 'HTTP_X_AH_CLIENT_IP';
      if ($conf['is_edit_domain']) {
        $base_url = 'https://edit.example.com';
        $cookie_domain = '.edit.example.com';
      }
      else {
        $base_url = 'http://www.example.com';
        $cookie_domain = '.www.example.com';
      }
      break;
  }
}
