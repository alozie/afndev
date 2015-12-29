<?php

/**
 * @file
 * Contains get_local_uri(), which returns local URI.
 *
 * This is split into a separate file so that it can be included in both
 * .drushrc and in settings.php, where it must be assigned to different
 * variables.
 */

/**
 * Get the local URI.
 *
 * @return string
 *   The local URI.
 */
function get_local_uri() {
  $uri = 'http://127.0.0.1:8080';

  return $uri;
}
