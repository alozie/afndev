<?php

/**
 * @file
 * Test configuration in settings.php.
 */

namespace Drupal\Tests\PHPUnit;
/**
 * Class SettingsTest.
 *
 * Verifies $settings.php conf values.
 */
class SettingsTest extends TestBase {
  /**
   * Sets up require parameters for tests to run.
   *
   * @param string $env
   *   The acquia environment being simulated. E.g., prod, test, dev, etc.
   */
  public function setupParams($env) {

    $this->drupalRoot = $this->projectDirectory . '/docroot';
    $_ENV['AH_SITE_ENVIRONMENT'] = $env;
    $_ENV['AH_SITE_NAME'] = '${project.acquia_subname}';
    $_ENV['AH_PHPUNIT_TEST'] = TRUE;
    // @codingStandardsIgnoreStart
    $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
    // @codingStandardsIgnoreEnd
    if (!defined('DRUPAL_ROOT')) {
      define('DRUPAL_ROOT', $this->drupalRoot);
    }

    // Adds settings.php and make global variables accessible to this class.
    require $this->drupalRoot . '/sites/default/settings.php';

    $this->conf = $conf;
    $this->base_url = $base_url;
  }

  /**
   * Test configuration for production environment on ACE.
   */
  public function testProd() {

    $this->setupParams('prod');

    // Base_url.
    $this->assertContains($this->getExpectedBaseUrl($_ENV['AH_SITE_NAME']), $this->base_url);

    // Assert cache.settings.php.
    $this->assertEquals(TRUE, $this->conf['cache']);
    $this->assertEquals(TRUE, $this->conf['block_cache']);
    $this->assertEquals(TRUE, $this->conf['page_compression']);
    $this->assertEquals(TRUE, $this->conf['preprocess_css']);
    $this->assertEquals(TRUE, $this->conf['preprocess_js']);
    $this->assertEquals(0, $this->conf['cache_lifetime']);
    $this->assertEquals(21600, $this->conf['page_cache_maximum_age']);
    $this->assertEquals('sites/all/modules/contrib/memcache/memcache.inc', $this->conf['cache_backends'][0]);
    $this->assertEquals('MemCacheDrupal', $this->conf['cache_default_class']);
    $this->assertEquals('DrupalDatabaseCache', $this->conf['cache_class_cache_form']);
    $this->assertEquals('includes/cache-install.inc', $this->conf['cache_backends'][1]);
    $this->assertEquals('DrupalFakeCache', $this->conf['cache_class_cache_page']);
    $this->assertEquals(FALSE, $this->conf['page_cache_invoke_hooks']);
    $this->assertEquals(TRUE, $this->conf['page_cache_without_database']);
    $this->assertEquals(TRUE, $this->conf['redirect_page_cache']);

    // Cron.
    $this->assertEquals(TRUE, $this->conf['acquia_spi_use_cron']);
    $this->assertEquals(FALSE, $this->conf['cron_safe_threshold']);

    // Files.
    $this->assertEquals('sites/default/files', $this->conf['file_public_path']);
    $this->assertEquals('sites/all', $this->conf['composer_manager_file_dir']);
    $this->assertEquals('sites/all/vendor', $this->conf['composer_manager_vendor_dir']);
    $this->assertEquals("/mnt/files/{$_ENV['AH_SITE_NAME']}/files-private", $this->conf['file_private_path']);
    $this->assertEquals("/mnt/tmp/{$_ENV['AH_SITE_NAME']}", $this->conf['file_temporary_path']);
    $this->assertEquals(FALSE, $this->conf['composer_manager_autobuild_file']);
    $this->assertEquals(FALSE, $this->conf['composer_manager_autobuild_packages']);
    $this->assertEquals(FALSE, $this->conf['admin_menu_cache_client']);

    // Testing.
    $this->assertEquals(FALSE, $this->conf['admin_menu_cache_client']);

    // Fast 404.
    $this->fast_404_tests();
  }

  /**
   * Test configuration for test/stg environment on ACE.
   */
  public function testTest() {

    $this->setupParams('test');

    // Base_url.
    $this->assertContains($this->getExpectedBaseUrl($_ENV['AH_SITE_NAME']), $this->base_url);

    // Assert cache.settings.php.
    $this->assertEquals('sites/all/modules/contrib/memcache/memcache.inc', $this->conf['cache_backends'][0]);
    $this->assertEquals('MemCacheDrupal', $this->conf['cache_default_class']);
    $this->assertEquals('DrupalDatabaseCache', $this->conf['cache_class_cache_form']);
    $this->assertEquals('includes/cache-install.inc', $this->conf['cache_backends'][1]);
    $this->assertEquals('DrupalFakeCache', $this->conf['cache_class_cache_page']);
    $this->assertEquals(FALSE, $this->conf['page_cache_invoke_hooks']);
    $this->assertEquals(TRUE, $this->conf['page_cache_without_database']);
    $this->assertEquals(TRUE, $this->conf['redirect_page_cache']);

    // Cron.
    $this->assertEquals(FALSE, $this->conf['acquia_spi_use_cron']);

    // Files.
    $this->assertEquals('sites/default/files', $this->conf['file_public_path']);
    $this->assertEquals('sites/all', $this->conf['composer_manager_file_dir']);
    $this->assertEquals('sites/all/vendor', $this->conf['composer_manager_vendor_dir']);
    $this->assertEquals("/mnt/files/{$_ENV['AH_SITE_NAME']}/files-private", $this->conf['file_private_path']);
    $this->assertEquals("/mnt/tmp/{$_ENV['AH_SITE_NAME']}", $this->conf['file_temporary_path']);
    $this->assertEquals(FALSE, $this->conf['composer_manager_autobuild_file']);
    $this->assertEquals(FALSE, $this->conf['composer_manager_autobuild_packages']);
    $this->assertEquals(FALSE, $this->conf['admin_menu_cache_client']);

    // Testing.
    $this->assertEquals(FALSE, $this->conf['admin_menu_cache_client']);

    // Fast 404 tests.
    $this->fast_404_tests();
  }

