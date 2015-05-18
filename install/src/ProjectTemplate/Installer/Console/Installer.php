<?php

namespace ProjectTemplate\Installer\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Process\Process;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Dumper;
use Github\Client;

class Installer extends Command {

  /**
   * @var \Symfony\Component\Filesystem\Filesystem
   */
  protected $fs;

  /**
   * The current project directory path on the local machine.
   * @var string
   */
  public $currentProjectDirectory;

  /**
   * The new project directory path on the local machine.
   * @var string
   */
  public $newProjectDirectory;

  /**
   * An array of installation configuration options.
   * @var array
   */
  public $config;

  /**
   * The commandline input.
   * @var InputInterface
   */
  protected $input;

  /**
   * The commandline output.
   * @var OutputInterface
   */
  protected $output;

  /**
   * @param \Symfony\Component\Filesystem\Filesystem $fs
   * @param string|null $name
   */
  public function __construct(Filesystem $fs, $name = NULL) {
    parent::__construct($name);
    // Instantiate file system component.
    $this->fs = $fs;

    // Determine system path of the current project.
    $this->currentProjectDirectory = realpath(dirname(__FILE__) . '/../../../../../');

    // Load default configuration from config.yml.
    $this->config = Yaml::parse(file_get_contents("{$this->currentProjectDirectory}/config.yml"));
  }

  /**
   * @see http://symfony.com/doc/current/components/console/introduction.html#creating-a-basic-command
   */
  protected function configure() {
    $this
      ->setName('install')
      ->setDescription('Create a new project using the Acquia PS Project Template.');
  }

  /**
   * @param InputInterface $input
   * @param OutputInterface $output
   */
  protected function interact(InputInterface $input, OutputInterface $output) {
    // Store the input and output for use in other functions.
    $this->input = $input;
    $this->output = $output;

    $helper = $this->getHelper('question');

    // Check if the proposed directory already exists.
    $newProjectDirectory = dirname($this->currentProjectDirectory) . '/' . $this->config['project']['machine_name'];
    if ($this->fs->exists($newProjectDirectory)) {
      $helper = $this->getHelper('question');
      $input = $this->input;
      $output = $this->output;

      // Confirm it is okay to overwrite the directory.
      $confirm_overwrite = new ConfirmationQuestion(sprintf('This operation will overwrite files in %s. Continue? ', $newProjectDirectory), 0);
      $overwrite_confirmed = $helper->ask($input, $output, $confirm_overwrite);
      if (!$overwrite_confirmed) {
        throw new \RuntimeException(
            'Please choose another machine name.'
        );
      }
    }

    // Set the new project directory name.
    $this->newProjectDirectory = $newProjectDirectory;
  }

  /**
   * @{inheritdoc}
   *
   * @throws \RuntimeException
   * @throws \Symfony\Component\Filesystem\Exception\IOException
   */
  protected function execute(InputInterface $input, OutputInterface $output) {
    $this->createProject($input, $output);

    // Add Vagrant VM.
    if ($this->config['vm']['enable']) {
      $this->addVm($input, $output);
    }

    // Download Drupal by executing makefile.
    $this->buildMakeFile($input, $output);

    $this->installTestingFramework($input, $output);

    // Clean up new project.
    $this->cleanUp($input, $output);

    // Display completion messages.
    $output->writeln("<info>You should now have a working copy of the project configured in the folder {$this->newProjectDirectory}.</info>");

    if ($this->config['starter_settings']['enable']) {
      // @todo Modify settings.php to include this. Also include other partials.
      $output->writeln("<info>Please include {$this->config['project']['machine_name']}/conf/base.settings.php in your settings.php file.</info>");
    }

    if ($this->config['vm']['enable']) {
      $output->writeln("<info>To set up the Drupal VM, follow the Quick Start Guide at http://www.drupalvm.com</info>");
      // @todo Automatically install role dependencies if Ansible is installed.
    }
  }

