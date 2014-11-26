<?php

// Disable the Akamai module!
$conf['akamai_disabled'] = TRUE;

if (!empty($_ENV['AH_SITE_ENVIRONMENT'])) {
  switch ($_ENV['AH_SITE_ENVIRONMENT']) {
    case 'prod':
      // Enable the Akamai module!
      $conf['akamai_disabled'] = FALSE;

      // Akamai CCU connection.
      $conf['akamai_basepath'] = 'http://www.example.com';
      $conf['akamai_username'] = 'example-username';
      $conf['akamai_password'] = 'example-password';

      if (empty($conf['is_edit_domain'])) {
        // Set reverse proxy settings to make compatible with Akamai.
        $conf['omit_vary_cookie'] = TRUE;
        $conf['reverse_proxy'] = TRUE;
        $conf['reverse_proxy_header'] = 'HTTP_TRUE_CLIENT_IP';

        /**
         * If $conf['reverse_proxy_addresses'] is not set, the ip_address()
         * function will prioritize $_SERVER['REMOTE_ADDR'] over the value of
         * the $_SERVER[$conf['reverse_proxy_header']] value. Therefore, we
         * need to manually set $_SERVER['REMOTE_ADDR'] to
         * $_SERVER['HTTP_TRUE_CLIENT_IP'] in order to correctly determine the
         * visitor's IP. This makes us vulnerable to IP spoofing, but the
         * choice is between that vulnerability or using the Akamai
         * edge IP as the visitor's ip (which can cause us to accidentally
         * block an entire region).
         *
         * @see ip_address()
         */
        $_SERVER['REMOTE_ADDR'] = $_SERVER['HTTP_TRUE_CLIENT_IP'];
      }
  }
}
