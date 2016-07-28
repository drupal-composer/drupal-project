<?php
/**
 * This is NCYL's console commands configuration for Robo task runner.
 *
 * @see http://robo.li/
 */

use Drupal\Component\Utility\Crypt;

// If robo is installed globally, this is needed to load the local autoloader.
require_once(__DIR__ . '/vendor/autoload.php');

class RoboFile extends \Robo\Tasks
{
  private $projectProperties;

  function __construct() {
    $this->projectProperties = $this->getProjectProperties();
  }

  /**
   * Generate configuration in your .env file.
   *
   * @option string db-pass Database password.
   * @option string db-user Database user.
   * @option string db-name Database name.
   * @option string db-host Database host.
   */
  function configure($opts = ['db-pass' => NULL, 'db-user' => NULL, 'db-name' => NULL, 'db-host' => NULL]) {

    $settings = $this->getDefaultPressflowSettings();

    // Use user environment settings if we have them.
    if ($system_defaults = getenv('DEFAULT_PRESSFLOW_SETTINGS')) {
      $settings = json_decode($system_defaults, TRUE);
    }

    // Loop through project properties and replace with command line arguments
    // if we have them.
    foreach ($opts as $opt => $value) {
      if ($value !== NULL) {
        // Ugly method to allow an empty param to be passed for the password.
        if ($value == 'NULL') {
          $value = '';
        }
        $this->projectProperties[$opt] = $value;
      }
    }

    // DB Name
    $settings['databases']['default']['default']['database'] = $this->projectProperties['db-name'];

    // Override DB username from project properties.
    if (isset($this->projectProperties['db-user'])) {
      $settings['databases']['default']['default']['username'] = $this->projectProperties['db-user'];
    }

    // Override DB password from project properties.
    if (isset($this->projectProperties['db-pass'])) {
      $settings['databases']['default']['default']['password'] = $this->projectProperties['db-pass'];
    }

    // Override DB host from project properties.
    if (isset($this->projectProperties['db-host'])) {
      $settings['databases']['default']['default']['host'] = $this->projectProperties['db-host'];
    }

    // Hash Salt.
    if (empty($this->projectProperties['hash_salt'])) {
      // If we don't have a salt, we generate one.
      $hash_salt = Crypt::randomBytesBase64(55);
      $this->projectProperties['hash_salt'] = $hash_salt;
      $this->taskWriteToFile('.env.dist')
        ->append()
        ->line('TS_HASH_SALT="' . $hash_salt . '"')
        ->run();
    }

    $settings['drupal_hash_salt'] = $this->projectProperties['hash_salt'];

    // Config Directory
    $settings['config_directory_name'] = $this->projectProperties['config_dir'];

    // Terminus env
    $branch = $this->projectProperties['branch'];
    $terminus_env = ($branch == 'master') ? 'dev' : $branch;

    $json_settings = json_encode($settings);

    $this->findReplaceProjectName();

    // Start with the dist env file.
    $this->_remove('.env');
    $this->_copy('.env.dist', '.env');

    $result = $this->taskWriteToFile('.env')
      ->append()
      ->line('# Generated configuration')
      ->line('PRESSFLOW_SETTINGS=' . $json_settings)
      ->line('TERMINUS_ENV=' . $terminus_env)
      ->run();

    return $result;
  }

  /**
   * Install dependencies and the actual Drupal site.
   *
   * @option boolean $pantheon Pantheon install.
   *
   * @return \Robo\Result
   */
  function install($opts = ['pantheon' => FALSE]) {
    $pantheon = $opts['pantheon'];
    $install_cmd = 'site-install config_installer -y';

    if ($pantheon) {
      $install_cmd = 'terminus drush "' . $install_cmd . '"';
      // Pantheon wants the site in SFTP for installs.
      $this->_exec('terminus site set-connection-mode --mode=sftp');

      // Even in SFTP mode, the settings.php file might have too restrictive
      // permissions. We use SFTP to chmod the settings file before installing.
      $sftp_command = trim($this->_exec('terminus site connection-info --field=sftp_command')->getMessage());
      $sftp_command = str_replace('sftp', 'sftp -b -', $sftp_command);
      $sftp_command .= ' << EOF
chmod 644 code/sites/default/settings.php
EOF';
      $this->_exec($sftp_command);

    }
    else {
      // Install dependencies. Only works locally.
      $this->taskComposerInstall()
        ->optimizeAutoloader()
        ->run();

      $this->_chmod('sites/default/settings.php', 0755);

      $install_cmd = 'drush ' . $install_cmd;
    }

    // Set our install to TRUE to disable file based config on install.
//    putenv("DRUPAL_INSTALL=TRUE");

    // Run the installation.
    $result = $this->taskExec($install_cmd)
      ->run();

    if ($pantheon) {
      // Put the site back into git mode.
      $this->_exec('terminus site set-connection-mode --mode=git');
    }

    if ($result->wasSuccessful()) {
      $this->say('Install complete');
    }

    return $result;
  }

