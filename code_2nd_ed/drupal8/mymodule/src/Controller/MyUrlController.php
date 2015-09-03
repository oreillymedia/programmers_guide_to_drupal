<?php

/**
 * @file
 * Contains \Drupal\mymodule\Controller\MyUrlController.
 */

namespace Drupal\mymodule\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Connection;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\mymodule\Entity\MyEntityTypeInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Drupal\Component\Utility\Html;

/**
 * Provides route responses for the sample module.
 *
 * From "Registering for a URL in Drupal 8", chapter 4, except for methods
 * noted below.
 */
class MyUrlController extends ControllerBase {

  /**
   * The database service, obtained through dependency injection.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $database;

  /**
   * Creates a new controller object.
   */
  public static function create(ContainerInterface $container) {
    return new static($container->get('database'));
  }

  /**
   * Constructs a controller object.
   *
   * @param Connection $database
   *   A database connection.
   */
  public function __construct(Connection $database) {
    $this->database = $database;
  }

  /**
   * Generates a sample content page.
   *
   * Function body from "Generating paged output", in Chapter 4.
   */
  public function generateMyPage() {
    // Note: You should really use the Views module to do this! Code is
    // only here to illustrate using PagerSelectExtender on a query,
    // and 'pager' item in a render array.

    $query = $this->database->select('node', 'n');
    $query->innerJoin('node_field_data', 'nd', 'n.nid = nd.nid AND n.vid = nd.vid');
    $query = $query
      ->extend('Drupal\Core\Database\Query\PagerSelectExtender') // Add pager.
      ->addMetaData('base_table', 'node') // Necessary for join queries.
      ->limit(10) // 10 items per page.
      ->fields('nd', array('title', 'nid')) // Get the title field.
      ->orderBy('nd.created', 'DESC') // Sort by last updated.
      ->addTag('node_access') // Enforce node access.
      ->condition('nd.status', 1);

    $result = $query->execute();

    // Extract the information from the query result.
    $titles = array();
    foreach ($result as $row) {
      $titles[] = $row->title;
    }

    // Make the render array for a paged list of titles.
    $build = array();
    $build['items'] = array(
      '#theme' => 'item_list',
      '#items' => $titles,
    );

    // Add the pager.
    $build['item_pager'] = array('#type' => 'pager');

    return $build;
  }

  /**
   * Generates autocompletes for path mymodule/autocomplete.
   *
   * From "Adding Auto-Complete to Forms", chapter 4.
   */
  public function autocomplete(Request $request) {
    $string = $request->query->get('q');
    // This is being output as JSON, so it needs to be sanitized.
    $string = Html::escape($string);

    $matches = array();

    if ($string) {
      // Find appropriate matches -- about 10 or fewer.
      // Put them into $matches as items, each an array with
      // 'value' and 'label' elements.

      // As a proxy, just add some text to the end of the submitted text.
      $additions = array('add', 'choice', 'more', 'plus', 'something');
      foreach ($additions as $word) {
        $choice = $string . $word;
        $matches[] = array(
          'value' => $choice,
          'label' => $choice,
        );
      }
    }

    return new JsonResponse($matches);
  }

  /**
   * Generates a form for adding a new My Entity entity object.
   *
   * @param \Drupal\mymodule\Entity\MyEntityTypeInterface $myentity_type
   *   The subtype (bundle) to use.
   *
   * From "Defining a Content Entity Type in Drupal 8", chapter 4.
   *
   * @return array
   *   The form array for the page.
   */
  public function addEntityPage(MyEntityTypeInterface $myentity_type) {
    // Create a stub entity of this type.
    $entity = $this->entityManager()
      ->getStorage('myentity')
      ->create(array('subtype' => $myentity_type->id()));

    // You might want to set other values on the stub entity.

    // Return the entity editing form for the stub entity.
    return $this->entityFormBuilder()->getForm($entity);
  }
}
