<?php

use ProjectTemplate\Installer\Console as Console;
use Symfony\Component\Console\Application;
use Symfony\Component\Filesystem\Filesystem;

require __DIR__ . '/../install/vendor/autoload.php';

$application = new Application();
$application->add(new Console\Installer(new Filesystem()));
$application->run();
