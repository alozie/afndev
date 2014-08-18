<?php

/**
 * @file
 * Acquia Cloud Production environment settings.
 */

// Purge Settings
$conf['purge_proxy_urls'] = ($_SERVER['HTTP_HOST'] == '{site}.prod.acquia-sites.com') ? 'http://{site}.prod.acquia-sites.com/?purge_method=ah' : 'http://the.prod.domain.com/?purge_method=ah';

$conf['cache'] = TRUE;
// When using varnish, set cache_lifetime to 0. 
// @See https://backlog.acquia.com/browse/NN-7868 and https://confluence.acquia.com/display/support/Caching+Bible
$conf['cache_lifetime'] = 300;
$conf['block_cache'] = 1;
$conf['page_cache_maximum_age'] = 3600;
$conf['page_compression'] = TRUE;
$conf['preprocess_css'] = TRUE;
$conf['preprocess_js'] = TRUE;

// Enable memcache on Acquia prod.
$conf['cache_backends'][] = 'sites/all/modules/contrib/memcache/memcache.inc';
$conf['cache_default_class'] = 'MemCacheDrupal';
$conf['cache_class_cache_form'] = 'DrupalDatabaseCache';
$conf['cache_class_cache_entity_bean'] = 'DrupalDatabaseCache';

// Private files path.
$conf['file_private_path'] = '/mnt/gfs/{site}.{env}/files-private';
