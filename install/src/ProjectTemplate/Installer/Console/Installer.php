<?php

/**
 * @file
 */

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
/**
 *
 */
class Installer extends Command {

  /**
   * @var \Symfony\Component\Filesystem\Filesystem
   */
  protected $fs;

  /**
   * The current project directory path on the local machine.
   *
   * @var string
   */
  public $currentProjectDirectory;

  /**
   * The new project directory path on the local machine.
   *
   * @var string
   */
  public $newProjectDirectory;

  /**
   * An array of installation configuration options.
   *
   * @var array
   */
  public $config;

  /**
   * The commandline input.
   *
   * @var InputInterface
   */
  protected $input;

  /**
   * The commandline output.
   *
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
      ->setDescription('Create a new project using the Acquia PS Project Template.')
      ->addOption(
        'overwrite',
        NULL,
        InputOption::VALUE_NONE,
        'If set, the target directory will be overwritten without prompt')
      ->addOption(
        'temporary',
        NULL,
        InputOption::VALUE_NONE,
        "If set, the project will be installed to the current repository's temp directory");
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

    // If the "temporary" flag has been set, install the project to the
    // current repository's "tmp" directory. This may be required for installing
    // via continuous integration, where access outside of project repo is
    // restricted.
    if ($input->getOption('temporary')) {
      $newProjectDirectory = $this->currentProjectDirectory . '/tmp/' . $this->config['project']['machine_name'];
    }
    // Otherwise, make the new project a sibling of the current repo dir.
    else {
      $newProjectDirectory = dirname($this->currentProjectDirectory) . '/' . $this->config['project']['machine_name'];
    }

    // Check if the proposed directory already exists.
    if ($this->fs->exists($newProjectDirectory) && !$input->getOption('overwrite') && !$input->getOption('temporary')) {
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

    // Download Drupal by executing makefile.
    $this->buildMakeFile($input, $output);

    // Set up testing framework(s).
    $this->installTestingFramework($input, $output);

    // Configure documentation.
    $this->initializeDocumentation($input, $output);

    // Clean up new project.
    $this->cleanUp($input, $output);

    // Create table of contents.
    $this->initializeTableOfContents($input, $output);

    // Initialize Git.
    $this->initializeGit($input, $output);

    // Display completion messages.
    $output->writeln("<info>You should now have a working copy of the project configured in the folder {$this->newProjectDirectory}.</info>");

    if ($this->config['vm']) {
      $this->addVm($input, $output);
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

    $output->writeln('<info>Copying Project Template into the new directory...</info>');

    // Clone Acquia's PSO Project Template repository and then remove the .git files.
    $mirror_options = array('override' => TRUE);
    $this->fs->remove($this->newProjectDirectory);
    $this->fs->mirror($this->currentProjectDirectory, $this->newProjectDirectory, NULL, $mirror_options);

    $output->writeln('<info>Removing Project Templates git directory</info>');
    $this->remove($this->newProjectDirectory . '/.git');

    // Replace project template readme with a project one
    $output->writeln('<info>Creating project-specific onboarding readme</info>');
    $this->fs->copy($this->newProjectDirectory . '/project.readme.md', $this->newProjectDirectory . '/readme.md', TRUE);
  }

  /**
   * Performs the actions needed to set up the project's documentation.
   *
   * @param InputInterface $input
   * @param OutputInterface $output
   */
  protected function initializeDocumentation(InputInterface $input, OutputInterface $output) {

    $output->writeln('<info>Initializing new project documentation directory</info>');

    // Copy a clone of docs.
    $this->fs->mirror($this->newProjectDirectory . '/docs', $this->newProjectDirectory . '/docs-temp');

    // Empty directory, but leave for project-specific documentation needs.
    $this->fs->remove($this->newProjectDirectory . '/docs');
    $this->fs->mkdir($this->newProjectDirectory . '/docs');

    // Look for configured docs.
    if (!empty($this->config['docs'])) {
      // Copy the defined documents.
      foreach ($this->config['docs'] as $doc => $description) {
        $output->writeln('<info>Copying doc ' . $description . '</info>');
        $this->fs->copy($this->newProjectDirectory . '/docs-temp/' . $doc . '.md', $this->newProjectDirectory . '/docs/' . $doc . '.md', TRUE);
      }
    }

    // Remove temp dir.
    $this->fs->remove($this->newProjectDirectory . '/docs-temp');

  }

