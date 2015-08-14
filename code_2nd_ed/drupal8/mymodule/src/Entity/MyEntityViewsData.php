<?php

/**
 * Contains \Drupal\mymodule\Entity\MyEntityViewsData.
 */

namespace Drupal\mymodule\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides views data for My Entity entities.
 *
 * From "Defining a Content Entity Type in Drupal 8", chapter 4.
 */
class MyEntityViewsData extends EntityViewsData {

  /**
   * Returns the Views data for the entity.
   */
  public function getViewsData() {
    // Start with the Views information provided by the base class.
    $data = parent::getViewsData();

    // Define a wizard.
    $data['myentity_field_data']['table']['wizard_id'] = 'myentity';

    // You could also override labels or put in a custom field
    // or filter handler.

    return $data;
  }
}
