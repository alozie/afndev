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
   * Test configuration for production environment on ACE.
   */
    public function testProd()
    {
        $_ENV['AH_SITE_ENVIRONMENT'] = 'prod';
        $_ENV['AH_SITE_GROUP'] = 'projectid';

        $_ENV['AH_SITE_NAME'] = $_ENV['AH_SITE_GROUP'];
        require 'sites/all/settings/base.settings.php';

        // Base_url
        $this->assertContains('projectid.prod.acquia-sites.com', $base_url);

        // Assert cache.settings.php
        $this->assertEquals(true, $conf['cache']);
        $this->assertEquals(true, $conf['block_cache']);
        $this->assertEquals(true, $conf['page_compression']);
        $this->assertEquals(true, $conf['preprocess_css']);
        $this->assertEquals(true, $conf['preprocess_js']);
        $this->assertEquals(0, $conf['cache_lifetime']);
        $this->assertEquals(1800, $conf['page_cache_maximum_age']);
        $this->assertEquals('./sites/all/modules/contrib/memcache/memcache.inc', $conf['cache_backends'][0]);
        $this->assertEquals('MemCacheDrupal', $conf['cache_default_class']);
        $this->assertEquals('DrupalDatabaseCache', $conf['cache_class_cache_form']);
        $this->assertEquals('./includes/cache-install.inc', $conf['cache_backends'][1]);
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
        $this->assertEquals('/mnt/files/projectid/files-private', $conf['file_private_path']);
        $this->assertEquals('/mnt/tmp/projectid', $conf['file_temporary_path']);
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
        $_ENV['AH_SITE_ENVIRONMENT'] = 'test';
        $_ENV['AH_SITE_GROUP'] = 'projectid';

        $_ENV['AH_SITE_NAME'] = $_ENV['AH_SITE_GROUP'] . $_ENV['AH_SITE_ENVIRONMENT'];
        require 'conf/settings/base.settings.php';

        $this->assertContains('projectidtest.prod.acquia-sites.com', $base_url);

        // Assert cache.settings.php.
        $this->assertEquals(0, $conf['cache_lifetime']);
        $this->assertEquals(1800, $conf['page_cache_maximum_age']);
        $this->assertEquals('./sites/all/modules/contrib/memcache/memcache.inc', $conf['cache_backends'][0]);
        $this->assertEquals('MemCacheDrupal', $conf['cache_default_class']);
        $this->assertEquals('DrupalDatabaseCache', $conf['cache_class_cache_form']);
        $this->assertEquals('./includes/cache-install.inc', $conf['cache_backends'][1]);
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
        $this->assertEquals('/mnt/files/projectidtest/files-private', $conf['file_private_path']);
        $this->assertEquals('/mnt/tmp/projectidtest', $conf['file_temporary_path']);
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
        $_ENV['AH_SITE_ENVIRONMENT'] = 'dev';
        $_ENV['AH_SITE_GROUP'] = 'projectid';

        $_ENV['AH_SITE_NAME'] = $_ENV['AH_SITE_GROUP'] . $_ENV['AH_SITE_ENVIRONMENT'];
        require 'conf/settings/base.settings.php';

        $this->assertContains('projectiddev.prod.acquia-sites.com', $base_url);

        // Assert cache.settings.php.
        $this->assertEquals(0, $conf['cache_lifetime']);
        $this->assertEquals(1800, $conf['page_cache_maximum_age']);
        $this->assertEquals('./sites/all/modules/contrib/memcache/memcache.inc', $conf['cache_backends'][0]);
        $this->assertEquals('MemCacheDrupal', $conf['cache_default_class']);
        $this->assertEquals('DrupalDatabaseCache', $conf['cache_class_cache_form']);
        $this->assertEquals('./includes/cache-install.inc', $conf['cache_backends'][1]);
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
        $this->assertEquals('/mnt/files/projectiddev/files-private', $conf['file_private_path']);
        $this->assertEquals('/mnt/tmp/projectiddev', $conf['file_temporary_path']);
        $this->assertEquals(false, $conf['composer_manager_autobuild_file']);
        $this->assertEquals(false, $conf['composer_manager_autobuild_packages']);
        $this->assertEquals(false, $conf['admin_menu_cache_client']);

        // Testing.
        $this->assertEquals(false, $conf['admin_menu_cache_client']);
    }
}
