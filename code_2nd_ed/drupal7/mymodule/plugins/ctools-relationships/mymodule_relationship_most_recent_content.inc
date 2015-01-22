<?php

/**
 * @file
 * CTools relationship plugin for most recently authored content.
 *
 * From "Implementing CTools Plugins in Drupal 7", chapter 4.
 */

// Plugin definition array.
$plugin = array(
  'title' => t('My Module context relationship plugin'),
  'description' => t('Locates the most recent content item authored by a user'),
  'required context' => new ctools_context_required(t('User'), 'user'),
  'context' => 'mymodule_relationship_most_recent_content',
  'keyword' => 'node',
);

/**
 * Provides a relationship from a user to the most recently authored content.
 */
function mymodule_relationship_most_recent_content($context = NULL, $config) {
  // Read the user ID from the context. If you have multiple context inputs,
  // $context will be an array of contexts. But there is only one here.
  if (empty($context) || empty($context->data) || empty($context->data->uid)) {
    // If there is a problem, return an empty CTools context. This is also
    // used by CTools to determine the output data type of this plugin.
    return ctools_context_create_empty('node', NULL);
  }
  $uid = $context->data->uid;

  // Locate the most recent content node created by this user.
  $nid = db_select('node', 'n')
    ->fields('n', array('nid'))
    ->condition('uid', $uid)
    ->orderBy('created', 'DESC')
    ->range(0,1)
    ->execute()
    ->fetchField();

  // Load the node item if possible.
  if (!$nid) {
    return ctools_context_create_empty('node', NULL);
  }
  $node = node_load($nid);
  if (!$node) {
    return ctools_context_create_empty('node', NULL);
  }

  // Return the found node in a CTools context.
  return ctools_context_create('node', $node);
}

