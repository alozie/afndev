# Settings files.

This directory contains modularized settings files. The are "required" into
a Drupal installation's sites/default/settings.php in the follow manner

    require '../all/settings/base.settings.php';

base.settings.php is responsible for including the other `*.settings.php` files.
You can modify the `$includes` variable in `base.settings.php` to control which
files are included. E.g.,

    $includes = array(
      'cache' => TRUE,
      'cron' => TRUE,
      'files' => TRUE,
      'testing' => TRUE,
      'akamai' => FALSE,
      'edit-domain' => FALSE,
    );
