<?php

/**
 * @file
 * Contains \Drupal\mymodule\Tests\ProgrammersGuideTestBase
 */

namespace Drupal\mymodule\Tests;

use Drupal\simpletest\WebTestBase;

/**
 * Base class for all book code tests, with helper methods.
 */
abstract class ProgrammersGuideTestBase extends WebTestBase {

  /**
   * Verbose output of a PHP variable with optional label.
   */
  public function outputVariable($var, $label = '') {
    $this->verbose(
      (($label) ? '<h2>' . $label . '</h2>' : '') .
      '<pre>' . print_r($var, TRUE), '</pre>');
  }

  /**
   * Verbose output of HTML code with optional label.
   */
  public function outputHTML($var, $label = '') {
    $this->verbose(
      (($label) ? '<h2>' . $label . '</h2>' : '') .
      htmlentities($var));
  }

  /**
   * Assert that the page title contains a string.
   *
   * Function borrowed from https://drupal.org/project/api.
   */
  protected function assertTitleContains($string, $message) {
    $title = current($this->xpath('//title'));
    $this->assertTrue(strpos($title, $string) !== FALSE, $message);
  }
}
