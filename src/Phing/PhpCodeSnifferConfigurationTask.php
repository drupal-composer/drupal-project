<?php

/**
 * @file
 * Contains \DrupalProject\build\Phing\PhpCodeSnifferConfigurationTask.
 */

namespace DrupalProject\Phing;

require_once 'phing/Task.php';

/**
 * A Phing task to generate a configuration file for PHP CodeSniffer.
 */
class PhpCodeSnifferConfigurationTask extends \Task {

  /**
   * The path to the configuration file to generate.
   *
   * @var string
   */
  private $configFile = '';

  /**
   * The extensions to scan.
   *
   * @var array
   */
  private $extensions = array();

  /**
   * The list of files and folders to scan.
   *
   * @var array
   */
  private $files = array();

  /**
   * The path to the global configuration file to generate.
   *
   * @var string
   */
  private $globalConfig = '';

  /**
   * The list of patterns to ignore.
   *
   * @var array
   */
  private $ignorePatterns = array();

  /**
   * The report format to use.
   *
   * @var string
   */
  private $report = '';

  /**
   * Whether or not to show progress.
   *
   * @var bool
   */
  private $showProgress = FALSE;

  /**
   * Whether or not to show sniff codes in the report.
   *
   * @var bool
   */
  private $showSniffCodes = FALSE;

  /**
   * The coding standard to use.
   *
   * @var string
   */
  private $standard;

  /**
   * Configures PHP CodeSniffer.
   */
  public function main() {
    // Check if all required data is present.
    $this->checkRequirements();

    $document = new \DOMDocument('1.0', 'UTF-8');
    $document->formatOutput = TRUE;

    // Create the root 'ruleset' element.
    $root_element = $document->createElement('ruleset');
    $root_element->setAttribute('name', 'pbm_default');
    $document->appendChild($root_element);

    // Add the description.
    $element = $document->createElement('description', 'Default PHP CodeSniffer configuration for composer based Drupal projects.');
    $root_element->appendChild($element);

    // Add the coding standard.
    $element = $document->createElement('rule');
    $element->setAttribute('ref', $this->standard);
    $root_element->appendChild($element);

    // Add the files to check.
    foreach ($this->files as $file) {
      $element = $document->createElement('file', $file);
      $root_element->appendChild($element);
    }

    // Add file extensions.
    if (!empty($this->extensions)) {
      $extensions = implode(',', $this->extensions);
      $this->appendArgument($document, $root_element, $extensions, 'extensions');
    }

    // Add ignore patterns.
    foreach ($this->ignorePatterns as $pattern) {
      $element = $document->createElement('exclude-pattern', $pattern);
      $root_element->appendChild($element);
    }

    // Add the report type.
    if (!empty($this->report)) {
      $this->appendArgument($document, $root_element, $this->report, 'report');
    }

    // Add the shorthand options.
    $shorthand_options = array(
      'p' => 'showProgress',
      's' => 'showSniffCodes',
    );

    $options = array_filter($shorthand_options, function ($value) {
      return $this->$value;
    });

    if (!empty($options)) {
      $this->appendArgument($document, $root_element, implode('', array_flip($options)));
    }

    // Save the file.
    file_put_contents($this->configFile, $document->saveXML());

    // If a global configuration file is passed, update this too.
    if (!empty($this->globalConfig)) {
      $global_config = <<<PHP
<?php
 \$phpCodeSnifferConfig = array (
  'default_standard' => '$this->configFile',
);
PHP;
      file_put_contents($this->globalConfig, $global_config);
    }
  }

  /**
   * Appends an argument element to the XML document.
   *
   * This will append an XML element in the following format:
   * <arg name="name" value="value" />
   *
   * @param \DOMDocument $document
   *   The document that will contain the argument to append.
   * @param \DOMElement $element
   *   The parent element of the argument to append.
   * @param string $value
   *   The argument value.
   * @param string $name
   *   Optional argument name.
   */
  protected function appendArgument(\DOMDocument $document, \DOMElement $element, $value, $name = '') {
    $argument = $document->createElement('arg');
    if (!empty($name)) {
      $argument->setAttribute('name', $name);
    }
    $argument->setAttribute('value', $value);
    $element->appendChild($argument);
  }

  /**
   * Checks if all properties required for generating the config are present.
   *
   * @throws \BuildException
   *   Thrown when a required property is not present.
   */
  protected function checkRequirements() {
    $required_properties = array('configFile', 'files', 'standard');
    foreach ($required_properties as $required_property) {
      if (empty($this->$required_property)) {
        throw new \BuildException("Missing required property '$required_property'.");
      }
    }
  }

  /**
   * Sets the path to the configuration file to generate.
   *
   * @param string $configFile
   *   The path to the configuration file to generate.
   */
  public function setConfigFile($configFile) {
    $this->configFile = $configFile;
  }

  /**
   * Sets the file extensions to scan.
   *
   * @param string $extensions
   *   A string containing file extensions, delimited by spaces, commas or
   *   semicolons.
   */
  public function setExtensions($extensions) {
    $this->extensions = array();
    $token = ' ,;';
    $extension = strtok($extensions, $token);
    while ($extension !== false) {
      $this->extensions[] = $extension;
      $extension = strtok($token);
    }
  }

  /**
   * Sets the list of files and folders to scan.
   *
   * @param string $files
   *   A list of paths, delimited by spaces, commas or semicolons.
   */
  public function setFiles($files) {
    $this->files = array();
    $token = ' ,;';
    $file = strtok($files, $token);
    while ($file !== false) {
      $this->files[] = $file;
      $file = strtok($token);
    }
  }

  /**
   * Sets the path to the global configuration file to generate.
   *
   * @param string $globalConfig
   *   The path to the global configuration file to generate.
   */
  public function setGlobalConfig($globalConfig) {
    $this->globalConfig = $globalConfig;
  }

  /**
   * Sets the list of patterns to ignore.
   *
   * @param string $ignorePatterns
   *   The list of patterns, delimited by spaces, commas or semicolons.
   */
  public function setIgnorePatterns($ignorePatterns) {
    $this->ignorePatterns = array();
    $token = ' ,;';
    $pattern = strtok($ignorePatterns, $token);
    while ($pattern !== false) {
      $this->ignorePatterns[] = $pattern;
      $pattern = strtok($token);
    }
  }

  /**
   * Sets the report format to use.
   *
   * @param string $report
   *   The report format to use.
   */
  public function setReport($report) {
    $this->report = $report;
  }

  /**
   * Sets whether or not to show progress.
   *
   * @param bool $showProgress
   *   Whether or not to show progress.
   */
  public function setShowProgress($showProgress) {
    $this->showProgress = (bool) $showProgress;
  }

  /**
   * Sets whether or not to show sniff codes in the report.
   *
   * @param bool $showSniffCodes
   *   Whether or not to show sniff codes.
   */
  public function setShowSniffCodes($showSniffCodes) {
    $this->showSniffCodes = (bool) $showSniffCodes;
  }

  /**
   * Sets the coding standard to use.
   *
   * @param string $standard
   *   The coding standard to use.
   */
  public function setStandard($standard) {
    $this->standard = $standard;
  }

}
