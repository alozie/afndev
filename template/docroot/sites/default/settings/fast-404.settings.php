<?php

/**
 * *******************************
 * Fast404 Quick Settings
 * Based on: https://confluence.acquia.com/display/PS/Site+launch+success+guide#Sitelaunchsuccessguide-Fast404Configuration and https://docs.acquia.com/articles/using-fast-404-drupal
 * *******************************
 */

# Load the fast_404.inc file
include_once(DRUPAL_ROOT . '/sites/all/modules/contrib/fast_404/fast_404.inc');

# Disallowed extensions
$conf['fast_404_exts'] = '/[^robots]\.(txt|png|gif|jpe?g|css|js|ico|swf|flv|cgi|bat|pl|dll|exe|asp)$/i';

# Allow anonymous users to hit URLs containing 'imagecache'
$conf['fast_404_allow_anon_imagecache'] = TRUE;

# Default fast 404 error message.
$conf['fast_404_html'] = '<html xmlns="http://www.w3.org/1999/xhtml"><head><title>404 Not Found</title></head><body><h1>Not Found</h1><p>The requested URL "@path" was not found on this server.</p></body></html>';

# Add URL whitelisting & if fast_404_url_whitelisting is true
$conf['fast_404_url_whitelisting'] = FALSE;
$conf['fast_404_whitelist'] = array(
  'index.php',
  'rss.xml',
  'install.php',
  'cron.php',
  'update.php',
  'xmlrpc.php'
);

# Array of whitelisted URL fragment strings that conflict with fast_404.
$conf['fast_404_string_whitelisting'] = array('cdn/farfuture', '/advagg_');

# By default the custom 404 page is only loaded for path checking. Load it for all 404s with the below option set to TRUE
$conf['fast_404_HTML_error_all_paths'] = FALSE;

# Don't call this when executing tests or else we'll have undefined function errors.
if (empty($_ENV['AH_PHPUNIT_TEST']) && function_exists('fast_404_ext_check')) {
  fast_404_ext_check();
}