  function info() {
    phpinfo();
  }
  /**
   * Run tests for this site. Currently just Behat.
   *
   * @option string url The url to test against.
   * @option string drush_param Drush drive parameters.
   * @option string feature Single feature file to run.
   *   Ex: --feature=features/user.feature.
   *
   * @return \Robo\Result
   */
  function test($opts = ['url' => '', 'drush_param' => '', 'feature' => NULL]) {

    // @TODO don't hard code this.
    $url = 'http://' . $this->projectProperties['project'] . '.dev';

    if ($opts['url'] != '') {
      $url = $opts['url'];
    }

    // Set the drush parameters from the options. This allows for aliases or a
    // root definition.
    $root = $this->projectProperties['web_root'];
    $drush_param = '"root":"' . $root . '"';
    if (!empty($opts['drush_param'])) {
      $drush_param = $opts['drush_param'];
    }

    // Add the specific behat config to our environment.
    putenv('BEHAT_PARAMS={"extensions":{"Behat\\\\MinkExtension":{"base_url":"' . $url . '"},"Drupal\\\\DrupalExtension":{"drupal":{"drupal_root":"' . $root . '"},"drush":{' . $drush_param . '}}}}');

    $behat_cmd = $this->taskExec('behat')
      ->arg('--config private/behat/behat.yml')
      ->arg(' --format progress');

    if ($opts['feature']) {
      $behat_cmd->arg($opts['feature']);
    }

    $behat_result = $behat_cmd->run();

    return $behat_result;

    // @TODO consider adding unit tests back in. These are slow and aren't working great right now.
//    $unit_result = $this->taskPHPUnit('../vendor/bin/phpunit')
//      ->dir('core')
//      ->run();
//
//    // @TODO will need to address multiple results when we enable other tests as well.
//    return $behat_result->merge($unit_result);
  }

  /**
   * Run tests against the Pantheon multidev.
   *
   * @option string feature Single feature file to run.
   *   Ex: --feature=features/user.feature.
   *
   * @return \Robo\Result
   */
  function pantheonTest($opts = ['feature' => NULL]) {
    $project = $this->projectProperties['project'];
    $branch = $this->projectProperties['branch'];
    $url = "https://$branch-$project.pantheonsite.io";
    $alias = "pantheon.$project.$branch";
    $drush_param = '"alias":"' . $alias . '"';
    return $this->test(['url' => $url, 'drush_param' => $drush_param, 'feature' => $opts['feature']]);
  }

  private function getProjectProperties() {

    // Load .env file from the local directory if it exists. Or use the .env.dist
    $env_file = (file_exists(__DIR__ . '/.env')) ? '.env' : '.env.dist';

    $dotenv = new Dotenv\Dotenv(__DIR__, $env_file);
    $dotenv->load();

    $properties = ['project' => '', 'hash_salt' => '', 'config_dir' => '', 'host_repo' => ''];

    array_walk($properties, function(&$var, $key) {
      $env_var = strtoupper('TS_' . $key);
      $var = getenv($env_var);
    });

    if ($web_root = getenv('TS_WEB_ROOT')) {
      $properties['web_root'] = __DIR__ . '/' . $web_root;
    }
    else {
      $properties['web_root'] = __DIR__;
    }

    $properties['escaped_web_root_path'] = $this->escapeArg($properties['web_root']);

    // Get the current branch using the simple exec command.
    $branch = exec('git symbolic-ref --short -q HEAD');

    if ($branch) {
      $properties['branch'] = $branch;
    }

    if ($db_name = getenv('TS_DB_NAME')) {
      $properties['db-name'] = $db_name;
    }
    else {
      $properties['db-name'] = $properties['project'] . '_' . $properties['branch'];
    }

    return $properties;
  }

  // See Symfony\Component\Console\Input.
  protected function escapeArg($string) {
    return preg_match('{^[\w-]+$}', $string) ? $string : escapeshellarg($string);
  }

  /**
   * Use regex to replace a 'key' => 'value', pair in a file like a settings file.
   *
   * @param $file
   * @param $key
   * @param $value
   */
  protected function replaceArraySetting($file, $key, $value) {
    $this->taskReplaceInFile($file)
      ->regex("/'$key' => '[^'\\\\]*(?:\\\\.[^'\\\\]*)*',/s")
      ->to("'$key' => '". $value . "',")
      ->run();
  }

  /**
   * Build temp folder path for the task.
   *
   * @return string
   */
  protected function getTmpDir() {
    return realpath(sys_get_temp_dir()) . DIRECTORY_SEPARATOR . 'drupal-deploy-' . time();
  }

  /**
   * Decide what our fetch directory should be named
   * (temporary location to stash scaffold files before
   * moving them to their final destination in the project).
   *
   * @return string
   */
  protected function getFetchDirName() {
    return 'host';
  }

