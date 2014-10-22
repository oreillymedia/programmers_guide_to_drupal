<?php

/**
 * @file
 * Rules actions.
 *
 * From "Providing Custom Actions to Rules", chapter 4.
 */

/**
 * Implements hook_rules_action_info().
 *
 * @see mymodule_rules_action_user_list()
 */
function mymodule_rules_action_info() {
  $actions = array();

  // Define one action.

  // The array key is the machine name of the action, and also the
  // name of the function that does the action.
  $actions['mymodule_rules_action_user_list'] = array(

    // Label and group in the user interface.
    'label' => t('Load a list of users related to content'),
    'group' => t('My Module custom'),

    // Describe the parameters.
    'parameter' => array(

      'item' => array(
        'label' => t('Content item to use'),
        'type' => 'node',
      ),

      // You can add additional parameters here.

    ),

    // Describe the output.
    'provides' => array(
      'user_list' => array(
        'type' => 'list<user>',
        'label' => t('List of users related to content'),
      ),

      // You could describe additional output here.
    ),
  );

  // Define other actions here.

  return $actions;
}

/**
 * Performs the 'mymodule_rules_action_user_list' action.
 *
 * @see mymodule_rules_action_info()
 */
function mymodule_rules_action_user_list($item) {
  // Since the parameter defined for this action is a node,
  // $item is a node. Do a query here to find a list of
  // users related to this node.

  // As a proxy for your real code, return a list of one
  // user -- the author of the content.
  $ids = array($item->uid);

  // Load the users and return them to Rules.
  return array('user_list' => user_load_multiple($ids));
}