  /**
   * Test configuration for dev environment on ACE.
   */
  public function testDev() {

    $this->setupParams('dev');

    $this->assertContains($this->getExpectedBaseUrl($_ENV['AH_SITE_NAME']), $this->base_url);

    // Assert cache.settings.php.
    $this->assertEquals('sites/all/modules/contrib/memcache/memcache.inc', $this->conf['cache_backends'][0]);
    $this->assertEquals('MemCacheDrupal', $this->conf['cache_default_class']);
    $this->assertEquals('DrupalDatabaseCache', $this->conf['cache_class_cache_form']);
    $this->assertEquals('includes/cache-install.inc', $this->conf['cache_backends'][1]);
    $this->assertEquals('DrupalFakeCache', $this->conf['cache_class_cache_page']);
    $this->assertEquals(FALSE, $this->conf['page_cache_invoke_hooks']);
    $this->assertEquals(TRUE, $this->conf['page_cache_without_database']);
    $this->assertEquals(TRUE, $this->conf['redirect_page_cache']);

    // Cron.
    $this->assertEquals(FALSE, $this->conf['acquia_spi_use_cron']);

    // Files.
    $this->assertEquals('sites/default/files', $this->conf['file_public_path']);
    $this->assertEquals('sites/all', $this->conf['composer_manager_file_dir']);
    $this->assertEquals('sites/all/vendor', $this->conf['composer_manager_vendor_dir']);
    $this->assertEquals("/mnt/files/{$_ENV['AH_SITE_NAME']}/files-private", $this->conf['file_private_path']);
    $this->assertEquals("/mnt/tmp/{$_ENV['AH_SITE_NAME']}", $this->conf['file_temporary_path']);
    $this->assertEquals(FALSE, $this->conf['composer_manager_autobuild_file']);
    $this->assertEquals(FALSE, $this->conf['composer_manager_autobuild_packages']);
    $this->assertEquals(FALSE, $this->conf['admin_menu_cache_client']);

    // Testing.
    $this->assertEquals(FALSE, $this->conf['admin_menu_cache_client']);

    // Fast 404 tests.
    $this->fast_404_tests();
  }

  /**
   * Returns the expected Acquia Cloud $base_url based on site name.
   *
   * @param string $site_name
   *   The Acquia Cloud site name.
   *
   * @return string
   *   The expected $base_url.
   */
  public function getExpectedBaseUrl($site_name) {

    $is_ah_free_tier = (!empty($_ENV['ACQUIA_HOSTING_DRUPAL_LOG'])
      && strstr($_ENV['ACQUIA_HOSTING_DRUPAL_LOG'], 'free'));
    $domain_prefix = $is_ah_free_tier ? 'devcloud' : 'prod';
    $domain = "$site_name.$domain_prefix.acquia-sites.com";
    $protocol = 'http://';

    return $protocol . $domain;
  }

  /**
   * Tests Phing setup:drupal:settings target.
   */
  public function testSetupLocalSettings() {

    $this->assertFileExists($this->projectDirectory . '/docroot/sites/default/settings/local.settings.php');
  }

  /**
   * Tests for the Fast 404 module settings.
   */
  private function fast_404_tests() {

    $this->assertEquals($this->conf['fast_404_exts'], '/[^robots]\.(txt|png|gif|jpe?g|css|js|ico|swf|flv|cgi|bat|pl|dll|exe|asp)$/i');

    $this->assertEquals($this->conf['fast_404_allow_anon_imagecache'], TRUE);

    $this->assertEquals($this->conf['fast_404_html'], '<html xmlns="http://www.w3.org/1999/xhtml"><head><title>404 Not Found</title></head><body><h1>Not Found</h1><p>The requested URL "@path" was not found on this server.</p></body></html>');

    $this->assertEquals($this->conf['fast_404_url_whitelisting'], FALSE);
    $this->assertEquals($this->conf['fast_404_whitelist'], array('index.php', 'rss.xml', 'install.php', 'cron.php', 'update.php', 'xmlrpc.php'));

    $this->assertEquals($this->conf['fast_404_string_whitelisting'], array('cdn/farfuture', '/advagg_'));

    $this->assertEquals($this->conf['fast_404_HTML_error_all_paths'], FALSE);

  }
}
