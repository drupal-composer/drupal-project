<?php

$home = drush_server_home();
$aliases['vag'] = array(
  'root' => '/srv/default/web',
  'remote-host' => '{{ project }}.local',
  'remote-user' => 'vagrant',
  'uri' => '{{ project }}.local',
);
$aliases['vagrant'] = array(
  'parent' => 'vag',
);

// Add drush alias for all defined branches.
$branches = [
  'master',
  'develop',
];
foreach ($branches as $branch) {
  $dev_defaults = array(
    'target-command-specific' => array(
      'sql-sync' => array(
        'enable' => array('stage_file_proxy'),
      ),
    ),
  );
  $uri_suffix = $branch == 'develop' ? '' : '--' . $branch;
  $aliases['ci.' . $branch] = array(
      'uri' => "{{ project }}$uri_suffix.ci.drunomics.com",
      'root' => "/srv/default/web",
      'remote-host' => "{{ project }}$uri_suffix.ci.drunomics.com",
    ) + $dev_defaults;
}
