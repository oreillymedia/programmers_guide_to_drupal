<?php

/**
 * @file
 * Contains \Drupal\mymodule\Tests\MyModuleSnippetsTest.
 */

namespace Drupal\mymodule\Tests;

use Drupal;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Cache\Cache;
use Drupal\Core\Url;

/**
 * Tests various code snippets from the book.
 *
 * @group Programmers Guide to Drupal
 */
class MyModuleSnippetsTest extends ProgrammersGuideTestBase {

  /**
   * Modules to enable (the book's module).
   *
   * @var array
   */
  public static $modules = array('mymodule', 'block', 'node', 'filter');

  function setUp() {
    parent::setUp();
  }

  /**
   * Tests various code snippets.
   */
  function testSnippets() {
    $container = \Drupal::getContainer();

    // Cache snippets from section "The Drupal Cache" in Chapter 1.

    // Make up some test data.
    $bin = 'default';
    $cid = 'my_cid';
    $data = 'my_data';
    $nid = 5;
    $tags = array('node:' . $nid);

    // Cache the data. Get the class in two different ways.
    $cache_class = \Drupal::cache($bin);
    $this->assertTrue($cache_class, 'Cache class is not null');
    $cache_class = $container->get('cache.' . $bin);

    $cache_class->set($cid, $data, CacheBackendInterface::CACHE_PERMANENT, $tags);

    // Check that we can retrieve data from the cache.
    $out = $cache_class->get($cid);
    $this->outputVariable($out, 'Cache get method output');
    $this->assertEqual($data, $out->data, 'Cached data could be retrieved');

    // Invalidate the data and check that it cannot be retrieved.
    Cache::invalidateTags($tags);
    $out = $cache_class->get($cid);
    $this->assertFalse($out, 'After invalidating tags, cached data cannot be retrieved');

    // Theme snippets from section "Making Your Output Themeable" in Chapter 2.

    $build['hello'] = array(
      '#input1' => t('Hello World!'),
      '#theme' => 'mymodule_hookname',
    );
    $output = drupal_render_root($build);
    $expected = '<div>Hello World!</div>';
    $this->outputHTML($output, 'Theme template output');
    $this->assertEqual(trim($output), $expected, 'Theme template worked in render array');

    // Config API snippets from "Configuration API in Drupal 8" section in
    // chapter 2.

    // Test reading the settings several different ways.

    $config = \Drupal::config('mymodule.settings');
    $this->assertTrue($config, 'Config class is not null');
    $config = $container->get('config.factory')->getEditable('mymodule.settings');

    $all = $config->get();
    $this->outputVariable($all, 'Full configuration output');

    $button_label = $all['submit_button_label'];
    $this->assertEqual($button_label, 'Submit', 'Read correct button label from overall get');

    $button_label = $config->get('submit_button_label');
    $this->assertEqual($button_label, 'Submit', 'Read correct button label with specific get');

    $name_field_info = $all['name_field_settings'];
    $name_label = $name_field_info['field_label'];
    $this->assertEqual($name_label, 'Your name', 'Read correct name field label from overall get');

    $name_field_info = $config->get('name_field_settings');
    $this->outputVariable($name_field_info, 'Name field configuration output');
    $name_label = $name_field_info['field_label'];
    $this->assertEqual($name_label, 'Your name', 'Read correct name field label from field settings get');

    $name_label = $config->get('name_field_settings.field_label');
    $this->assertEqual($name_label, 'Your name', 'Read correct name field label from specific get');

    // Change the submit label.
    $new_label = "Save";
    $config->set('submit_button_label', $new_label);
    $config->save();

    // Get a new config object, to make sure it was really saved.
    $new_config = \Drupal::config('mymodule.settings');
    $button_label = $new_config->get('submit_button_label');
    $this->assertEqual($button_label, 'Save', 'Read correct button label after save');

    // State API snippets from "State API in Drupal 8" section in chapter 2.

    // Get $state in two ways.
    $state = \Drupal::state();
    $this->assertTrue($state, 'State is not null');
    $state = $container->get('state');

    $value = 'Some test data';
    $state->set('mymodule.my_state_variable_name', $value);
    $new_value = $state->get('mymodule.my_state_variable_name');
    $this->assertEqual($value, $new_value, 'State get worked correctly');

    $state->delete('mymodule.my_state_variable_name');
    $new_value = $state->get('mymodule.my_state_variable_name');
    $this->assertNull($new_value, 'After delete, could not retrieve state value');

    // Snippets from "Internationalizing User Interface Text" section in
    // chapter 2. Good code only; bad code examples are omitted.
    $button_text = t('Save');
    $this->assertEqual($button_text, 'Save', 't() worked OK on simple string');

    $user_name = 'foo';
    $message_string = t('Hello @user_name', array('@user_name' => $user_name));
    $this->outputHTML($message_string, 't() with variables output');
    $this->assertEqual($message_string, 'Hello foo', 't() worked OK on string with variable');

    $test = (object) array();
    $foo = ($test instanceof MyClass);

    // Database snippets from "Querying the Database with the Database API"
    // section in Chapter 2.

    // Make a blocked user for querying purposes.
    $account = $this->drupalCreateUser(array());
    $account->status = 0;
    $account->save();

    // Query by status 0 (blocked).
    $desired_status = 0;
    $found = FALSE;
    $result = db_query('SELECT * FROM {users_field_data} u WHERE u.status = :status',
      array(':status' => $desired_status));
    foreach ($result as $record) {
      $this->outputVariable($record, 'User database record');
      if ($record->uid == $account->id()) {
        $found = TRUE;
      }
    }
    $this->assertTrue($found, 'Created user was found by status query');

    // Test the ability to query by user name.
    $found = FALSE;
    $result = db_query('SELECT * FROM {users_field_data} u WHERE u.name = :name',
      array(':name' => $account->getUsername()));
    foreach ($result as $record) {
      if ($record->uid == $account->id()) {
        $found = TRUE;
      }
    }
    $this->assertTrue($found, 'Created user was found by name query');

    // Create a node, for query purposes.
    $newnode = $this->drupalCreateNode();
    // Log in as user who can access content.
    $account = $this->drupalCreateUser(array('access content'));
    $this->drupalLogin($account);

    $query = db_select('node', 'n');
    $query->innerJoin('node_field_data', 'nd', 'n.nid = nd.nid AND n.vid = nd.vid');
    $query->innerJoin('users_field_data', 'u', 'u.uid = nd.uid');
    $query->addField('nd', 'changed', 'last_updated');
    $query
      ->extend('Drupal\Core\Database\Query\PagerSelectExtender')
      ->limit(20)
      ->fields('nd', array('title', 'nid'))
      ->fields('u', array('name'))
      ->addTag('node_access')
      ->condition('nd.status', 1);
    $result = $query->execute();
    $found = FALSE;
    foreach ($result as $node) {
      $title = $node->title;
      if ($node->nid == $newnode->id()) {
        $found = TRUE;
        $this->verbose("Found node with title $title");
      }
    }
    $this->assertTrue($found, "Found node in query");

    // Snippets from the "Cleansing and Checking User-Provided Input"
    // section in Chapter 2. Only "good" code is included.

    $text = '<h2>Text with HTML</h2>';
    $plain_text = htmlentities($text);
    $url = 'http://example.com';

    $url_object = Url::fromUri($url);
    $output = Drupal::l($text, $url_object);
    $this->outputHTML($output, 'l() output');
    $this->assertEqual($output, '<a href="' . $url . '">' . $plain_text . '</a>', 'l output is as expected');

    // Form builder snippet from "Basic Form Generation and Processing in
    // Drupal 8", chapter 4.
    $my_render_array['personal_data_form'] =
      \Drupal::formBuilder()->getForm('Drupal\mymodule\Form\PersonalDataForm');
    $this->outputVariable($my_render_array, 'Personal data form array');
    $this->assertTrue(isset($my_render_array['personal_data_form']['first_name']), 'First name field is present in form array');

    $builder = $container->get('form_builder');
    $my_render_array['personal_data_form'] =
      $builder->getForm('Drupal\mymodule\Form\PersonalDataForm');
    $this->assertTrue(isset($my_render_array['personal_data_form']['first_name']), 'First name field is present in form array');

    // Entity query snippets from "Querying and Loading Entities in Drupal 8",
    // chapter 4.

    // Try various methods for retrieving the query.
    $query = \Drupal::entityQuery('node');
    $this->assertTrue($query, 'Query is not null');
    $query = \Drupal::entityQueryAggregate('node');
    $this->assertTrue($query, 'Query is not null');

    $query_service = $container->get('entity.query');
    $query = $query_service->getAggregate('node');
    $this->assertTrue($query, 'Query is not null');

    // Try an actual query.
    $query = $query_service->get('node');
    $query->condition('type', $newnode->getType());
    $ids = $query->execute();

    // Try different methods of getting storage manager.
    $storage = \Drupal::entityManager()->getStorage('node');
    $this->assertTrue($storage, 'Storage is not null');

    // Load the entities and verify.
    $storage = $container->get('entity.manager')->getStorage('node');
    $entities = $storage->loadMultiple($ids);
    $this->assertEqual(count($entities), 1, 'One node was found');
    $first = reset($entities);
    $this->assertEqual($first->getTitle(), $newnode->getTitle(), 'Correct node was found');
  }

