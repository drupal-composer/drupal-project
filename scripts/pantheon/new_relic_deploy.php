<?php
// Fetch metadata from Pantheon's internal API.
$req = pantheon_curl('https://api.live.getpantheon.com/sites/self/bindings?type=newrelic', NULL, 8443);
$meta = json_decode($req['body'], true);

// Get the right binding for the current ENV.
// TODO: scope down the pantheon_curl() more.
// It should be possible to just fetch the one for the current env. 
$nr = FALSE;
foreach($meta as $data) {
  if ($data['environment'] === PANTHEON_ENVIRONMENT) {
    $nr = $data;
    break;
  }
}

// Find out what tag we are on
$deploy_tag = `git describe --tags`;
// Get the annotation
$annotation = `git tag -l -n99 $deploy_tag`;

// Use New Relic's own example curl for ease of use.
if ($nr) {
  $curl = 'curl -H "x-api-key:'. $data['api_key'] .'"';
  $curl .= ' -d "deployment[application_id]=' . $data['app_name'] .'"';
  $curl .= ' -d "deployment[description]=This deploy log was sent using Quicksilver"';
  $curl .= ' -d "deployment[revision]='. $deploy_tag .'"';
  $curl .= ' -d "deployment[changelog]='. $annotation .'"';
  $curl .= ' -d "deployment[user]='. $_POST['user_email'] .'"';
  $curl .= ' https://api.newrelic.com/deployments.xml';
  // The below can be helpful debugging.
  // echo "\n\nCURLing... \n\n$curl\n\n";
  passthru($curl);
}
else {
  echo "\n\nALERT! No New Relic metadata could be found.\n\n";
}
