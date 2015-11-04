<?php

// Disable acquia_spi by default.
$conf['acquia_spi_use_cron'] = FALSE;

// If we're in the Acquia Cloud, require setting and set up a few variables.
if ($is_ah_env) {
  switch ($ah_env) {
    case 'prod':
      // Disable automatic cron run.
      $conf['cron_safe_threshold'] = 0;
      $conf['acquia_spi_use_cron'] = TRUE;

      break;
  }
}