  /**
   * Creates new directory and populates with fundamental files & directories.
   *
   * This will copy the Project Template directory to a new destination and
   * initialize a new git repository there. Artifacts will be cleaned up in
   * cleanUp().
   *
   * @param InputInterface $input
   * @param OutputInterface $output
   */
  protected function createProject(InputInterface $input, OutputInterface $output) {
    $output->writeln("<info>Creating directory {$this->newProjectDirectory}.</info>");
    $output->writeln('<info>Copying Project Template into the new directory...</info>');

    // Clone Acquia's PSO Project Template repository and then remove the .git files.
    $mirror_options = array('override' => TRUE);
    $this->fs->mirror($this->currentProjectDirectory, $this->newProjectDirectory, NULL, $mirror_options);
    $this->remove($this->newProjectDirectory . '/.git');
    $this->git('init', array($this->newProjectDirectory));
    // Install .git hooks into the new project
    $mirror_options = array('override' => TRUE);
    $this->fs->mirror($this->currentProjectDirectory . '/git/hooks', $this->newProjectDirectory . '/.git/hooks', NULL, $mirror_options);
  }

  /**
   * Builds the chosen make file to the new project's docroot.
   *
   * The make file will be copied to the new project as well.
   *
   * @param InputInterface $input
   * @param OutputInterface $output
   */
  protected function buildMakeFile(InputInterface $input, OutputInterface $output) {
    $options = array(
      'no-gitinfofile' => NULL,
      'concurrency' => 8,
      'force-complete' => NULL,
    );
    $build_path = "{$this->newProjectDirectory}/docroot";
    // Make sure that the build path does not exist before building.
    $this->remove($build_path);
    // Build custom make file.
    $make_file = "{$this->newProjectDirectory}/scripts/{$this->config['project']['make_file']}";

    $output->writeln("<info>Building $make_file to $build_path...</info>");
    $this->drush('make', array($make_file, $build_path), $options);

    // @todo We need to make it clear which make file was built in the new
    // repository, perhaps by removing other make files or copying the used
    // file into a new location.
  }

  /**
   * Download Drupal VM for the project.
   *
   * This is accomplished through a `git clone` to the 'box' directory. The
   * '.git' directory is then removed from 'box'.
   *
   * @param InputInterface $input
   * @param OutputInterface $output
   */
  protected function addVm(InputInterface $input, OutputInterface $output) {
    $output->writeln('<info>Cloning Drupal VM from GitHub...</info>');

    // Add Drupal VM Vagrant box repository and then remove the .git files.
    $vm_dir = $this->config['vm']['dir_name'];
    $this->remove("{$this->newProjectDirectory}/$vm_dir");
    $this->git('clone', array(
      "git@github.com:geerlingguy/drupal-vm.git",
      "{$this->newProjectDirectory}/$vm_dir",
    ));
    $this->remove("{$this->newProjectDirectory}/$vm_dir/.git");

    $this->addVmConfig($input, $output);
  }

  /**
   * Configure Drupal VM for the project.
   *
   * @param InputInterface $input
   * @param OutputInterface $output
   */
  protected function addVmConfig(InputInterface $input, OutputInterface $output) {
    $vm_dir = $this->config['vm']['dir_name'];

    // Load the example configuration file included with Drupal VM.
    $vm_config = Yaml::parse(file_get_contents("{$this->newProjectDirectory}/$vm_dir/example.config.yml"));

    // Add the scripts directory to synced folders list.
    $vm_config['vagrant_synced_folders'][] = array(
      'local_path' => "{$this->newProjectDirectory}/scripts",
      'destination' => '/scripts',
      'id' => 'project_template_scripts',
      'type' => 'nfs',
    );

    // Use the docroot as the site's primary synced folder.
    $docroot = "/var/www/{$this->config['project']['machine_name']}";
    $vm_config['vagrant_synced_folders'][0]['local_path'] = "{$this->newProjectDirectory}/docroot";
    $vm_config['vagrant_synced_folders'][0]['destination'] = $docroot;

    // Update domain configuration.
    $vm_config['vagrant_hostname'] = $this->config['project']['local_url'];
    $vm_config['drupal_domain'] = $this->config['project']['local_url'];
    $vm_config['drupal_site_name'] = $this->config['project']['human_name'];
    $vm_config['drupal_core_path'] = $docroot;

    // Update the path to make file.
    $make_file = $this->config['project']['make_file'];
    $vm_config['drush_makefile_path'] = '/scripts/' . $make_file;
    // Remove makefile extension.
    $vm_config['drupal_install_profile'] = preg_replace('(\.make|\.yml)', '', $make_file);

    // Update other important settings.
    $vm_config['drupal_enable_modules'] = [];
    $vm_config['extra_apt_packages'] = [];

    // Write adjusted config.yml to disk.
    $this->fs->dumpFile("{$this->newProjectDirectory}/$vm_dir/config.yml", Yaml::dump($vm_config));

    $output->writeln("<info>Drupal VM was installed to `{$this->config['project']['machine_name']}/box`.</info>");
  }

