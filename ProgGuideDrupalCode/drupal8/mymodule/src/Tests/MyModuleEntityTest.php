<?php

/**
 * @file
 * Contains \Drupal\mymodule\Tests\MyModuleEntityTest.
 */

namespace Drupal\mymodule\Tests;

/**
 * Tests entity and field code.
 *
 * @group Programmers Guide to Drupal
 */
class MyModuleEntityTest extends ProgrammersGuideTestBase {

  /**
   * Modules to enable (the book's module, plus Field UI and Text).
   *
   * @var array
   */
  public static $modules = array('mymodule', 'field', 'field_ui', 'text');

  /**
   * Tests the defined entity type, field formatter, and field widget.
   *
   * @see \Drupal\mymodule\Entity\MyEntity
   * @see \Drupal\mymodule\Entity\MyEntitytype
   * @see \Drupal\mymodule\Plugin\Field\FieldFormatter\MyCustomText
   * @see \Drupal\mymodule\Plugin\Field\FieldWidget\MyCustomText
   */
  function testEntityAndField() {
    // Log in.
    $account = $this->drupalCreateUser(array('administer my entities', 'access administration pages', 'administer myentity fields', 'administer myentity form display', 'administer myentity display'));
    $this->drupalLogin($account);

    // Verify that the admin link is on the Structure page.
    $this->drupalGet('admin/structure');
    $this->assertLink('My entity subtypes', 0, 'Admin link is shown on Structure');
    $this->assertText('Manage my entity subtypes and their fields', 'Admin description is shown on Structure');

    // Create an entity sub-type/bundle.
    $this->clickLink('My entity subtypes');
    $this->assertTitleContains('My entity subtypes', 'Admin page title is correct');
    $this->clickLink('Add my entity subtype');
    $this->assertText('Machine-readable name', 'Machine name label is present');
    $this->assertText('Label', 'Label label is present');
    $this->assertText('Description', 'Description label is present');
    $this->assertText('Settings', 'Settings fieldset label is present');
    $this->assertText('Published by default', 'Published label is present');
    $this->drupalPostForm(NULL, array(
        'id' => 'test',
        'label' => 'My subtype',
        'description' => 'This is the description',
      ), t('Save'));
    $this->assertText('My subtype', 'Label is shown');
    $this->assertText('This is the description', 'Description is shown');

    // Add a text field.
    $this->clickLink('Manage fields');
    $this->assertLink('Edit', 0, 'Edit link is present');
    $this->assertLink('Manage form display', 0, 'Form link is present');
    $this->assertLink('Manage display', 0, 'Display link is present');
    $this->assertLink('My entity subtypes', 0, 'Breadcrumb link is present');

    $this->drupalPostForm(NULL, array(
        'fields[_add_new_field][label]' => 'The field label',
        'fields[_add_new_field][field_name]' => 'abcdef',
        'fields[_add_new_field][type]' => 'text',
      ), t('Save'));
    $this->drupalPostForm(NULL, array(), t('Save field settings'));
    $this->drupalPostForm(NULL, array(
        'field[description]' => 'Some kind of a description',
      ), t('Save settings'));

    // Set up the widget.
    $this->drupalPostForm('admin/structure/myentity_type/manage/test/form-display', array(
        'fields[field_abcdef][type]' => 'mymodule_mywidget',
      ), t('Save'));

    // Set up the formatter.
    $this->drupalPostForm('admin/structure/myentity_type/manage/test/display', array(
        'fields[field_abcdef][type]' => 'mymodule_myformatter',
      ), t('Save'));

    // Add an entity item.
    $this->drupalGet('admin/structure/myentity_type');
    $this->clickLink('Add new My Entity');
    $this->assertTitleContains('Add new my entity', 'Title is correct');
    $this->assertText('Title', 'Label for title field is present');
    $this->assertText('The field label', 'Label for custom field is present');
    $this->assertText('Some kind of a description', 'Description for custom field is present');
    $this->assertRaw(t('x label'), 'X choice label is present from widget');
    $this->assertRaw(t('y label'), 'Y choice label is present from widget');
    $this->drupalPostForm(NULL, array(
        'title[0][value]' => 'My title goes here',
        'field_abcdef[0][value]' => 'x_stored',
      ), t('Save'));

    // Test text/link on entity page.
    $this->assertTitleContains('My title goes here', 'Entity object title is correct');
    $this->assertText('Predefined output text x', 'Custom field is displayed with custom display');
    $this->assertText(t('View'), 'View tab is there');

    // Edit the entity.
    $this->clickLink(t('Edit'));
    $this->assertTitleContains('Edit my entity', 'Title is correct');
    $this->assertLink(t('Delete'), 0, 'Delete link is present');
    $this->drupalPostForm(NULL, array(
        'title[0][value]' => 'My new title',
      ), t('Save'));
    $this->assertTitleContains('My new title', 'Entity edited title is correct');

    // Add another entity item and delete it.
    $this->drupalGet('admin/structure/myentity_type');
    $this->clickLink('Add new My Entity');
    $this->drupalPostForm(NULL, array(
        'title[0][value]' => 'whatever',
      ), t('Save'));
    $this->drupalPostForm('myentity/2/delete', array(), t('Confirm'));
    $this->drupalget('myentity/2');
    $this->assertResponse('404', 'After deleting, page does not exist');

    // Delete the entity subtype completely.
    $this->drupalGet('admin/structure/myentity_type');
    $this->clickLink('Delete');
    $this->assertTitleContains('Are you sure you want to delete', 'Delete page title is correct');
    $this->assertText('All entities of this type will also be deleted', 'Delete explanation is present');
    $this->drupalPostForm(NULL, array(), t('Confirm'));
    $this->assertTitleContains('My entity subtypes', 'Went back to the right page');
    $this->assertNoText('This is the description', 'Entity subtype is gone');
    $this->drupalGet('myentity/1');
    $this->assertResponse('404', 'After deleting subtype, page does not exist');

  }
}
