<?php

/**
 * @file
 * Test base for project PHPUnit tests.
 */

namespace Drupal\Tests\PHPUnit;

/**
 * Class TestBase.
 *
 * Verifies that behat configuration is as expected.
 */
abstract class TestBase extends \PHPUnit_Framework_TestCase
{
   /**
     * Class constructor.
     */
    public function __construct()
    {
        $this->projectDirectory = dirname(dirname(dirname(__DIR__)));
        $this->drupalRoot = $this->projectDirectory . '/docroot';
    }
}
