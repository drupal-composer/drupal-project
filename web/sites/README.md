# Sites directory configuration

The primary purpose of this setup is to allow for modular inclusion of settings
files. The default setup includes settings in this order:

1.  `web/sites/settings.common.php` (production settings)
1.  `web/sites/settings.development.php` (development settings that override production settings)
1.  `web/sites/default/settings.local.php` (local settings that override all earlier ones).

Because there tend to be many development environments but only one or two
production environments, we choose to run the former out of the `default` sites
directory. This means that we only have to specify overrides in `sites.php` for
our production environments, and all other environments use development settings
by default.

Another benefit of this approach is that it allows us to keep version-controlled
settings files outside of individual sites directories. Because Drupal locks
down permissions on those folders, `git` throws "cannot unlink" errors when
trying to pull in changes. Keeping them in the main `sites` directory avoids
this problem.

## Usage

1.  Edit `web/sites/sites.php` and replace `example.com` and `stage.example.com`
    with the URLs of your production and staging websites (whichever ones you
    want to run with production settings).
1.  Edit `web/sites/production.settings.php` and replace `^www\.example\.com$`
    with regular expression(s) to match all possible URLs for the environment(s)
    you listed in step (1).
1.  Edit `settings.common.php` and modify it to match your hosting environment.
    E.g., if on Pantheon, remove the Acquia section, and vice versa.
1.  Add a `settings.local.php` to the `web/sites/default` directory to specify
    database configuration and any other local configuration overrides.
