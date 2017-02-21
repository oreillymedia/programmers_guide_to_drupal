<?php

/**
 * @file
 * Contains \Drupal\mymodule\Tests\MyThemeTest.
 */

namespace Drupal\mymodule\Tests;

use Drupal;

/**
 * Tests the theme code.
 *
 * @group Programmers Guide to Drupal
 */
class MyThemeTest extends ProgrammersGuideTestBase {

  /**
   * Modules to enable (Block).
   *
   * @var array
   */
  public static $modules = array('block');

  function setUp() {
    parent::setUp();

    // Switch to the book's theme.
    $theme_service = Drupal::service('theme_handler');
    $theme_service->install(array('mytheme'));
    $theme_service->setDefault('mytheme');
  }

  /**
   * Tests the theme.
   */
  function testTheme() {
    // Place a block in the custom region of this theme.
    $block_title = 'This is a test.';
    $this->drupalPlaceBlock('system_powered_by_block', array(
        'region' => 'internal_region_name',
        'label' => $block_title,
        'label_display' => TRUE,
      ));

    // Place a block in one of the regular regions.
    $block_title_2 = 'Another title';
    $this->drupalPlaceBlock('system_powered_by_block', array(
        'region' => 'content',
        'label' => $block_title_2,
        'label_display' => TRUE,
      ));

    // Log in.
    $account = $this->drupalCreateUser(array('administer blocks'));
    $this->drupalLogin($account);

    // Verify that the new region is shown on the Blocks page.
    $this->drupalGet('admin/structure/block');
    $this->assertText('Readable region name');

    // Visit the user profile page. Make sure both blocks are shown.
    $this->drupalGet('user');
    $this->assertText($block_title, 'Block in custom region is shown');
    $this->assertText($block_title_2, 'Block in standard region is shown');
  }
}
