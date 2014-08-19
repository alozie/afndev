<?php
if ($argc < 4) {
  print 'You must pass in arguments for the following variables:' . PHP_EOL . 'username, password, repository, [month], [day], [year], [number]' . PHP_EOL . PHP_EOL . 'example: php generate-release-notes.php dan password test-repo > example-release-notes.md' . PHP_EOL . 'example: php generate-release-notes.php dan password test-repo 4 10 2014 > example-release-notes.md' . PHP_EOL . 'example: php generate-release-notes.php dan password test-repo 4 10 2014 100 > example-release-notes.md' . PHP_EOL . PHP_EOL . 'note: by default, PRs are gathered from 30 days previous and up to 100 PRs' . PHP_EOL;
  exit;
}

// Default to 30 days ago as the first PR to pull.
if ($argc < 7) {
  $argv[4] = date('m', strtotime('30 days ago'));
  $argv[5] = date('d', strtotime('30 days ago'));
  $argv[6] = date('Y', strtotime('30 days ago'));
}

// Default to pulling 100 PRs.
if ($argc < 8) {
  $argv[7] = 100;
}

// Print the header.
print '# Release notes for ' . date("F j, Y") . PHP_EOL . PHP_EOL;

// We can only get 100 results at a time, so we need to split the calls into chunks of 100.
$calls = ceil($argv[7] / 100);

for ($page = 1; $page <= $calls; $page++) {
  // Download the json file from Github.
  $ch = curl_init();

  curl_setopt($ch, CURLOPT_HEADER, 0);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_USERPWD, $argv[1] . ':' . $argv[2]);
  curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
  curl_setopt($ch, CURLOPT_USERAGENT, 'Acquia-PS');

  $url = 'https://api.github.com/repos/acquia-pso/' . $argv[3] . '/issues?state=closed&since=' . $argv[6] . '-' . str_pad($argv[4], 2, '0', STR_PAD_LEFT) . '-' . str_pad($argv[5], 2, '0', STR_PAD_LEFT) . 'T00:00:00Z&per_page=' . $argv[7] . '&page=' . $page;

  curl_setopt($ch, CURLOPT_URL, $url);

  $json_raw = curl_exec($ch);
  $chinfo = curl_getinfo($ch);

  // We bail if we don't get a successful connection.
  if ($chinfo['http_code'] !== 200) {
    print 'HTTP Error: ' . $chinfo['http_code'] . PHP_EOL;
    print $json_raw . PHP_EOL;
    exit;
  }

  curl_close($ch);

  // Decode the JSON.
  $json = json_decode($json_raw, TRUE);

  // Print each Pull Request.
  foreach ($json as $pr) {
    // Print the PR Title.
    print '## ' . $pr['title'] . PHP_EOL;
    // Print the PR Time and URL.
    print date("F j, Y", strtotime($pr['closed_at'])) . ' ([' . $pr['html_url'] . ']' . '(' . $pr['html_url'] . '))' . PHP_EOL . PHP_EOL;
    // Print the PR Body.
    print $pr['body'] . PHP_EOL . PHP_EOL;
  }
}

?>
