<?php

/**
 * @file
 * Test git hooks.
 */

namespace Drupal\Tests\PHPUnit;

/**
 * Class GitTasksTest.
 *
 * Verifies that git related tasks work as expected.
 */
class GitTasksTest extends TestBase
{

    /**
     * Tests Phing setup:git-hooks target.
     */
    public function testGitConfig()
    {
        $this->assertFileExists($this->projectDirectory . '/.git');
        $this->assertFileExists($this->projectDirectory . '/.git/hooks/commit-msg');
        $this->assertFileExists($this->projectDirectory . '/.git/hooks/pre-commit');
        $this->assertNotContains(
            '${project.prefix}',
            file_get_contents($this->projectDirectory . '/.git/hooks/commit-msg')
        );
    }

    /**
     * Tests operation of scripts/git-hooks/commit-msg.
     */
    public function testGitHookCommitMsg()
    {
        // Commits must be executed inside of new project directory.
        chdir($this->projectDirectory);

        $prefix = $this->config['project']['prefix'];
        $bad_commit_msgs = array(
            "This is a bad commit.", // Missing prefix and ticket number.
            "123: This is a bad commit.", // Missing project prefix.
            "$prefix: This is a bad commit.", // Missing ticket number.
            "$prefix-123 This is a bad commit.", // Missing colon.
            "$prefix-123: This is a bad commit", // Missing period.
            "$prefix-123: Hello.", // Too short.
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

    /**
     * Tests operation of scripts/git-hooks/pre-commit.
     *
     * Should assert that code validation via phpcs is functioning.
     */
    public function testGitPreCommitHook()
    {
        // Commits must be executed inside of new project directory.
        chdir($this->projectDirectory);
        $prefix = $this->config['project']['prefix'];
        $command = "git commit --amend -m '$prefix-123: This is a good commit.' 2>&1";
        $output = shell_exec($command);
        $this->assertNotContains('PHP Code Sniffer was not found', $output);
        $this->assertContains('Sniffing staged files via PHP Code Sniffer.', $output);
    }
}
