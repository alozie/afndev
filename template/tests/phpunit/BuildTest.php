<?php

/**
 * @file
 * Test build tasks. E.g. setup:build:all.
 */

namespace Drupal;

/**
 * Class BuildTest.
 *
 * Verifies that build tasks work as expected.
 */
class BuildTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Class constructor.
     */
    public function __construct()
    {
        $this->projectDirectory = realpath(dirname(__FILE__) . '/../../');
    }


    /**
     * Tests Phing setup:make target.
     *
     * This should build /make.yml.
     */
    public function testSetupMake()
    {
        $this->assertFileExists($this->projectDirectory . '/docroot/index.php');
        $this->assertFileExists($this->projectDirectory . '/docroot/modules/contrib');
        $this->assertFileExists($this->projectDirectory . '/docroot/themes/custom');
    }
}
