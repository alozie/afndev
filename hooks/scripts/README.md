Miscellaneous scripts.


# /audit

Some useful audit-related scripts.


# /site-protection

Sample code for settings.php includes and $conf settings that help you quickly
lock down an Acquia Cloud environment using basic auth and / or IP whitelisting.

- All site lockdown logic in located in acquia.inc
- All settings are in $conf variables.
    - ``$conf['ah_basic_auth_credentials']`` An array of basic auth username /
      password combinations
    - ``$conf['ah_whitelist']`` An array of IP addresses to allow on to the site.
    - ``$conf['ah_blacklist']`` An array of IP addresses that will be denied access to the site.
    - ``$conf['ah_paths_no_cache']`` Paths we should explicitly never cache.
    - ``$conf['ah_paths_skip_auth']`` Skip basic authentication for these paths.
    - ``$conf['ah_restricted_paths']`` Paths which may not be accessed unless the user is on the IP whitelist.
- The site lockdown process happens by calling ``ac_protect_this_site();`` with defined $conf elements.
- Whitelist / blacklist IPs may use any of the following syntax:
    - CIDR (107.0.255.128/27)
    - Range (121.91.2.5-121-121.91.3.4)
    - Wildcard (36.222.120.*)
    - Single  (9.80.226.4)
    
## Business Logic
- With no $conf values set, ``ac_protect_this_site();`` will do nothing.
- If the path is marked as restricted, all users not on the whitelist will receive access denied.
- If a user's IP is on the blacklist and **not** on the whitelist they will receive access denied.
- Filling ``$conf['ah_basic_auth_credentials']`` will result in all requests being requring an .htaccess log in.
- Securing the site requires entries in both ``$conf['ah_whitelist']`` **and** ``$conf['ah_restricted_paths']``


## Examples

#### Block access to non-whitelisted users on all pages of non-production environments.
```
$conf['ah_restricted_paths'] = array(
  '*',
);

$conf['ah_whitelist'] = array(
  '36.222.120.*',
  '107.0.255.128/27',
);

if (file_exists('/var/www/site-php')) {
  require('/var/www/site-php/{site}/{site}-settings.inc');

  if(!defined('DRUPAL_ROOT')) {
    define('DRUPAL_ROOT', getcwd());
  }

  if (file_exists(DRUPAL_ROOT . '/sites/acquia.inc')) {
    if (isset($_ENV['AH_NON_PRODUCTION']) && $_ENV['AH_NON_PRODUCTION']) {
      require DRUPAL_ROOT . '/sites/acquia.inc';
      ac_protect_this_site();
    }
  }
}
```
#### Block access to user and admin pages on the production environment. Enforce .htaccess authentication on non-production.  Allow access to an API path without authentication
 
```
if (file_exists('/var/www/site-php')) {
  require('/var/www/site-php/{site}/{site}-settings.inc');

  if(!defined('DRUPAL_ROOT')) {
    define('DRUPAL_ROOT', getcwd());
  }

  if (file_exists(DRUPAL_ROOT . '/sites/acquia.inc')) {
    if (isset($_ENV['AH_SITE_ENVIRONMENT'])) {
      if ($_ENV['AH_SITE_ENVIRONMENT'] != 'prod') {
        $conf['ah_basic_auth_credentials'] = array(
          'Editor' => 'Password',
          'Admin' => 'P455w0rd',
        );
        $conf['ah_paths_no_cache'] = array(
          'api'
        );
      }
      else {
        $conf['ah_restricted_paths'] = array(
          'user',
          'user/*',
          'admin',
          'admin/*',
        );
        $conf['ah_whitelist'] = array(
          '107.0.255.128/27',
          '64.119.156.88/29',
          '64.119.149.232/29',
          '107.20.238.9',
          '70.102.97.2/30',
          '50.196.16.189/29',
        );
      }
      require DRUPAL_ROOT . '/sites/acquia.inc';
      ac_protect_this_site();
    }
  }
}
```

#### Blacklist known bad IPs on all environments

```
$conf['ah_blacklist'] = array(
  '12.13.14.15',
);

if (file_exists('/var/www/site-php')) {
  require('/var/www/site-php/{site}/{site}-settings.inc');

  if(!defined('DRUPAL_ROOT')) {
    define('DRUPAL_ROOT', getcwd());
  }

  if (file_exists(DRUPAL_ROOT . '/sites/acquia.inc')) {
    require DRUPAL_ROOT . '/sites/acquia.inc';
    ac_protect_this_site();
  }
}
```
