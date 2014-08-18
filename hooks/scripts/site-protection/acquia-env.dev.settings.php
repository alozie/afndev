<?php

/**
 * @file
 * Acquia Cloud Development environment settings.
 */

// Purge Settings
$conf['purge_proxy_urls'] = 'http://{site}dev.prod.acquia-sites.com/?purge_method=ah';

// Private files path.
$conf['file_private_path'] = '/mnt/gfs/{site}.{env}/files-private';