  /**
   * Performs Git actions needed for project.
   *
   * @param InputInterface $input
   * @param OutputInterface $output
   */
  protected function initializeGit(InputInterface $input, OutputInterface $output) {

    $output->writeln('<info>Initializing new project git repository</info>');
    $this->git("init", array($this->newProjectDirectory));

    // Install .git hooks into the new project.
    if (!empty($this->config['git']['hooks'])) {
      $output->writeln('<info>Creating Git hooks directory</info>');
      $this->fs->mkdir($this->newProjectDirectory . '/.git/hooks');

      // Copy the desirable hooks.
      foreach ($this->config['git']['hooks'] as $hook) {
        $output->writeln('<info>Copying Git hook ' . $hook . '</info>');
        $this->fs->copy($this->currentProjectDirectory . '/git/hooks/' . $hook, $this->newProjectDirectory . '/.git/hooks/' . $hook, TRUE);
      }
    }

    // Load repositories.
    if (!empty($this->config['git']['remotes'])) {
      foreach ($this->config['git']['remotes'] as $remote_name => $remote_url) {
        $this->addRemoteRepository($input, $output, $remote_name, $remote_url);
      }
    }
  }

  /**
   * Adds a new remote for the project.
   *
   * @param InputInterface $input
   * @param OutputInterface $output
   * @param $name
   * @param $url
   */
  protected function addRemoteRepository(InputInterface $input, OutputInterface $output, $name, $url) {

    $this->writeProgressMessage("<info>Adding remote repository {$name}.</info>", $output, $this->progress);
    $this->git("-C {$this->newProjectDirectory} remote add", array($name, $url));
  }

  /**
   * Generates table of contents.
   *
   * @param InputInterface $input
   * @param OutputInterface $output
   */
  protected function initializeTableOfContents(InputInterface $input, OutputInterface $output) {

    $output->writeln('<info>Initializing new project-specific table of contents</info>');

    // Clear out the readme.
    $this->fs->remove($this->newProjectDirectory . '/docs/readme.md');

    // Generate the TOC.
    $readme_contents = '## Table of Contents';

    // Start out with creating entries for docs.
    if (!empty($this->config['docs'])) {
      $readme_contents .= "\n\n### Docs";
      // Create a line per doc.
      foreach ($this->config['docs'] as $doc => $description) {
        // * [Github](https://github.com/acquia-pso/PROJECT)
        $readme_contents .= "\n* [" . $description . "](docs/" . $doc . ".md)";
      }
    }

    // Write out contents.
    $this->fs->dumpFile($this->newProjectDirectory . '/docs/readme.md', $readme_contents);
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

    // We deleted the docroot and rebuilt it, so we'll need to copy files from
    // docroot/sites back into the new project.
    $this->fs->mirror("{$this->currentProjectDirectory}/docroot/sites", "{$this->newProjectDirectory}/docroot/sites", NULL, array('override' => TRUE));
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
    $this->git(
          'clone', array(
            "git@github.com:geerlingguy/drupal-vm.git",
            "{$this->newProjectDirectory}/$vm_dir",
          )
      );
    $this->remove("{$this->newProjectDirectory}/$vm_dir/.git");

    $this->addVmConfig($input, $output);
    $this->bootstrapVm($input, $output);

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

    // Do not execute subsequent drush make within the VM since files are in docroot.
    $vm_config['build_makefile'] = FALSE;
    $vm_config['install_site'] = TRUE;

    // Write adjusted config.yml to disk.
    $this->fs->dumpFile("{$this->newProjectDirectory}/$vm_dir/config.yml", Yaml::dump($vm_config, 4, 2));

    $output->writeln("<info>Drupal VM was installed to `{$this->config['project']['machine_name']}/box`.</info>");
  }

