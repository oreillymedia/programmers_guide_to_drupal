<?php

/**
 * @file
 * Default reaction rules and components.
 *
 * From "Providing Default Reaction Rules and Components", chapter 4.
 */

/**
 * Implements hook_default_rules_configuration().
 */
function mymodule_default_rules_configuration() {
  $configs = array();

  // Read in one exported reaction rule.
  $file = drupal_get_path('module', 'mymodule') . '/rules/sample_rule.txt';
  $contents = file_get_contents($file);
  $configs['mymodule_sample_rule'] = rules_import($contents);

  // Add other reaction rules and components here if desired.

  return $configs;
}