  /**
   * Install a testing framework for the new project.
   *
   * @param InputInterface $input
   * @param OutputInterface $output
   *
   * @return bool
   *   Returns FALSE if testing framework was not installed.
   */
  protected function installTestingFramework(InputInterface $input, OutputInterface $output) {

    if (!$this->config['testing_framework']['enable']) {
      $this->remove( "{$this->newProjectDirectory}/tests");

      return FALSE;
    }

    // @todo Write drupal_root, base_url, and other settings to local.yml.
    // @todo Provide default behat profiles for dev and stg envs on ACE.
    // @todo Install behat runner module?

    /*
    $output->writeln("<info>Installing composer dependencies for testing framework...</info>");
    $options = array(
      'working-dir' => 'tests/behat'
    );
    $this->composer('install', array(), $options);
    */
  }

  /**
   * Remove installation artifacts from the new project.
   *
   * @param InputInterface $input
   * @param OutputInterface $output
   */
  protected function cleanUp(InputInterface $input, OutputInterface $output) {
    $output->writeln("<info>Cleaning up install files...</info>");

    // Clean up files specific to installation process.
    $this->remove(array(
      "{$this->newProjectDirectory}/src/ProjectTemplate",
      "{$this->newProjectDirectory}/install.md",
      "{$this->newProjectDirectory}/install",
    ));
  }

  /**
   * @param $command
   * @param array $arguments
   * @param array $options
   * @return mixed
   */
  protected function composer($command, array $arguments = array(), array $options = array()) {
    $this->customCommand('composer', $command, $arguments, $options);
  }

  /**
   * @param $command
   * @param array $arguments
   * @param array $options
   * @return mixed
   */
  protected function git($command, array $arguments = array(), array $options = array()) {
    $this->customCommand('git', $command, $arguments, $options);
  }

  /**
   * @param $command
   * @param array $arguments
   * @param array $options
   * @return mixed
   */
  protected function drush($command, array $arguments = array(), array $options = array()) {
    $this->customCommand('drush', $command, $arguments, $options);
  }

  /**
   * @param string $command
   *   The binary to call. E.g., 'git'.
   * @param array $arguments
   *   An array of arguments. E.g., 'pull origin/master'.
   * @param array $options
   *   An array of options in one of the following formats:
   *     array('bare' => NULL, 'tags' => 'smoke') will translate into
   *     `--bare --tags=smoke`
   * @return mixed
   *   Returns the command output.
   *
   * @todo Replace this with a real implementation of Symfony command class.
   */
  protected function customCommand($binary, $command, array $arguments = array(), array $options = array()) {
    $arguments = implode(' ', $arguments);
    $string_options = '';

    foreach ($options as $name => $value) {
      if (is_null($value)) {
        $string_options .= ' --' . $name;
      }
      else {
        $string_options .= ' --' . $name . '=' . $value;
      }
    }

    $command = "$binary {$command} {$string_options} {$arguments}";
    $process = new Process($command);
    $process->setTimeout(3600);
    $process->run(function ($type, $buffer) {
      print $buffer;
    });

    if (!$process->isSuccessful()) {
      throw new \RuntimeException($process->getErrorOutput());
    }

    return $process->getOutput();
  }

  /**
   * @param string $dir
   * @param bool $overwrite
   */
  public function mkdir($dir, $overwrite = FALSE) {

    if ($overwrite) {
      if ($this->fs->exists($dir)) {
        $this->fs->remove($dir);
      }
    }

    return $this->fs->mkdir($dir, 0755);
  }

  /**
   * @param $files
   * @return mixed
   */
  public function remove($files) {
    return $this->fs->remove($files);
  }

  /**
   * @param string $originDir
   * @param string $targetDir
   * @param bool $copyOnWindows
   * @return mixed
   */
  public function symlink($originDir, $targetDir, $copyOnWindows = FALSE) {
    return $this->fs->symlink($originDir, $targetDir, $copyOnWindows = FALSE);
  }

  /**
   * @param string $message
   * @param OutputInterface $output
   * @param ProgressHelper $progress
   */
  protected function writeProgressMessage($message, $output) {
    $output->writeln('');
    $output->writeln($message);
  }
}
