<?php

namespace DrupalProject\composer;

use Composer\Json\JsonFile;
use Composer\Script\Event;
use Symfony\Component\Filesystem\Filesystem;

class Setup {

  public static function setup(Event $event) {
    $jsonFile = new JsonFile($event->getComposer()
      ->getConfig()
      ->getConfigSource()
      ->getName());
    $config = $jsonFile->read();
    $fs = new Filesystem();
    $drupalRoot = $event->getIO()->ask('<info>Customize Drupal root path?</info> [<comment>web</comment>]? ', 'web');
    if ($drupalRoot !== 'web') {
      $drupalRoot = rtrim($drupalRoot, '/');
      $gitIgnore = file_get_contents('.gitignore');
      $gitIgnore = preg_replace('/' . preg_quote('web/', '/') . '/', $drupalRoot . '/', $gitIgnore);
      if (isset($config['extra']['installer-paths'])) {
        $installer_paths = [];
        foreach ($config['extra']['installer-paths'] as $path => $spec) {
          $newPath = preg_replace('/' . preg_quote('web/', '/') . '/', $drupalRoot . '/', $path);
          $installer_paths[$newPath] = $spec;
        }
        $config['extra']['installer-paths'] = $installer_paths;
      }
      $fs->dumpFile('.gitignore', $gitIgnore);
    }
    if ($event->getIO()->askConfirmation('<info>Remove dotenv?</info> [<comment>y,N</comment>]? ', TRUE)) {
      $fs->remove(['.env.example', 'load.environment.php']);
      if (!empty($config['autoload']['files'])) {
        $config['autoload']['files'] = array_diff($config['autoload']['files'], ['load.environment.php']);
        if (empty($config['autoload']['files'])) {
          unset($config['autoload']['files']);
        }
      }
      unset($config['require']['vlucas/phpdotenv']);
      unset($config['require-dev']['vlucas/phpdotenv']);
    }
    if ($event->getIO()->askConfirmation('<info>Remove drush?</info> [<comment>y,N</comment>]? ', FALSE)) {
      unset($config['require']['drush/drush']);
      unset($config['require-dev']['drush/drush']);
    }
    if ($event->getIO()->askConfirmation('<info>Remove drupal-console?</info> [<comment>y,N</comment>]? ', FALSE)) {
      unset($config['require']['drupal/console']);
      unset($config['require-dev']['drupal/console']);
    }
    if ($event->getIO()->askConfirmation('<info>Remove the installer and other scaffold files?</info> [<comment>y,N</comment>]? ', FALSE)) {
      unset($config['scripts']['setup']);
      $config['autoload']['classmap'] = array_diff($config['autoload']['classmap'], ['scripts/composer/Setup.php']);
      $config['scripts']['post-root-package-install'] = array_diff($config['scripts']['post-root-package-install'], ['@setup']);
      if (empty($config['scripts']['post-root-package-install'])) {
        unset($config['scripts']['post-root-package-install']);
      }
      $fs->remove([
        '.travis.yml',
        'phpunit.xml.dist',
        'scripts/composer/Setup.php',
      ]);
    }
    $jsonFile->write($config);
    return TRUE;
  }

}
