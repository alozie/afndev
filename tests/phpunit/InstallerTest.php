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
    $this->assertFileExists($this->newProjectDirectory . '/docroot/sites/all/drush');
    $this->assertFileExists($this->newProjectDirectory . '/docroot/sites/all/settings');
  }

  public function testBuildMakeFile() {
    $this->assertFileExists($this->newProjectDirectory . '/docroot/index.php');
  }

  public function testInstallTestingFramework() {
    // Assert that a local.yml file was created in the new project.
    $this->assertFileExists($this->newProjectDirectory . '/tests/behat/local.yml');
    $behat_config = Yaml::parse(file_get_contents("{$this->newProjectDirectory}/tests/behat/local.yml"));

    // Assert that its values were modified to match values defined in
    // project's config.yml file.
    $this->assertEquals($behat_config['local']['extensions']['Behat\MinkExtension']['base_url'], $this->config['project']['local_url']);
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
