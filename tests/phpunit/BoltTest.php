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
        $this->assertFileExists($this->new_project_dir . '/vendor');
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
            '${project.acquia_subname}',
            file_get_contents($this->new_project_dir . '/tests/phpunit/SettingsTest.php')
        );
        $this->assertNotContains(
            '${project.human_name}',
            file_get_contents($this->new_project_dir . '/readme/architecture.md')
        );

        $profile_dir = $this->new_project_dir . '/profiles/' . $this->config['project']['profile']['name'];
        // Test new installation profile.
        $this->assertFileExists($profile_dir . '/' . $this->config['project']['profile']['name'] . '.info');
        $this->assertFileExists($profile_dir . '/' . $this->config['project']['profile']['name'] . '.profile');
        $this->assertFileExists($profile_dir . '/' . $this->config['project']['profile']['name'] . '.install');
        $this->assertNotContains(
            '${project.profile.name}',
            file_get_contents($profile_dir . '/' . $this->config['project']['profile']['name'] . '.install')
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
        $this->assertFileExists($this->new_project_dir . '/docroot/sites/all/modules/contrib');
    }

    /**
     * Tests Phing setup:drupal:settings target.
     */
    public function testSetupLocalSettings()
    {
        $this->assertFileExists($this->new_project_dir . '/sites/default/settings/local.settings.php');
    }

    /**
     * Tests Phing setup:behat target.
     */
    public function testSetupBehat()
    {
        // Assert that a local.yml file was created in the new project.
        $this->assertFileExists($this->new_project_dir . '/tests/behat/local.yml');
        // $behat_config = Yaml::parse(file_get_contents("{$this->new_project_dir}/tests/behat/local.yml"));

        $this->assertNotContains(
            '${local_url}',
            file_get_contents("{$this->new_project_dir}/tests/behat/local.yml")
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

    /**
     * Tests operation of scripts/git-hooks/commit-msg.
     */
    public function testGitHookCommitMsg()
    {
        // Commits must be executed inside of new project directory.
        chdir($this->new_project_dir);
        $bad_commit_msgs = array(
            "This is a bad commit.", // Missing prefix and ticket number.
            "123: This is a bad commit.", // Missing project prefix.
            "BLT: This is a bad commit.", // Missing ticket number.
            "BLT-123 This is a bad commit.", // Missing colon.
            "BLT-123: This is a bad commit", // Missing period.
            "BLT-123: Hello.", // Too short.
        );
        foreach ($bad_commit_msgs as $bad_commit_msg) {
            // "2>&1" redirects standard error output to standard output.
            $command = "git commit --amend -m '$bad_commit_msg' 2>&1";
            print "Executing \"$command\" \n";
            $output = shell_exec($command);
            $this->assertContains('Invalid commit message', $output);
        }
        $good_commit_msgs = array(
            "BLT-123: This is a good commit.",
        );
        foreach ($good_commit_msgs as $good_commit_msg) {
            // "2>&1" redirects standard error output to standard output.
            $command = "git commit --amend -m '$good_commit_msg' 2>&1";
            print "Executing \"$command\" \n";
            $output = shell_exec($command);
            $this->assertNotContains('Invalid commit message', $output);
        }
    }
}
