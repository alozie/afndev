<?php

// Disable the Akamai module by default. To be enable on prod in specific.
// $conf['akamai_disabled'] = TRUE;

if ($is_ah_env) {
  switch ($ah_env) {
    case 'prod':

      // Akamai module configuration.
      // $conf['akamai_disabled'] = FALSE;
      // $conf['akamai_basepath'] = 'http://www.example.com';
      // $conf['akamai_username'] = 'example-username';
      // $conf['akamai_password'] = 'example-password';

      if (empty($conf['is_edit_domain'])) {
        /**
         * Page caching:
         *
         * By default, Drupal sends a "Vary: Cookie" HTTP header for anonymous page
         * views. This tells a HTTP proxy that it may return a page from its local
         * cache without contacting the web server, if the user sends the same Cookie
         * header as the user who originally requested the cached page. Without "Vary:
         * Cookie", authenticated users would also be served the anonymous page from
         * the cache. If the site has mostly anonymous users except a few known
         * editors/administrators, the Vary header can be omitted. This allows for
         * better caching in HTTP proxies (including reverse proxies), i.e. even if
         * clients send different cookies, they still get content served from the cache.
         * However, authenticated users should access the site directly (i.e. not use an
         * HTTP proxy, and bypass the reverse proxy if one is used) in order to avoid
         * getting cached pages from the proxy.
         */
        $conf['omit_vary_cookie'] = TRUE;

        /**
         * Reverse Proxy Configuration:
         *
         * Reverse proxy servers are often used to enhance the performance
         * of heavily visited sites and may also provide other site caching,
         * security, or encryption benefits. In an environment where Drupal
         * is behind a reverse proxy, the real IP address of the client should
         * be determined such that the correct client IP address is available
         * to Drupal's logging, statistics, and access management systems. In
         * the most simple scenario, the proxy server will add an
         * X-Forwarded-For header to the request that contains the client IP
         * address. However, HTTP headers are vulnerable to spoofing, where a
         * malicious client could bypass restrictions by setting the
         * X-Forwarded-For header directly. Therefore, Drupal's proxy
         * configuration requires the IP addresses of all remote proxies to be
         * specified in $conf['reverse_proxy_addresses'] to work correctly.
         *
         * Enable this setting to get Drupal to determine the client IP from
         * the X-Forwarded-For header (or $conf['reverse_proxy_header'] if set).
         * If you are unsure about this setting, do not have a reverse proxy,
         * or Drupal operates in a shared hosting environment, this setting
         * should remain commented out.
         *
         * In order for this setting to be used you must specify every possible
         * reverse proxy IP address in $conf['reverse_proxy_addresses'].
         * If a complete list of reverse proxies is not available in your
         * environment (for example, if you use a CDN) you may set the
         * $_SERVER['REMOTE_ADDR'] variable directly in settings.php.
         * Be aware, however, that it is likely that this would allow IP
         * address spoofing unless more advanced precautions are taken.
         */
        $conf['reverse_proxy'] = TRUE;

        /**
         * Set this value if your proxy server sends the client IP in a header
         * other than X-Forwarded-For.
         */
        // For Akamai, reverse proxy header is HTTP_TRUE_CLIENT_IP.
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
        if (!empty($_SERVER['HTTP_TRUE_CLIENT_IP'])) {
          $_SERVER['REMOTE_ADDR'] = $_SERVER['HTTP_TRUE_CLIENT_IP'];
        }
      }
  }
}
