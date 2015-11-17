<?php

/**
 * @file
 * PHP Unit tests for Bolt deployments.
 */

namespace Drupal;

use Symfony\Component\Yaml\Yaml;

/**
 * Class DeployTest.
 *
 * Verifies that build artifact matches standards.
 */
class DeployTest extends \PHPUnit_Framework_TestCase {

  /**
   * Class constructor.
   */
  public function __construct() {
    $this->projectDirectory = realpath(dirname(__FILE__) . '/../../');
    $this->config = Yaml::parse(file_get_contents("{$this->projectDirectory}/project.yml"));
    $this->config = array_merge($this->config, Yaml::parse(file_get_contents("{$this->projectDirectory}/local.yml")));
    $this->new_project_dir = dirname($this->projectDirectory) . '/' . $this->config['project']['acquia_subname'];
    $this->deploy_dir = $this->new_project_dir . '/deploy';
  }

  /**
   * Tests Phing deploy target.
   */
  public function testBoltDeploy() {

    // Ensure deploy directory exists.
    $this->assertFileExists($this->deploy_dir);

    // Ensure docroot was built into to deploy directory.
    $this->assertFileExists($this->deploy_dir . '/docroot');
    $this->assertFileExists($this->deploy_dir . '/docroot/index.php');
    $this->assertFileExists($this->deploy_dir . '/docroot/core');
    $this->assertFileExists($this->deploy_dir . '/docroot/modules/contrib');

    // Ensure settings files were copied to deploy directory.
    $this->assertFileExists($this->deploy_dir . '/docroot/sites/default/settings.php');
    $this->assertFileExists($this->deploy_dir . '/docroot/sites/default/settings');
    $this->assertFileNotExists($this->deploy_dir . '/docroot/sites/default/settings/local.settings.php');

    // Ensure hooks were copied to deploy directory.
    $this->assertFileExists($this->deploy_dir . '/hooks');
    $this->assertFileExists($this->deploy_dir . '/hooks/README.md');
  }

}