  /**
   * Tests that classes, interfaces, functions, etc. still exist.
   *
   * At the time of the first release of this book, Drupal 8 was still in
   * flux. This tests for the existence of Drupal 8 classes, interfaces,
   * functions, etc. mentioned in the book. If their names change or they
   * vanish, this test will fail and the book can be corrected.
   */
  function testExistence() {
    $interfaces = array(
      '\Drupal\Core\Ajax\CommandInterface',
      '\Drupal\Core\Block\BlockPluginInterface',
      '\Drupal\Core\Cache\CacheBackendInterface',
      '\Drupal\Core\Config\Entity\ConfigEntityInterface',
      '\Drupal\Core\Datetime\DateFormatInterface',
      '\Drupal\Core\DependencyInjection\ServiceModifierInterface',
      '\Drupal\Core\Entity\ContentEntityInterface',
      '\Drupal\Core\Entity\EntityInterface',
      '\Drupal\Core\Entity\EntityManagerInterface',
      '\Drupal\Core\Entity\EntityStorageInterface',
      '\Drupal\Core\Entity\EntityTypeInterface',
      '\Drupal\Core\Entity\Query\QueryInterface',
      '\Drupal\Core\Entity\Query\QueryAggregateInterface',
      '\Drupal\Core\Entity\EntityStorageInterface',
      '\Drupal\Core\Extension\ModuleHandlerInterface',
      '\Drupal\Core\Field\FieldItemInterface',
      '\Drupal\Core\Field\FieldItemListInterface',
      '\Drupal\Core\Field\FieldStorageDefinitionInterface',
      '\Drupal\Core\Field\FormatterInterface',
      '\Drupal\Core\Field\WidgetInterface',
      '\Drupal\Core\Form\FormStateInterface',
      '\Drupal\Core\Language\LanguageInterface',
      '\Drupal\Core\Render\Element\FormElementInterface',
      '\Drupal\Core\Render\Element\ElementInterface',
      '\Drupal\Core\Routing\RouteMatchInterface',
      '\Drupal\Core\Session\AccountInterface',
      '\Drupal\Core\Session\AccountProxyInterface',
      '\Drupal\Core\State\StateInterface',
      '\Symfony\Component\DependencyInjection\ContainerInterface',
      '\Symfony\Component\EventDispatcher\EventSubscriberInterface',
    );
    foreach ($interfaces as $interface) {
      $this->assertTrue(interface_exists($interface), "Interface $interface exists");
      $file = $this->classToFile($interface);
      if ($file) {
        $this->assertNoDeprecated($file);
      }
    }

    $classes = array(
      '\Drupal',
      '\Drupal\Component\Datetime\DateTimePlus',
      '\Drupal\Component\Utility\Unicode',
      '\Drupal\Core\Access\AccessResult',
      '\Drupal\Core\Ajax\AjaxResponse',
      '\Drupal\Core\Annotation\Translation',
      '\Drupal\Core\Block\Annotation\Block',
      '\Drupal\Core\Block\BlockBase',
      '\Drupal\Core\Block\BlockManager',
      '\Drupal\Core\Cache\Cache',
      '\Drupal\Core\Config\Config',
      '\Drupal\Core\Entity\Annotation\ConfigEntityType',
      '\Drupal\Core\Entity\EntityForm',
      '\Drupal\Core\Config\Entity\ConfigEntityBase',
      '\Drupal\Core\Config\Entity\ConfigEntityListBuilder',
      '\Drupal\Core\Controller\ControllerBase',
      '\Drupal\Core\Database\Connection',
      '\Drupal\Core\Database\Query\PagerSelectExtender',
      '\Drupal\Core\Datetime\Entity\DateFormat',
      '\Drupal\Core\Entity\Annotation\ContentEntityType',
      '\Drupal\Core\Entity\ContentEntityBase',
      '\Drupal\Core\Entity\ContentEntityConfirmFormBase',
      '\Drupal\Core\Entity\ContentEntityForm',
      '\Drupal\Core\Field\FieldItemBase',
      '\Drupal\Core\Field\FormatterBase',
      '\Drupal\Core\Field\WidgetBase',
      '\Drupal\Core\Field\Annotation\FieldType',
      '\Drupal\Core\Field\Annotation\FieldFormatter',
      '\Drupal\Core\Field\Annotation\FieldWidget',
      '\Drupal\Core\Form\ConfigFormBase',
      '\Drupal\Core\Form\FormBase',
      '\Drupal\Core\Language\Language',
      '\Drupal\Core\Path\AliasManager',
      '\Drupal\Core\Plugin\DefaultPluginManager',
      '\Drupal\Core\Render\Annotation\FormElement',
      '\Drupal\Core\Render\Annotation\RenderElement',
      '\Drupal\Core\Render\Element',
      '\Drupal\Core\Routing\RouteSubscriberBase',
      '\Drupal\Core\Url',
      '\Drupal\Component\Utility\SafeMarkup',
      '\Drupal\Tests\UnitTestCase',
      '\Drupal\block_content\Plugin\Block\BlockContentBlock',
      '\Drupal\migrate\MigrateExecutable',
      '\Drupal\node\Form\NodeTypeDeleteConfirm',
      '\Drupal\simpletest\WebTestBase',
      '\Drupal\simpletest\KernelTestBase',
      '\Drupal\system\DateFormatListBuilder',
      '\Drupal\system\Form\DateFormatEditForm',
      '\Drupal\system\Form\DateFormatDeleteForm',
      '\Drupal\block\Controller\CategoryAutocompleteController',
      '\Drupal\views\Plugin\views\field\FieldPluginBase',
      '\Drupal\views\Annotation\ViewsArgument',
      '\Drupal\views\Annotation\ViewsField',
      '\Drupal\views\Annotation\ViewsRow',
      '\Drupal\views\Annotation\ViewsStyle',
      '\Drupal\views\Plugin\views\field\FieldPluginBase',
      '\Drupal\views\Plugin\views\row\RowPluginBase',
      '\Drupal\views\Plugin\views\style\StylePluginBase',
      '\Drupal\views\ViewExecutable',
      '\Drupal\views\Views',
      '\Symfony\Component\DependencyInjection\Container',
      '\Symfony\Component\EventDispatcher\Event',
      '\Symfony\Component\HttpFoundation\Request',
      '\Symfony\Component\HttpFoundation\Response',
      '\Symfony\Component\Routing\Route',
      '\Symfony\Component\Routing\RouteCollection',
      '\Symfony\Component\Validator\Constraint',

    );
    foreach ($classes as $class) {
      $this->assertTrue(class_exists($class), "Class $class exists");
      $file = $this->classToFile($class);
      if ($file) {
        $this->assertNoDeprecated($file);
      }
    }

    $traits = array(
      '\Drupal\Core\StringTranslation\StringTranslationTrait',
    );
    foreach ($traits as $trait) {
      $this->assertTrue(trait_exists($trait), "Trait $trait exists");
      $file = $this->classToFile($trait);
      if ($file) {
        $this->assertNoDeprecated($file);
      }
    }

    $methods = array(
      '\Drupal' => array('config', 'currentUser', 'formBuilder',
        'getContainer', 'service', 'l'),
      '\Drupal\Core\Ajax\AjaxResponse' => 'addCommand',
      '\Drupal\Core\Block\BlockBase' => array('access', 'build'),
      '\Drupal\Core\Block\BlockManager' => array('clearCachedDefinitions', 'getSortedDefinitions'),
      '\Drupal\Core\Cache\CacheBackendInterface' => array('get',
        'invalidate',
        'set'),
      '\Drupal\Core\Config\Config' => array('get', 'save', 'set'),
      '\Drupal\Core\Database\Connection' => 'select',
      '\Drupal\Core\DependencyInjection\ServiceModifierInterface' => 'alter',
      '\Drupal\Core\DrupalKernel' => 'discoverServiceProviders',
      '\Drupal\Core\Entity\ContentEntityForm' => array(
        'buildEntity',
        'form',
        'save',
        'validateForm',
      ),
      '\Drupal\Core\Entity\ContentEntityInterface' => 'baseFieldDefinitions',
      '\Drupal\Core\Entity\Entity' => 'access',
      '\Drupal\Core\Entity\EntityForm' => array(
        'form',
        'save',
        'validateForm',
      ),
      '\Drupal\Core\Entity\Query\QueryInterface' => 'condition',
      '\Drupal\Core\Field\FieldItemInterface' => array(
        'propertyDefinitions',
        'schema',
      ),
      '\Drupal\Core\Field\FormatterInterface' => 'viewElements',
      '\Drupal\Core\Field\WidgetInterface' => 'formElement',
      '\Drupal\Core\Form\FormBase' => 't',
      '\Drupal\Core\Form\FormBuilderInterface' => 'getForm',
      '\Drupal\Core\Form\FormStateInterface' => array(
        'get',
        'getValues',
        'set',
        'setErrorByName',
        'setRebuild',
      ),
      '\Drupal\Core\Render\Element\ElementInterface' => 'getInfo',
      '\Drupal\Core\Session\AccountProxyInterface' => 'hasPermission',
      '\Drupal\block\BlockListBuilder' => array('buildForm', 'createInstance'),
      '\Drupal\block\BlockViewBuilder' => 'viewMultiple',
      '\Drupal\block\Controller\CategoryAutocompleteController' => 'autocomplete',
      '\Symfony\Component\DependencyInjection\ContainerInterface' => 'get',
      '\Symfony\Component\EventDispatcher\EventDispatcherInterface' => 'dispatch',
      '\Symfony\Component\EventDispatcher\EventSubscriberInterface' => 'getSubscribedEvents',

    );
    foreach ($methods as $class => $methods) {
      if (!is_array($methods)) {
        $methods = array($methods);
      }
      foreach ($methods as $method) {
        $this->assertTrue(method_exists($class, $method), "Method $class::$method exists");
      }
      $file = $this->classToFile($class);
      if ($file) {
        $this->assertNoDeprecated($file);
      }
    }

    $functions = array(
      'check_markup',
      'check_url',
      'db_add_field',
      'db_change_field',
      'db_create_table',
      'db_query',
      'db_select',
      'drupal_flush_all_caches',
      'drupal_render',
      't',
    );
    foreach ($functions as $function) {
      $this->assertTrue(function_exists($function), "Function $function() exists");
    }

    $services = array(
      'cache.default',
      'current_user',
      'database',
      'entity.query',
      'entity.manager',
      'event_dispatcher',
      'path.alias_manager',
      'plugin.manager.block',
      'string_translation',
    );
    foreach ($services as $service) {
      $this->assertTrue(\Drupal::hasService($service), "Service $service exists");
    }

    $files = array(
      'core/config/schema/core.data_types.schema.yml',
      'core/core.services.yml',
      'core/includes/database.inc',
      'core/lib/Drupal/Component/Datetime/DateTimePlus.php',
      'core/lib/Drupal/Core/Ajax',
      'core/modules',
      'core/modules/block/block.services.yml',
      'core/modules/block/block.routing.yml',
      'core/modules/block/block.links.contextual.yml',
      'core/modules/filter/filter.permissions.yml',
      'core/modules/filter/src/FilterPermissions.php',
      'core/modules/system/system.routing.yml',
      'core/modules/system/config/schema/system.schema.yml',
      'core/modules/taxonomy/taxonomy.routing.yml',
      'core/modules/user/user.routing.yml',
      'core/modules/user/user.links.action.yml',
      'core/modules/user/user.links.task.yml',
      'core/modules/views/src/Plugin/views',
      'core/modules/views/src/Plugin/views/argument',
      'core/modules/views/src/Plugin/views/field',
      'core/modules/views/src/Plugin/views/row',
      'core/modules/views/src/Plugin/views/style',
      'vendor/symfony/dependency-injection/Container.php',
    );

    foreach ($files as $file) {
      $this->assertTrue(file_exists(DRUPAL_ROOT . '/' . $file), "File $file exists");
    }
  }

