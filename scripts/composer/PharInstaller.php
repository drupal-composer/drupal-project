<?php

namespace drunomics\Composer;

use Composer\Script\Event;
use Composer\Util\StreamContextFactory;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Scripthandler for installing (development) tools.
 */
class PharInstaller {

  /**
   * Install phar tools as noted in the extra tools section.
   *
   * This is like tm/tooly-composer-script but faster.
   *
   * @param \Composer\Script\Event $event
   */
  public static function installPharTools(Event $event) {

    if ($event->isDevMode()) {
      $fs = new Filesystem();
      $composer = $event->getComposer();
      $bin_dir = $composer->getConfig()->get('bin-dir');
      $extras = $composer->getPackage()->getExtra();

      if (array_key_exists('tools', $extras)) {
        foreach ($extras['tools'] as $tool => $data) {
          if (empty($data['url'])) {
            throw new \LogicException("Missing tool url.");
          }
          $filename = basename($data['url']) . '-' . $data['version'];
          if (!$fs->exists("$bin_dir/$filename")) {
            if (!$fs->exists($bin_dir)) {
              $fs->mkdir($bin_dir);
            }
            $event->getIO()->write("<info>Downloading $filename...</info>");
            $content = static::download($data['url']);
            $fs->dumpFile("$bin_dir/$filename", $content, 0755);

            if ($fs->exists("$bin_dir/$tool")) {
              $fs->remove("$bin_dir/$tool");
            }
            $fs->symlink("$bin_dir/$filename", "$bin_dir/$tool");
          }
        }
      }
    }
  }

  /**
   * Downloads the URL.
   */
  protected static function download($url) {
    $context = StreamContextFactory::getContext($url);
    return file_get_contents($url, FALSE, $context);
  }

}
