<?php

/**
 * Contains \Drupal\mymodule\Entity\MyEntityTypeInterface.
 */

namespace Drupal\mymodule\Entity;

use Drupal\Core\Config\Entity\ConfigEntityInterface;

/**
 * Defines an interface for My Entity bundle configuration entities.
 *
 * From "Defining a Configuration Entity Type in Drupal 8", chapter 4.
 */
interface MyEntityTypeInterface extends ConfigEntityInterface {
  /**
   * Returns the description of the bundle.
   *
   * @return string
   *   The bundle description.
   */
  public function getDescription();
}
