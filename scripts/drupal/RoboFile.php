<?php

/**
 * This is project's console commands configuration for Robo task runner.
 *
 * @see http://robo.li/
 */
class RoboFile extends \Robo\Tasks {

  protected function getProjectRoot() {
    return realpath(__DIR__ . '/../../');
  }

  protected function getVendorDir() {
    return $this->getProjectRoot() . '/vendor';
  }

  protected function getVendorBin() {
    return $this->getProjectRoot() . '/vendor/bin';
  }

  protected function getWebRoot() {
    return $this->getProjectRoot() . '/web';
  }

  protected function getDrush() {
    return $this->getVendorBin() . '/drush';
  }

  public function versionDump() {
    $this->taskWriteToFile($this->getVendorDir() . '/.drupal-version')
      ->text(\Drupal::VERSION)
      ->run();
  }

  public function versionRemove() {
    $this->taskFilesystemStack()
      ->remove($this->getVendorDir() . '/.drupal-version')
      ->run();
  }

  public function update($version = NULL) {
    if (!isset($version)) {
      $version = 'drupal-8';
    }

    $this->stopOnFail();

    $this->taskFilesystemStack()
      ->mkdir('tmp')
      ->run();

    $this->taskCleanDir(['./tmp'])
      ->run();

    $this->taskExec($this->getDrush())
      ->args(['dl', $version])
      ->args('--root=tmp')
      ->args('--destination=tmp')
      ->args('--drupal-project-rename=drupal-8')
      ->args('--quiet')
      ->args('--yes')
      ->run();

    $this->taskRsync()
      ->fromPath('tmp/drupal-8')
      ->toPath($this->getWebRoot())
      ->exclude('.gitkeep')
      ->exclude('autoload.php')
      ->exclude('composer.json')
      ->exclude('composer.lock')
      ->exclude('core')
      ->exclude('drush')
      ->exclude('example.gitignore')
      ->exclude('LICENSE.txt')
      ->exclude('README.txt')
      ->exclude('vendor')
      ->exclude('sites')
      ->exclude('themes')
      ->exclude('profiles')
      ->exclude('modules')
      ->run();

    $default_settings = [
      'sites/default/default.settings.php',
      'sites/default/default.services.yml',
      'sites/example.settings.local.php',
      'sites/example.sites.php'
    ];

    foreach ($default_settings as $file) {
      $this->taskRsync()
        ->fromPath('tmp/drupal-8/' . $file)
        ->toPath($this->getWebRoot() . '/' . $file)
        ->run();
    }

    $this->taskDeleteDir('tmp')
      ->run();
  }

}