  /**
   * Load the VM on the host machine.
   *
   * @param InputInterface $input
   * @param OutputInterface $output
   */
  protected function bootstrapVm(InputInterface $input, OutputInterface $output) {

    if (!empty($this->config['vm']['bootstrap']) and $this->config['vm']['bootstrap']) {
      $output->writeln('<info>Bootstrapping Drupal VM...</info>');

      // Check for virtualbox.
      $output->writeln('<info>Checking for virtualbox</info>');
      $result = strtolower($this->customCommand('VBoxManage', 'list', array('vms')));
      if ($result == '-bash: vboxmanage: command not found') {
        $output->writeln('<info>Unmet dependency, please install virtualbox</info>');
        return;
      }

      // Check for vagrant.
      $output->writeln('<info>Checking for vagrant</info>');
      $result = strtolower($this->customCommand('vagrant', 'global-status'));
      if ($result == '-bash: vagrant: command not found') {
        $output->writeln('<info>Unmet dependency, please install vagrant</info>');
        return;
      }

      // Check for ansible.
      try{
        $output->writeln('<info>Checking for ansible</info>');
        $this->customCommand('ansible', '--version');
      }

     catch (\RuntimeException $re) {
        $output->writeln('<info>Unmet dependency, please install ansible</info>');
        exit(1);
      }

      if (!empty($this->config['vm']['rebuild_requirements']) and $this->config['vm']['rebuild_requirements']) {
        // Load ansible reqs.
        $output->writeln('<info>Loading ansible requirements. NOTE - you will be prompted to enter your sudo password</info>');
        $role_file = $this->newProjectDirectory . '/' . $this->config['vm']['dir_name'] . '/provisioning/requirements.txt';
        $result = strtolower($this->customCommand('sudo', 'ansible-galaxy', array('install --force'), array('role-file' => $role_file)));
      }

      // Load host manager.
      $output->writeln('<info>Loading host manager</info>');
      $result = strtolower($this->customCommand('vagrant', 'plugin install', array('vagrant-hostsupdater')));

      // Run Vagrant up from VM dir.
      $output->writeln('<info>Bootstrapping VM</info>');
      $result = strtolower($this->customCommand('(cd ' . $this->newProjectDirectory . '/' . $this->config['vm']['dir_name'] . ' && vagrant up )', ''));

    }
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

    if (!$this->config['testing_framework']) {
      $this->remove("{$this->newProjectDirectory}/tests");

      return FALSE;
    }

    // @todo Install behat runner module?
    $output->writeln("<info>Configuring Behat yml files...</info>");
    $behat_config = Yaml::parse(file_get_contents("{$this->currentProjectDirectory}/tests/behat/example.local.yml"));

    $behat_config['local']['extensions']['Drupal\DrupalExtension']['drupal']['drupal_root'] = "{$this->newProjectDirectory}/docroot";
    $behat_config['local']['extensions']['Behat\MinkExtension']['base_url'] = $this->config['project']['local_url'];
    $behat_config['local']['extensions']['Behat\MinkExtension']['javascript_session'] = $this->config['testing_framework']['behat']['javascript_driver'];

    // Write adjusted config.yml to disk.
    $this->fs->dumpFile("{$this->newProjectDirectory}/tests/behat/local.yml", Yaml::dump($behat_config, 4, 2));
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
    $this->remove(
          array(
            "{$this->newProjectDirectory}/src/ProjectTemplate",
            "{$this->newProjectDirectory}/install",
            "{$this->newProjectDirectory}/example.config.yml",
            "{$this->newProjectDirectory}/project.readme.md",
          )
      );
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
   * @param array $argumentsAn
   *   array of arguments. E.g., 'pull origin/master'.
   *   An array of arguments. E.g., 'pull origin/master'.
   * @param array $optionsAn
   *   array of options in one of the following formats:
   *   An array of options in one of the following formats:
   *    array('bare' => NULL, 'tags' => 'smoke') will translate into
   *    `--bare --tags=smoke`
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
    $process->run(
          function ($type, $buffer) {
              print $buffer;
          }
      );

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
