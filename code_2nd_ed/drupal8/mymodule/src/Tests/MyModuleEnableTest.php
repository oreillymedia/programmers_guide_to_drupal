<?php

/**
 * @file
 * Contains \Drupal\mymodule\Tests\MyModuleEnableTest.
 */

namespace Drupal\mymodule\Tests;

use Drupal\Core\Database\Database;

/**
 * Tests module enable hooks and other basic functionality.
 *
 * @group Programmers Guide to Drupal
 */
class MyModuleEnableTest extends ProgrammersGuideTestBase {

  /**
   * Modules to enable (the book's module).
   *
   * @var array
   */
  public static $modules = array('mymodule', 'node');

  /**
   * Tests the schema hook.
   *
   * @see mymodule_schema()
   */
  function testSchemaHook() {
    $connection = Database::getConnection();

    // Add some data to the table, and then retrieve it, to verify that
    // the table was created.
    $connection->insert('mymodule_foo')
      ->fields(array('bar' => 'Hello', 'baz' => 2))
      ->execute();
    $result = $connection->query('SELECT * from {mymodule_foo}');
    $count = 0;
    foreach ($result as $record) {
      $count++;
      $this->outputVariable($record, 'Database record');
      $this->assertEqual($record->bar, 'Hello', 'Field bar is correct');
      $this->assertEqual($record->baz, 2, 'Field baz is correct');
    }
    $this->assertEqual($count, 1, 'Count of records is correct');
  }

  /**
   * Tests the permission hook.
   *
   * @see mymodule_permission()
   */
  function testPermission() {
    $account_yes = $this->drupalCreateUser(array('administer mymodule'));
    $account_no = $this->drupalCreateUser(array('access content'));

    $this->drupalLogin($account_yes);
    $current = \Drupal::currentUser();
    $this->assertTrue($current->hasPermission('administer mymodule'), 'First user has permission');

    $this->drupalLogin($account_no);
    $current = \Drupal::currentUser();
    $this->assertFalse($current->hasPermission('administer mymodule'), 'Second user does not have permission');

  }
}
