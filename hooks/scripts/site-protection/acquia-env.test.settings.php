<?php

/**
 * @file
 * Acquia Cloud Test / Staging environment settings.
 */

// Purge Settings
$conf['purge_proxy_urls'] = ($_SERVER['HTTP_HOST'] == '{site}stg.prod.acquia-sites.com') ? 'http://{site}stg.prod.acquia-sites.com/?purge_method=ah' : 'http://stage.domain.com/?purge_method=ah';

// Private files path.
$conf['file_private_path'] = '/mnt/gfs/{site}.{env}/files-private';