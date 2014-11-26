<?php

if (!empty($_ENV['AH_SITE_ENVIRONMENT'])) {
  switch ($_ENV['AH_SITE_ENVIRONMENT']) {
    case 'prod':

      // Enforce caching in production.
      $conf['cache'] = TRUE;
      $conf['block_cache'] = TRUE;

      // Enforce aggregation and compression.
      $conf['page_compression'] = TRUE;
      $conf['preprocess_css'] = TRUE;
      $conf['preprocess_js'] = TRUE;
      break;
  }

  // When using varnish, set cache_lifetime to 0.
  // @See https://backlog.acquia.com/browse/NN-7868 and
  // https://confluence.acquia.com/display/support/Caching+Bible
  // Setting this to 0 allows direct cache clears to clear caches that
  // have not yet reached expiration.
  $conf['cache_lifetime'] = 0;
  // Set default cache expiration to 30 minutes.
  $conf['page_cache_maximum_age'] = 1800;

  // Memcache for caching on Acquia Cloud.
  $conf['cache_backends'][] = './sites/all/modules/contrib/memcache/memcache.inc';
  $conf['cache_default_class'] = 'MemCacheDrupal';
  $conf['cache_class_cache_form'] = 'DrupalDatabaseCache';

  // Varnish and page caching are fundamentally the same. In all SSL through
  // Varnish backed environments (all Acquia envs), disable the page cache.
  // @See https://backlog.acquia.com/browse/DOC-2961
  $conf['cache_backends'][] = './includes/cache-install.inc';
  $conf['cache_class_cache_page'] = 'DrupalFakeCache';

  // With the page cache disabled, no sense invoking hooks. Note that this is
  // NOT compatible with domain access.
  $conf['page_cache_invoke_hooks'] = FALSE;
  $conf['page_cache_without_database'] = TRUE;

  // Even though our page cache backend is fake, setting redirects to be
  // stored in the page cache will set the appropriate cache headers.
  $conf['redirect_page_cache'] = TRUE;
}
