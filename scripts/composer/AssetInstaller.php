<?php

namespace DrupalProject\composer;

use Composer\Script\Event;
use Composer\Util\Filesystem as ComposerFilesystem;
use Symfony\Component\Filesystem\Filesystem as SymfonyFilesystem;
use Symfony\Component\Finder\Finder;

/**
 * Class AssetInstaller.
 *
 * @package DrupalProject\composer
 */
class AssetInstaller {

  public static $assetFileTypes = [
    '.htaccess',
    '*.css',
    '*.ico',
    '*.jpeg',
    '*.jpg',
    '*.js',
    '*.png',
    '*.svg',
  ];

  public static $frontControllers = [
    'index.php',
    'core/install.php',
    'core/rebuild.php',
    'core/modules/statistics/statistics.php',
  ];

  /**
   * Build and install the project assets.
   *
   * @param \Composer\Script\Event $event
   *   The composer Event object.
   */
  public static function install(Event $event) {
    $extra = $event->getComposer()->getPackage()->getExtra();

    if (!isset($extra['drupal-app-dir'])) {
      throw new \RuntimeException('Please configure drupal-app-dir in your composer.json');
    }
    if (!isset($extra['drupal-web-dir'])) {
      throw new \RuntimeException('Please configure drupal-web-dir in your composer.json');
    }

    $fs = new SymfonyFilesystem();

    // Remove the web directory, it will be recreated with new updated assets.
    if ($fs->exists($extra['drupal-web-dir'])) {
      $fs->remove($extra['drupal-web-dir']);
    }

    // Ensure that the app and web directories exist.
    $fs->mkdir(array($extra['drupal-app-dir'], $extra['drupal-web-dir']));

    // Create the stub files.
    foreach (static::$frontControllers as $fileName) {
      static::createStubPhpFile($extra['drupal-app-dir'], $extra['drupal-web-dir'], $fileName);
    }

    // Symlink public files.
    $publicFilesSymlinkTarget = isset($extra['drupal-web-dir-public-files']) ? $extra['drupal-web-dir-public-files'] : NULL;
    static::createPublicFilesSymlink($extra['drupal-app-dir'], $extra['drupal-web-dir'], $publicFilesSymlinkTarget);

    // Create symlinks.
    static::createAssetSymlinks($extra['drupal-app-dir'], $extra['drupal-web-dir']);
  }

  /**
   * Symlink the public files folder.
   *
   * @param string $appDir
   *   The app directory name.
   * @param string $webDir
   *   The web directory name.
   * @param string $symlinkTarget
   *   Optional parameter, the target of the link.
   */
  public static function createPublicFilesSymlink($appDir, $webDir, $symlinkTarget = NULL) {
    $cfs = new ComposerFilesystem();

    if (!$symlinkTarget || !file_exists(realpath($symlinkTarget))) {
      $symlinkTarget = $appDir . '/sites/default/files';
    }

    $cfs->ensureDirectoryExists($webDir . '/sites/default');
    $cfs->relativeSymlink(realpath($symlinkTarget), realpath($webDir) . '/sites/default/files');
  }

  /**
   * Symlink the assets from the app to web directory.
   *
   * @param string $appDir
   *   The app directory path.
   * @param string $webDir
   *   The web directory path.
   */
  public static function createAssetSymlinks($appDir, $webDir) {
    $finder = new Finder();

    $finder->ignoreDotFiles(FALSE)->in($appDir);
    foreach (static::$assetFileTypes as $name) {
      $finder->name($name);
    }
    $finder->exclude('sites/default/files');

    $cfs = new ComposerFilesystem();

    foreach ($finder->files() as $file) {
      $cfs->ensureDirectoryExists($webDir . '/' . $file->getRelativePath());
      $cfs->relativeSymlink($file->getRealPath(), realpath($webDir) . '/' . $file->getRelativePathname());
    }
  }

  /**
   * Create a PHP stub file at web directory.
   *
   * @param string $appDir
   *   The app directory name.
   * @param string $webDir
   *   The web directory name.
   * @param string $path
   *   The PHP file from the app directory.
   */
  public static function createStubPhpFile($appDir, $webDir, $path) {
    $appDir = realpath($appDir);
    $webDir = realpath($webDir);

    $endPath = (dirname($path) == '.') ? $appDir : $appDir . '/' . dirname($path);
    $startPath = (dirname($path) == '.') ? $webDir : $webDir . '/' . dirname($path);

    $fs = new SymfonyFilesystem();
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
