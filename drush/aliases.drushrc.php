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