  /**
   * Asserts that a file does not include the deprecated tag anywhere.
   */
  function assertNoDeprecated($file) {
    $text = file_get_contents($file);
    $this->assertFalse(strpos($text, '@deprecated'), $file . ' does not contain @deprected anywhere');
  }

  /**
   * Converts a namespaced class name to a file name.
   *
   * @param string $class
   *   Name of a class to find the filename of.
   *
   * @return string
   *   File name of the class, or '' if it cannot be determined. This is
   *   a heuristic, so it may not work for all files, and in particular,
   *   it doesn't work for vendor files.
   */
  function classToFile($class) {
    $parts = explode('\\', trim($class, '\\'));
    if ($parts[0] != 'Drupal') {
      return '';
    }
    if (count($parts) == 1) {
      return DRUPAL_ROOT . '/core/lib/Drupal.php';
    }
    if ($parts[1] == 'Core' || $parts[1] == 'Component') {
      return DRUPAL_ROOT . '/core/lib/' . implode('/', $parts) . '.php';
    }
    if ($parts[1] == 'Tests') {
      return DRUPAL_ROOT . '/core/tests/' . implode('/', $parts) . '.php';
    }
    array_shift($parts);
    $module = array_shift($parts);
    return DRUPAL_ROOT . '/core/modules/' . $module . '/src/' . implode('/', $parts) . '.php';
  }
}