  /**
   * Prepare a Pantheon multidev for this project/branch.
   *
   * @option boolean install Trigger an install on Pantheon.
   * @option boolean y Answer prompts with y.
   *
   * @return \Robo\Result
   */
  function pantheonDeploy($opts = ['install' => FALSE, 'y' => FALSE]) {
    $branch = $this->projectProperties['branch'];
    $result = $this->taskExec('terminus site environment-info')->run();

    // Check for existing multidev and prompt to create.
    if (!$result->wasSuccessful() && $branch != 'master') {
      if (!$opts['y']) {
        if (!$this->confirm('No matching multidev found. Create it?')) {
          return FALSE;
        }
      }
      $this->taskExec("terminus site create-env --to-env=$branch --from-env=dev")
        ->run();
    }

    // Make sure our site is awake.
    $this->_exec('terminus site wake');

    // Ensure we're in git mode.
    $this->_exec('terminus site set-connection-mode --mode=git');

    // Deployment
    $this->deploy();

    // Trigger remote install.
    if ($opts['install']) {
      $this->_exec('terminus site wipe --yes');
      return $this->install(array('pantheon' => TRUE));
    }
  }

  /**
   * Perform git checkout of host files.
   */
  function deploy() {

    $repo = $this->projectProperties['host_repo'];

    $branch = $this->projectProperties['branch'];

    $webroot = $this->projectProperties['web_root'];

    $tmpDir = $this->getTmpDir();
    $hostDirName = $this->getFetchDirName();
    $this->stopOnFail();
    $fs = $this->taskFilesystemStack()
      ->mkdir($tmpDir)
      ->mkdir("$tmpDir/$hostDirName")
      ->run();

    // Make sure we have an empty temp dir.
    $this->taskCleanDir([$tmpDir])
      ->run();

    // Git checkout of the matching remote branch.
    $this->taskGitStack()
      ->stopOnFail()
      ->cloneRepo($repo, "$tmpDir/$hostDirName")
      ->dir("$tmpDir/$hostDirName")
      ->checkout($branch)
      ->run();

    // Get the last commit from the remote branch.
    $last_remote_commit = $this->taskExec('git log -1 --date=short --pretty=format:%ci')
      ->dir("$tmpDir/$hostDirName")
      ->run();
    $last_commit_date = trim($last_remote_commit->getMessage());

    $commit_message = $this->_exec("git log --pretty=format:'%h %s' --no-merges --since='$last_commit_date'")->getMessage();

    $commit_message = "Combined commits: \n" . $commit_message;

    // Copy webroot to our deploy directory.
    $this->taskRsync()
      ->fromPath("./")
      ->toPath("$tmpDir/deploy")
      ->args('-a', '-v', '-z', '--no-group', '--no-owner')
      ->excludeVcs()
      ->exclude('.gitignore')
      ->exclude('sites/default/settings.local.php')
      ->exclude('sites/default/files')
      ->printed(FALSE)
      ->run();

    // Move host .git into our deployment directory.
    $this->taskRsync()
      ->fromPath("$tmpDir/$hostDirName/.git")
      ->toPath("$tmpDir/deploy")
      ->args('-a', '-v', '-z', '--no-group', '--no-owner')
      ->printed(FALSE)
      ->run();

    $this->taskGitStack()
      ->stopOnFail()
      ->dir("$tmpDir/deploy")
      ->add('-A')
      ->commit($commit_message)
      ->push('origin', $branch)
//      ->tag('0.6.0')
//      ->push('origin','0.6.0')
      ->run();

    // Clean up
//    $this->taskDeleteDir($tmpDir)
//      ->run();


  }

  /**
   * Return the default array of pressflow settings.
   * @return array
   */
  protected function getDefaultPressflowSettings() {
    return array (
      'databases' =>
        array (
          'default' =>
            array (
              'default' =>
                array (
                  'driver' => 'mysql',
                  'prefix' => '',
                  'database' => '',
                  'username' => 'root',
                  'password' => 'root',
                  'host' => '127.0.0.1',
                  'port' => 3306,
                ),
            ),
        ),
      'conf' =>
        array (
          'pressflow_smart_start' => true,
          'pantheon_binding' => NULL,
          'pantheon_site_uuid' => NULL,
          'pantheon_environment' => 'local',
          'pantheon_tier' => 'local',
          'pantheon_index_host' => 'localhost',
          'pantheon_index_port' => 8983,
          'redis_client_host' => '',
          'redis_client_port' => 6379,
          'redis_client_password' => '',
          'file_public_path' => 'sites/default/files',
          'file_private_path' => 'sites/default/files/private',
          'file_directory_path' => 'site/default/files',
          'file_temporary_path' => '/tmp',
          'file_directory_temp' => '/tmp',
          'css_gzip_compression' => false,
          'js_gzip_compression' => false,
          'page_compression' => false,
        ),
      'hash_salt' => '',
      'config_directory_name' => 'sites/default/config',
    );
  }

  /**
   * Update files with the correct project name
   */
  function findReplaceProjectName() {
    $git_repo = exec('basename `git rev-parse --show-toplevel`');

    // Update the composer project name.
    $this->taskReplaceInFile('composer.json')
      ->from('"name": "thinkshout/drupal-project",')
      ->to('"name": "thinkshout/' . $git_repo .'",')
      ->run();

    // Update the site value in .env.dist
    $this->taskReplaceInFile('.env.dist')
      ->from('TS_PROJECT="SITE"')
      ->to('TS_PROJECT="' . $git_repo .'"')
      ->run();
  }

}
