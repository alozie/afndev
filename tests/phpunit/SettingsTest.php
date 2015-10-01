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

        // Assert cache.settings.php
        $this->assertEquals(true, $conf['cache']);
        $this->assertEquals(true, $conf['block_cache']);
        $this->assertEquals(true, $conf['page_compression']);
        $this->assertEquals(true, $conf['preprocess_css']);
        $this->assertEquals(true, $conf['preprocess_js']);
        $this->assertEquals(0, $conf['cache_lifetime']);
        $this->assertEquals(21600, $conf['page_cache_maximum_age']);
        $this->assertEquals('sites/all/modules/contrib/memcache/memcache.inc', $conf['cache_backends'][0]);
        $this->assertEquals('MemCacheDrupal', $conf['cache_default_class']);
        $this->assertEquals('DrupalDatabaseCache', $conf['cache_class_cache_form']);
        $this->assertEquals('includes/cache-install.inc', $conf['cache_backends'][1]);
        $this->assertEquals('DrupalFakeCache', $conf['cache_class_cache_page']);
        $this->assertEquals(false, $conf['page_cache_invoke_hooks']);
        $this->assertEquals(true, $conf['page_cache_without_database']);
        $this->assertEquals(true, $conf['redirect_page_cache']);

        // Cron.
        $this->assertEquals(true, $conf['acquia_spi_use_cron']);
        $this->assertEquals(false, $conf['cron_safe_threshold']);

        // Files.
        $this->assertEquals('sites/default/files', $conf['file_public_path']);
        $this->assertEquals('sites/all', $conf['composer_manager_file_dir']);
        $this->assertEquals('sites/all/vendor', $conf['composer_manager_vendor_dir']);
        $this->assertEquals("/mnt/files/{$_ENV['AH_SITE_NAME']}/files-private", $conf['file_private_path']);
        $this->assertEquals("/mnt/tmp/{$_ENV['AH_SITE_NAME']}", $conf['file_temporary_path']);
        $this->assertEquals(false, $conf['composer_manager_autobuild_file']);
        $this->assertEquals(false, $conf['composer_manager_autobuild_packages']);
        $this->assertEquals(false, $conf['admin_menu_cache_client']);

        // Testing.
        $this->assertEquals(false, $conf['admin_menu_cache_client']);
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

        // Assert cache.settings.php.
        $this->assertEquals('sites/all/modules/contrib/memcache/memcache.inc', $conf['cache_backends'][0]);
        $this->assertEquals('MemCacheDrupal', $conf['cache_default_class']);
        $this->assertEquals('DrupalDatabaseCache', $conf['cache_class_cache_form']);
        $this->assertEquals('includes/cache-install.inc', $conf['cache_backends'][1]);
        $this->assertEquals('DrupalFakeCache', $conf['cache_class_cache_page']);
        $this->assertEquals(false, $conf['page_cache_invoke_hooks']);
        $this->assertEquals(true, $conf['page_cache_without_database']);
        $this->assertEquals(true, $conf['redirect_page_cache']);

        // Cron.
        $this->assertEquals(false, $conf['acquia_spi_use_cron']);

        // Files.
        $this->assertEquals('sites/default/files', $conf['file_public_path']);
        $this->assertEquals('sites/all', $conf['composer_manager_file_dir']);
        $this->assertEquals('sites/all/vendor', $conf['composer_manager_vendor_dir']);
        $this->assertEquals("/mnt/files/{$_ENV['AH_SITE_NAME']}/files-private", $conf['file_private_path']);
        $this->assertEquals("/mnt/tmp/{$_ENV['AH_SITE_NAME']}", $conf['file_temporary_path']);
        $this->assertEquals(false, $conf['composer_manager_autobuild_file']);
        $this->assertEquals(false, $conf['composer_manager_autobuild_packages']);
        $this->assertEquals(false, $conf['admin_menu_cache_client']);

        // Testing.
        $this->assertEquals(false, $conf['admin_menu_cache_client']);
    }

  /**
   * Test configuration for dev environment on ACE.
   */
    public function testDev()
    {
        $this->setupParams('dev');
        require $this->drupalRoot . '/sites/default/settings.php';

        $this->assertContains($_ENV['AH_SITE_NAME'] . '.prod.acquia-sites.com', $base_url);

        // Assert cache.settings.php.
        $this->assertEquals('sites/all/modules/contrib/memcache/memcache.inc', $conf['cache_backends'][0]);
        $this->assertEquals('MemCacheDrupal', $conf['cache_default_class']);
        $this->assertEquals('DrupalDatabaseCache', $conf['cache_class_cache_form']);
        $this->assertEquals('includes/cache-install.inc', $conf['cache_backends'][1]);
        $this->assertEquals('DrupalFakeCache', $conf['cache_class_cache_page']);
        $this->assertEquals(false, $conf['page_cache_invoke_hooks']);
        $this->assertEquals(true, $conf['page_cache_without_database']);
        $this->assertEquals(true, $conf['redirect_page_cache']);

        // Cron.
        $this->assertEquals(false, $conf['acquia_spi_use_cron']);

        // Files.
        $this->assertEquals('sites/default/files', $conf['file_public_path']);
        $this->assertEquals('sites/all', $conf['composer_manager_file_dir']);
        $this->assertEquals('sites/all/vendor', $conf['composer_manager_vendor_dir']);
        $this->assertEquals("/mnt/files/{$_ENV['AH_SITE_NAME']}/files-private", $conf['file_private_path']);
        $this->assertEquals("/mnt/tmp/{$_ENV['AH_SITE_NAME']}", $conf['file_temporary_path']);
        $this->assertEquals(false, $conf['composer_manager_autobuild_file']);
        $this->assertEquals(false, $conf['composer_manager_autobuild_packages']);
        $this->assertEquals(false, $conf['admin_menu_cache_client']);

        // Testing.
        $this->assertEquals(false, $conf['admin_menu_cache_client']);
    }
}
