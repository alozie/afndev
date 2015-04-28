<?php

namespace ProjectTemplate\Installer\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Process\Process;
use Github\Client;

class Installer extends Command {

  /**
   * @var \Symfony\Component\Filesystem\Filesystem
   */
  protected $fs;

  /**
   * A progress helper.
   * @var object
   */
  protected $progress;

  /**
   * The machine name of the new project to create.
   * @var string
   */
  public $projectName;

  /**
   * The human readable name of the project to create.
   * @var string
   */
  public $projectTitle;

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
   * @param \Symfony\Component\Filesystem\Filesystem $fs
   * @param string|null $name
   */
  public function __construct(Filesystem $fs, $name = NULL) {
    parent::__construct($name);
    // Instantiate file system component.
    $this->fs = $fs;

    // Set defaults for configuration array.
    $this->config = array(
      'ubuia' => TRUE,
      'testing_framework' => array(
        'enable' => TRUE,
        // Default to second item in select list prompt, which is 'Zombie.js'.
        'javascript_session' => 1,
      ),
      'starter_settings' => array(
        'enable' => TRUE,
        'akamai' => TRUE,
      ),
      // Default to first item in select list prompt, which is 'standard'.
      'make-file' => 0,
    );

    // Determine system path of the current project.
    $this->currentProjectDirectory = realpath(dirname(__FILE__) . '/../../../../../');
  }

  /**
   * @see http://symfony.com/doc/current/components/console/introduction.html#creating-a-basic-command
   */
  protected function configure() {
    $this
      ->setName('install')
      ->setDescription('Create a new project using the Acquia PS Project Template.')
      ->addArgument(
        'make-file',
        InputArgument::OPTIONAL,
        'Custom make file to run in docroot'
      );
  }

  /**
   * @param InputInterface $input
   * @param OutputInterface $output
   */
  protected function interact(InputInterface $input, OutputInterface $output) {
    $helper = $this->getHelper('question');

    // Instantiate file progress helper.
    $this->progress = $this->getHelper('progress');

    // @todo Add validation to make sure this really is machine readable!

    $question = new Question('What is the machine name of this project? ');
    $question->setValidator(function ($answer) {
      // regex for valid php variable name from http://php.net/manual/en/language.variables.basics.php
      $pattern = '/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/';
      if (!preg_match($pattern, $answer)) {
        throw new \RuntimeException(
            'Please enter a valid machine name (hint: can\'t contain spaces)'
        );
      }
      return $answer;
    });
    $this->projectName = $helper->ask($input, $output, $question);

    $question = new Question('What is the human-readable name of this project? ');
    $this->projectTitle = $helper->ask($input, $output, $question);

    // Prompt for options related to virtual machine.
    $question = new ConfirmationQuestion('Include Ubuia Vagrant box? ', $this->config['ubuia']);
    $this->config['ubuia'] = $helper->ask($input, $output, $question);

    // Prompt for options related to testing framework.
    $question = new ConfirmationQuestion('Include the PS Automated Testing Framework? ', $this->config['testing_framework']);
    $this->config['testing_framework']['enable'] = $helper->ask($input, $output, $question);
    if ($this->config['testing_framework']['enable']) {
      $question = new Question('What will the local URL of the website be? ');
      $this->config['local_url'] = $helper->ask($input, $output, $question);

      $question = new ChoiceQuestion(
        'Which javascript session driver should be used for Behat testing?',
        array('Selenium2 / Phantom.js', 'Zombie.js'),
        $this->config['testing_framework']['javascript_session']
      );
      $this->config['testing_framework']['javascript_session'] = $helper->ask($input, $output, $question);
    }

    $question = new ConfirmationQuestion('Include a starter settings.php file? ', $this->config['starter_settings']['enable']);
    $this->config['starter_settings']['enable'] = $helper->ask($input, $output, $question);

    // Prompt for options related to starter settings.php file.
    $question = new ConfirmationQuestion('Add Akamai integration? ', $this->config['starter_settings']['akamai']);
    $this->config['starter_settings']['akamai'] = $helper->ask($input, $output, $question);

    $make_file = $input->getArgument('make-file');
    if (!$make_file) {
      $question = new ChoiceQuestion(
        'You did not specify a custom make file. Which default make file should be used?',
        array('standard', 'lightning'),
        $this->config['make-file']
      );
      $this->config['make-file'] = $helper->ask($input, $output, $question);
    }
  }

