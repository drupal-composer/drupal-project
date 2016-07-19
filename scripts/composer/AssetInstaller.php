<?php

/**
 * @file
 * Contains \DrupalProject\composer\AssetInstaller.
 */

namespace DrupalProject\composer;

use Composer\Script\Event;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class AssetInstaller {

  public static function install(Event $event) {
    $extra = $event->getComposer()->getPackage()->getExtra();
    if (!isset($extra['drupal-app-dir'])) {
      throw new \RuntimeException('Please configure drupal-app-dir in your composer.json');
    }
    if (!isset($extra['drupal-web-dir'])) {
      throw new \RuntimeException('Please configure drupal-web-dir in your composer.json');
    }

    $fs = new Filesystem();
    $fs->mkdir([$extra['drupal-app-dir'], $extra['drupal-web-dir']]);

    static::createSymlinks($extra['drupal-app-dir'], $extra['drupal-web-dir']);

    static::createStubPhpFile($extra['drupal-app-dir'], $extra['drupal-web-dir'], 'index.php');
    static::createStubPhpFile($extra['drupal-app-dir'], $extra['drupal-web-dir'], 'core/install.php');
    static::createStubPhpFile($extra['drupal-app-dir'], $extra['drupal-web-dir'], 'core/rebuild.php');

    // Optional?
    static::createStubPhpFile($extra['drupal-app-dir'], $extra['drupal-web-dir'], 'core/modules/statistics/statistics.php');

    // Symlink public files
    $fs = new Filesystem();
    $fs->symlink(realpath($extra['drupal-app-dir']) . '/sites/default/files', $extra['drupal-web-dir'] . '/sites/default/files');
  }

  public static function createSymlinks($appDir, $webDir) {
    $finder = new Finder();
    $cfs = new \Composer\Util\Filesystem();

    $names = [
      '.htaccess',
      '*.css',
      '*.ico',
      '*.jpeg',
      '*.jpg',
      '*.js',
      '*.png',
      '*.svg'
    ];

    $finder = $finder->ignoreDotFiles(FALSE)->in($appDir);
    foreach ($names as $name) {
      $finder->name($name);
    }
    $finder->exclude('sites/default/files');

    foreach ($finder->files() as $file) {
      $cfs->ensureDirectoryExists($webDir . '/' . $file->getRelativePath());
      $cfs->relativeSymlink($file->getRealPath(), realpath($webDir) . '/' . $file->getRelativePathname());
    }
  }

  public static function createStubPhpFile($appDir, $webDir, $path) {
    $fs = new Filesystem();
    $endPath = (dirname($path) == '.') ? $appDir : $appDir . '/' . dirname($path);
    $startPath = (dirname($path) == '.') ? $webDir : $webDir . '/' . dirname($path);
    $relativePath = $fs->makePathRelative($endPath, $startPath);
    $filename = basename($path);

    $content = <<<EOF
<?php

chdir('$relativePath');
require './$filename';

EOF;

    $fs->dumpFile($webDir . '/' . $path, $content);
  }
}
