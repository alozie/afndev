<?php

/**
 * @file
 * Test configuration in settings.php.
 */

namespace Drupal;

use PHPUnit_Framework_TestCase;

class SettingsTest extends PHPUnit_Framework_TestCase
{
    /**
     * Sets up require parameters for tests to run.
     *
     * @param string $env
     *   The acquia environment being simulated. E.g., prod, test, dev, etc.
     */
    public function setupParams($env)
    {
        $this->projectRoot = dirname(dirname(__DIR__));
        $this->drupalRoot = $this->projectRoot . '/docroot';
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

        // Base_url
        $this->assertContains($_ENV['AH_SITE_NAME'] . '.prod.acquia-sites.com', $base_url);
    }

  /**
   * Test configuration for test/stg environment on ACE.
   */
    public function testTest()
    {
        $this->setupParams('test');
        require $this->drupalRoot . '/sites/default/settings.php';

        // Base_url
        $this->assertContains($_ENV['AH_SITE_NAME'] . '.prod.acquia-sites.com', $base_url);
    }

  /**
   * Test configuration for dev environment on ACE.
   */
    public function testDev()
    {
        $this->setupParams('dev');
        require $this->drupalRoot . '/sites/default/settings.php';

        $this->assertContains($_ENV['AH_SITE_NAME'] . '.prod.acquia-sites.com', $base_url);
    }
}