  /**
   * @{inheritdoc}
   *
   * @throws \RuntimeException
   * @throws \Symfony\Component\Filesystem\Exception\IOException
   */
  protected function execute(InputInterface $input, OutputInterface $output) {

    // Create a progress bar.
    $this->progress->start($output, 100);

    // Set the new project directory to be a sibling of the current project.
    $this->newProjectDirectory = dirname($this->currentProjectDirectory) . '/' . $this->projectName;
    $this->createProject($input, $output, $this->progress);
    $this->progress->advance(20);

    // Add Ubuia.
    if ($this->config['ubuia']) {
      $this->addUbuia($input, $output);
    }
    $this->progress->advance(20);

    // Download Drupal by executing makefile.
    $this->buildMakeFile($input, $output);
    $this->progress->advance(20);

    $this->installTestingFramework($input, $output);
    $this->progress->advance(20);

    // Clean up new project.
    $this->cleanUp($input, $output);

    // Display completion messages.
    $this->writeProgressMessage("<info>You should now have a working copy of the project configured in the folder {$this->$newProjectDirectory}.</info>", $output, $this->progress);

    if ($this->config['starter_settings']['enable']) {
      // @todo Modify settings.php to include this. Also include other partials.
      $this->writeProgressMessage("<info>Please include {$this->projectName}/conf/base.settings.php in your settings.php file.</info>", $output, $this->progress);
    }
    if ($this->config['ubuia']) {
      $this->writeProgressMessage("<info>Please run `vagrant up` from within `{$this->projectName}/box` and then install Drupal via `drush site-install`</info>", $output, $this->progress);
    }

    $this->progress->advance(20);
    $this->progress->clear();
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
    $this->writeProgressMessage("<info>Creating directory {$this->newProjectDirectory}.</info>", $output, $this->progress);
    $this->writeProgressMessage('<info>Copying Project Template into the new directory...</info>', $output, $this->progress);

    // Clone Acquia's PSO Project Template repository and then remove the .git files.
    // @todo Project user to confirm that overwriting is ok!

    $mirror_options = array('override' => TRUE);
    $this->fs->mirror($this->currentProjectDirectory, $this->newProjectDirectory, NULL, $mirror_options);
    $this->remove($this->newProjectDirectory . '/.git');
    $this->git('init', array($this->newProjectDirectory));
  }

  /**
   * Download the Ubuia repository to the new project.
   *
   * This is accomplished through a `git clone` to the 'box' directory. The
   * '.git' directory is then removed from 'box'.
   *
   * @todo Possibly change this to download only latest release.
   *
   * @param InputInterface $input
   * @param OutputInterface $output
   */
  protected function addUbuia(InputInterface $input, OutputInterface $output) {
    $this->writeProgressMessage('<info>Cloning Ubuia from GitHub...</info>', $output, $this->progress);
    // Add Ubuia Vagrant box repository and then remove the .git files.
    $vm_dir_name = 'box';
    $this->remove("{$this->newProjectDirectory}/$vm_dir_name");
    $this->git('clone --bare', array(
      "git@github.com:acquia-pso/ubuia.git",
      "{$this->newProjectDirectory}/$vm_dir_name"
    ));
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
    $make_file = $input->getArgument('make-file');
    if (!$make_file) {
      $make_file = "{$this->newProjectDirectory}/install/{$this->config['make-file']}.make";
    }

    $this->writeProgressMessage("<info>Building $make_file to $build_path...</info>", $output, $this->progress);
    $this->drush('make', array($make_file, $build_path), $options);

    // Copy make file to the scripts directory.
    $new_make_file = "{$this->newProjectDirectory}/scripts/" . basename($make_file);
    $this->fs->copy($make_file, $new_make_file);
    // @todo Also copy contrib.make and any other drush make includes.
  }

  /**
   * Install a testing framework for the new project.
   *
   * @param InputInterface $input
   * @param OutputInterface $output
   */
  protected function installTestingFramework(InputInterface $input, OutputInterface $output) {

    if (!$this->config['testing_framework']['enable']) {
      $this->remove( "{$this->newProjectDirectory}/tests");

      return FALSE;
    }

    $this->writeProgressMessage("<info>Installing composer dependencies for testing framework...</info>", $output, $this->progress);

    // @todo Write drupal_root, base_url, and other settings to local.yml.
    // @todo If ubuia enabled, write ubuia.yml to testing dir.
    // @todo Provide default behat profiles for dev and stg envs on ACE.
    // @todo Install behat runner module?

    /*
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
    $this->writeProgressMessage("<info>Cleaning up install files...</info>", $output, $this->progress);

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
   * @param array $arguments
   * @param array $options
   * @return mixed
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

    $process = new Process("$binary {$command} {$string_options} {$arguments}");
    $process->setTimeout(3600);
    $process->run();

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
   * @param string $dir
   * @return bool
   */
  public function chdir($dir) {
    return chdir($dir);
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
  protected function writeProgressMessage($message, $output, $progress) {
    $progress->clear();
    $output->writeln('');
    $output->writeln($message);
    $progress->display();
  }
}
