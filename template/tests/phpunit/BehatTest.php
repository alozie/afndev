<?php

/**
 * @file
 * Test behat configuration.
 */

namespace Drupal;

/**
 * Class BehatTest.
 *
 * Verifies that behat configuration is as expected.
 */
class BehatTest extends \PHPUnit_Framework_TestCase
{

   /**
     * Class constructor.
     */
    public function __construct()
    {
        $this->projectDirectory = realpath(dirname(__FILE__) . '/../../');
    }

    /**
     * Tests Phing setup:behat target.
     */
    public function testSetupBehat()
    {
        // Assert that a local.yml file was created in the new project.
        $this->assertFileExists($this->projectDirectory . '/tests/behat/local.yml');
        // $behat_config = Yaml::parse(file_get_contents("{$this->projectDirectory}/tests/behat/local.yml"));

        $this->assertNotContains(
            '${local_url}',
            file_get_contents("{$this->projectDirectory}/tests/behat/local.yml")
        );
    }
}
