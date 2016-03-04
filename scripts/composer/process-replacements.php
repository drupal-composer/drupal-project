#!/usr/bin/php
<?php

// We get the project name from the name of the path that Composer created for
// us.
$project = basename(realpath("."));
echo "Project name $project taken from directory name\n";

// Specify files for which replacement will be applied.
$file_patterns = [
  '*.md',
  'devsetup/*\.yml',
  'web/sites/default/*\.php',
  'drush/*\.php',
];

$replacements = [
  "{{ project }}" => $project
];

// Process replacements.
foreach ($file_patterns as $pattern) {

  foreach (glob($pattern, GLOB_BRACE) as $file) {
    $content = file_get_contents($file);
    if (($new_content = strtr($content, $replacements)) != $content) {
      echo "Processing replacements in file $file...\n";
      file_put_contents($file, $new_content);
    }
  }
}

exit(0);
