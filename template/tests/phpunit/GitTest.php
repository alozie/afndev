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
     *
     * @dataProvider providerTestGitHookCommitMsg
     */
    public function testGitHookCommitMsg($expected, $commit_message, $message = null)
    {
        $this->assertCommitMessageValidity($commit_message, $expected, $message);
    }

    /**
     * Data provider.
     */
    public function providerTestGitHookCommitMsg()
    {
        $prefix = $this->config['project']['prefix'];
        return array(
            array(false, "This is a bad commit.", 'Missing prefix and ticket number.'),
            array(false, "123: This is a bad commit.", 'Missing project prefix.'),
            array(false, "{$prefix}: This is a bad commit.", 'Missing ticket number.'),
            array(false, "{$prefix}-123 This is a bad commit.", 'Missing colon.'),
            array(false, "{$prefix}-123: This is a bad commit", 'Missing period.'),
            array(false, "{$prefix}-123: Hello.", 'Too short.'),
            array(false, "NOT-123: This is a bad commit.", 'Wrong project prefix.'),
            array(true, "{$prefix}-123: This is a good commit.", 'Good commit.'),
            array(true, "{$prefix}-123: This is an exceptionally long--seriously, really, really, REALLY long, but ' .
              'still good commit.", 'Long good commit.'),
        );
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

    /**
     * Asserts that a given commit message is valid or not.
     *
     * @param $commit_message
     * @param $is_valid
     * @param string $message
     *
     * @return array
     */
    protected function assertCommitMessageValidity($commit_message, $is_valid, $message = '')
    {
        // Commits must be executed inside of new project directory.
        chdir($this->projectDirectory);

        // "2>&1" redirects standard error output to standard output.
        $command = "git commit --amend -m '$commit_message' 2>&1";
        print "Executing \"$command\" \n";

        $output = shell_exec($command);
        $invalid_commit_text = 'Invalid commit message';
        $output_contains_invalid_commit_text = (bool) strstr($output, $invalid_commit_text);
        $this->assertNotSame($is_valid, $output_contains_invalid_commit_text, $message);
    }
}
