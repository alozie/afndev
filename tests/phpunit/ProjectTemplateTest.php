<?php

/**
 * @file
 * PHP Unit tests for Project Template itself.
 */

namespace Drupal;

use Symfony\Component\Yaml\Yaml;

/**
 * Class ProjectTemplateTest.
 *
 * Verifies that project structure and configuration matches Project Template
 * standards.
 */
class ProjectTemplateTest extends \PHPUnit_Framework_TestCase
{

  /**
   * Class constructor.
   */
    public function __construct()
    {
        $this->projectDirectory = realpath(dirname(__FILE__) . '/../../');
        $this->config = Yaml::parse(file_get_contents("{$this->projectDirectory}/project.yml"));
    }

  /**
   * Tests Phing pt:create target.
   */
    public function testProjectTemplateCreate()
    {
        $new_project_dir = dirname($this->projectDirectory) . '/' . $this->config['project']['acquia_subname'];
        $this->assertFileExists($new_project_dir);
        $this->assertFileNotExists($new_project_dir . '/install');
        $this->assertNotContains('pt:', $new_project_dir . '/.travis.yml');
        $this->assertFileNotExists($new_project_dir . '/build/tasks/project-template.xml');
        $this->assertNotContains(
            'project-template',
            file_get_contents($new_project_dir . '/build/phing/build.xml')
        );
        $this->assertNotContains(
            'Project Template',
            file_get_contents($new_project_dir . '/build/phing/build.xml')
        );
        $this->assertNotContains(
            '${project.acquia_subname}',
            file_get_contents($new_project_dir . '/sites/default/settings.php')
        );
        $this->assertNotContains(
            '${project.human_name}',
            file_get_contents($new_project_dir . '/docs/architecture.md')
        );
    }

  /**
   * Tests Phing setup:make target.
   *
   * This should build /make.yml.
   */
    public function testSetupMake()
    {
        $this->assertFileExists($this->projectDirectory . '/docroot/index.php');
        $this->assertFileExists($this->projectDirectory . '/docroot/sites/all/modules/contrib');
    }

  /**
   * Tests Phing setup:behat target.
   */
    public function testSetupBehat()
    {
        // Assert that a local.yml file was created in the new project.
        $this->assertFileExists($this->projectDirectory . '/tests/behat/local.yml');
        $behat_config = Yaml::parse(file_get_contents("{$this->projectDirectory}/tests/behat/local.yml"));

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
        $this->assertFileExists($this->projectDirectory . '/.git');
        $this->assertFileExists($this->projectDirectory . '/.git/hooks/commit-msg');
        $this->assertFileExists($this->projectDirectory . '/.git/hooks/pre-commit');
    }

  /**
   * Tests Phing vm:add target.
   */
    public function testVmAdd()
    {
        // @todo Assert vm configuration in box/config.yml.
        $this->assertFileExists($this->projectDirectory . '/box/Vagrantfile');
        $this->assertFileExists($this->projectDirectory . '/box/config.yml');

        // Asserts that all Phing properties have been expanded.
        $this->assertNotContains('${', file_get_contents($this->projectDirectory . '/box/config.yml'));
    }
}
