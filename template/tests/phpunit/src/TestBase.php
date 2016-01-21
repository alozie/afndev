<?php
/**
 * @file
 * Test base for project PHPUnit tests.
 */

namespace Drupal\Tests\PHPUnit;

use Symfony\Component\Yaml\Yaml;

/**
 * Class TestBase.
 *
 * Verifies that behat configuration is as expected.
 */
abstract class TestBase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var string
     *   The project root directory on the local machine.
     */
    protected $projectDirectory;
    /**
     * @var string
     *   The docroot directory on the local machine.
     */
    protected $drupalRoot;
    /**
     * @var array
     *   The yaml configuration from project.yml.
     */
    protected $config;
   /**
     * Class constructor.
     */
    public function __construct()
    {
        $this->projectDirectory = dirname(dirname(dirname(__DIR__)));
        $this->drupalRoot = $this->projectDirectory . '/docroot';
        $this->config = Yaml::parse(file_get_contents("{$this->projectDirectory}/project.yml"));
    }
}
