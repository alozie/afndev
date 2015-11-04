<?php

// Disable mail on non-production enviroments
if (!$is_ah_env || !in_array($ah_env, array('prod'))) {
  // Ensure DevelMailLog is available even if Devel module is disabled
  include_once('includes/mail.inc');
  include_once('modules/system/system.mail.inc');
  include_once($module_dir . '/development/devel/devel.mail.inc');

  $conf['mail_system'] = array(
    'default-system' => 'DevelMailLog',
  );
}
