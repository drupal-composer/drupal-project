# Sites directory configuration

The primary purpose of this setup is to allow for modular inclusion of settings files. Note that later includes override earlier ones. The default setup includes settings files in this order:

1.  `web/sites/settings.common.php` » configuration common to all environments
1.  `web/sites/settings.ENVIRONMENT.php` » environment-specific configuration that overrides configuration in the "common" settings; e.g., "dev" or "live"
1.  `web/sites/default/settings.local.php` » local environment configuration that overrides all earlier configuration

One benefit of this approach is that it allows us to keep version-controlled settings files outside of individual sites directories (e.g., `default`). Because Drupal locks down permissions on those folders, `git` throws "cannot unlink" errors when trying to pull in changes. Keeping them in the main `sites` directory avoids this problem.

## Usage

1.  Edit `settings.common.php` and modify the `$env` definition to match your hosting provider.
1.  Edit `settings.common.php` and add an include statement for a hosting provider-specific settings file, if any.
1.  Add a `settings.local.php` to the `web/sites/default` directory to specify database configuration and any other local configuration overrides.
