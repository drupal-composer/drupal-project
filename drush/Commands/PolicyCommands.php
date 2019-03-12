<?php

namespace Drush\Commands;

use Consolidation\AnnotatedCommand\CommandData;

/**
 * Edit this file to reflect your organization's needs.
 */
class PolicyCommands extends DrushCommands {

  /**
   * Protected sites that should not be overwritten.
   *
   * @var array
   */
  protected $protectedSites = ['@prod', '@self.prod'];

  /**
   * Prevent catastrophic braino. Note that this file has to be local to the
   * machine that initiates the sql:sync command.
   *
   * @hook validate sql:sync
   *
   * @throws \Exception
   */
  public function sqlSyncValidate(CommandData $commandData) {
    $target = $commandData->input()->getArgument('target');
    if (in_array($target, $this->protectedSites)) {
      throw new \Exception(dt('Per !file, you may never overwrite the production database.', ['!file' => __FILE__]));
    }
  }

  /**
   * Limit rsync operations to production site.
   *
   * @hook validate core:rsync
   *
   * @throws \Exception
   */
  public function rsyncValidate(CommandData $commandData) {
    $target = $commandData->input()->getArgument('target');
    foreach ($this->protectedSites as $alias) {
      if (strpos($target, $alias) === 0) {
        throw new \Exception(dt('Per !file, you may never rsync to the production site.', ['!file' => __FILE__]));
      }
    }
  }
}
