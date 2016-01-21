<?php

/**
 * @file
 * Test configuration in settings.php.
 */

namespace Drupal\Tests\PHPUnit;

use PHPUnit_Framework_TestCase;

class SettingsTest extends TestBase
{

    /**
     * Sets up require parameters for tests to run.
     *
     * @param string $env
     *   The acquia environment being simulated. E.g., prod, test, dev, etc.
     */
    public function setupParams($env)
    {
        $_ENV['AH_SITE_ENVIRONMENT'] = $env;
        $_ENV['AH_SITE_NAME'] = $_ENV['AH_SITE_GROUP'] = 'bolt';
        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
        if (!defined('DRUPAL_ROOT')) {
            define('DRUPAL_ROOT', $this->drupalRoot);
        }
    }

    /**
     * Test configuration for production environment on ACE.
     */
    public function testProd()
    {
        $this->setupParams('prod');
        require $this->drupalRoot . '/sites/default/settings.php';


        $this->assertContains($config['system.logging']['error_level'], 'hide');
    }

      /**
     * Tests Phing setup:drupal:settings target.
     */
    public function testSetupLocalSettings()
    {
        $this->assertFileExists($this->projectDirectory . '/docroot/sites/default/settings/local.settings.php');
    }
}
