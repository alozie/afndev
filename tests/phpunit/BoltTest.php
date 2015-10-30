<?php

/**
 * @file
 * PHP Unit tests for Bolt itself.
 */

namespace Drupal;

use Symfony\Component\Yaml\Yaml;

/**
 * Class BoltTest.
 *
 * Verifies that project structure and configuration matches Bolt
 * standards.
 */
class BoltTest extends \PHPUnit_Framework_TestCase
{

  /**
   * Class constructor.
   */
    public function __construct()
    {
        $this->projectDirectory = realpath(dirname(__FILE__) . '/../../');
        $this->config = Yaml::parse(file_get_contents("{$this->projectDirectory}/project.yml"));
        $this->config = array_merge($this->config, Yaml::parse(file_get_contents("{$this->projectDirectory}/local.yml")));
        $this->new_project_dir = dirname($this->projectDirectory) . '/' . $this->config['project']['acquia_subname'];
    }

  /**
   * Tests Phing pt:create target.
   */
    public function testBoltCreate()
    {
        $this->assertFileExists($this->new_project_dir);
        $this->assertFileNotExists($this->new_project_dir . '/install');
        $this->assertFileNotExists($this->new_project_dir . '/tests/phpunit/Bolt.php');
        $this->assertNotContains(
            'pt:self-test',
            file_get_contents($this->new_project_dir . '/.travis.yml')
        );
        $this->assertFileNotExists($this->new_project_dir . '/build/tasks/bolt.xml');
        $this->assertNotContains(
            'bolt',
            file_get_contents($this->new_project_dir . '/build/phing/build.xml')
        );
        $this->assertNotContains(
            'Bolt',
            file_get_contents($this->new_project_dir . '/build/phing/build.xml')
        );
        $this->assertNotContains(
            '${project.acquia_subname}',
            file_get_contents($this->new_project_dir . '/sites/default/settings.php')
        );
        $this->assertNotContains(
            '${project.human_name}',
            file_get_contents($this->new_project_dir . '/readme/architecture.md')
        );
    }

  /**
   * Tests Phing setup:make target.
   *
   * This should build /make.yml.
   */
    public function testSetupMake()
    {
        $this->assertFileExists($this->new_project_dir . '/docroot/index.php');
        $this->assertFileExists($this->new_project_dir . '/docroot/modules/contrib');
        $this->assertFileExists($this->new_project_dir . '/docroot/themes/custom');
    }

    /**
     * Tests Phing setup:drupal:settings target.
     */
    public function testSetupLocalSettings() {
        $this->assertFileExists($this->new_project_dir . '/sites/default/settings/settings.local.php');
    }

  /**
   * Tests Phing setup:behat target.
   */
    public function testSetupBehat()
    {
        // Assert that a local.yml file was created in the new project.
        $this->assertFileExists($this->new_project_dir . '/tests/behat/local.yml');
        $behat_config = Yaml::parse(file_get_contents("{$this->new_project_dir}/tests/behat/local.yml"));

        // Assert that its values were modified to match values defined in
        // project's config.yml file.
        $this->assertEquals(
            $behat_config['local']['extensions']['Behat\MinkExtension']['base_url'],
            $this->config['project']['local_url']
        );
    }

  /**
   * Tests Phing setup:git-hooks target.
   */
    public function testGitConfig()
    {
        $this->assertFileExists($this->new_project_dir . '/.git');
        $this->assertFileExists($this->new_project_dir . '/.git/hooks/commit-msg');
        $this->assertFileExists($this->new_project_dir . '/.git/hooks/pre-commit');
      $this->assertNotContains(
        '${project.prefix}',
        file_get_contents($this->new_project_dir . '/.git/hooks/commit-msg')
      );
    }
}
