<?php

/**
 * @file
 * Test build tasks. E.g. setup:build:all.
 */

namespace Drupal\Tests\PHPUnit;

/**
 * Class BuildTest.
 *
 * Verifies that build tasks work as expected.
 */
class BuildTest extends TestBase
{
   /**
     * Tests Phing setup:make target.
     *
     * This should build /make.yml.
     */
    public function testSetupMake()
    {
        $this->assertFileExists($this->projectDirectory . '/docroot/index.php');
        $this->assertFileExists($this->projectDirectory . '/docroot/sites/all/modules/contrib');
        $this->assertFileExists($this->projectDirectory . '/docroot/sites/all/themes/custom');
    }
}
