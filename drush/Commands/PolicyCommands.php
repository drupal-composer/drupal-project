<?php

namespace Drush\Commands;

use Consolidation\AnnotatedCommand\CommandData;
use Consolidation\AnnotatedCommand\Hooks\HookManager;
use Drush\Attributes\Hook;
use Drush\Commands\core\RsyncCommands;
use Drush\Commands\sql\SqlSyncCommands;

/**
 * Edit this file to reflect your organization's needs.
 */
class PolicyCommands extends DrushCommands {

  /**
   * Prevent catastrophic braino. Note that this file has to be local to the
   * machine that initiates the sql:sync command.
   *
   * @throws \Exception
   */
  #[Hook(type: HookManager::ARGUMENT_VALIDATOR, target: SqlSyncCommands::SYNC)]
  public function sqlSyncValidate(CommandData $commandData) {
    if ($commandData->input()->getArgument('target') == '@prod') {
      throw new \Exception(dt('Per !file, you may never overwrite the production database.', ['!file' => __FILE__]));
    }
  }

  /**
   * Limit rsync operations to production site.
   *
   * @throws \Exception
   */
  #[Hook(type: HookManager::ARGUMENT_VALIDATOR, target: RsyncCommands::RSYNC)]
  public function rsyncValidate(CommandData $commandData) {
    if (preg_match("/^@prod/", $commandData->input()->getArgument('target'))) {
      throw new \Exception(dt('Per !file, you may never rsync to the production site.', ['!file' => __FILE__]));
    }
  }
}
