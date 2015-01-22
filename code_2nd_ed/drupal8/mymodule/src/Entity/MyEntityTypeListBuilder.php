<?php

/**
 * Contains \Drupal\mymodule\Entity\MyEntityTypeListBuilder.
 */

namespace Drupal\mymodule\Entity;

use Drupal\Core\Config\Entity\ConfigEntityListBuilder;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Url;
use Drupal\Component\Utility\Xss;

/**
 * Defines a list builder class for My Entity bundle configuration entities.
 *
 * From "Defining a Configuration Entity Type in Drupal 8", chapter 4.
 */
class MyEntityTypeListBuilder extends ConfigEntityListBuilder {

  /**
   * Defines the header of the list in the UI.
   */
  public function buildHeader() {
    $header['label'] = $this->t('Label');

    $header['description'] = array(
      'data' => $this->t('Description'),
    );

    return $header + parent::buildHeader();
  }

  /**
   * Defines how to display an entity in a row in the list.
   */
  public function buildRow(EntityInterface $entity) {
    $row['label'] = array(
      'data' => $this->getLabel($entity),
    );

    $row['description'] = Xss::filterAdmin($entity->description);

    return $row + parent::buildRow($entity);
  }

  /**
   * Defines the default operations.
   */
  public function getDefaultOperations(EntityInterface $entity) {
    $operations = parent::getDefaultOperations($entity);

    // Add an operation for adding a new entity object of this type.
    $url = new Url('mymodule.myentity.add', array('myentity_type' => $entity->id()));
    $operations['add_new'] = array(
      'title' => $this->t('Add new My Entity'),
      'weight' =>  11,
    ) + $url->toArray();

    return $operations;
  }
}
