<?php

use Symfony\Component\Yaml\Yaml;

/**
 * Class InstallerTest.
 *
 * Tests install/src/ProjectTemplate/Installer/Console/Installer.php.
 */
class InstallerTest extends PHPUnit_Framework_TestCase
{
  public function __construct() {
    $this->currentProjectDirectory = realpath(dirname(__FILE__) . '/../../');
    $this->config = Yaml::parse(file_get_contents("{$this->currentProjectDirectory}/config.yml"));
    $this->newProjectDirectory = dirname($this->currentProjectDirectory) . '/' . $this->config['project']['machine_name'];
  }

  public function testCreateProject() {
    $this->assertFileExists($this->newProjectDirectory);
  }

  public function testBuildMakeFile() {
    $this->assertFileExists($this->newProjectDirectory . '/docroot/index.php');
  }

  public function testInstallTestingFramework() {
    // @todo Assert values insite of local.yml.
    $this->assertFileExists($this->newProjectDirectory . '/tests/behat/local.yml');
  }

  public function testCleanUp() {
    $this->assertFileNotExists($this->newProjectDirectory . '/install');
    $this->assertFileNotExists($this->newProjectDirectory . '/src/ProjectTemplate');
  }

  public function testInitializeGit() {
    // @todo Assert git remote configuration.
    $this->assertFileExists($this->newProjectDirectory . '/.git');
    $this->assertFileExists($this->newProjectDirectory . '/.git/hooks/commit-msg');
    $this->assertFileExists($this->newProjectDirectory . '/.git/hooks/pre-commit');
  }

  public function testAddVm() {
    // @todo Assert vm configuration in box/config.yml.
    $this->assertFileExists($this->newProjectDirectory . '/box/Vagrantfile');
  }
}
