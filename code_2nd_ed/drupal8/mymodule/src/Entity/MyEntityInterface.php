<?php

/**
 * Contains \Drupal\mymodule\Entity\MyEntityInterface.
 */

namespace Drupal\mymodule\Entity;

use Drupal\Core\Entity\ContentEntityInterface;

/**
 * Defines an interface for My Entity entities.
 *
 * From "Defining a Content Entity Type in Drupal 8", chapter 4.
 */
interface MyEntityInterface extends ContentEntityInterface {

  /**
   * Returns the entity title.
   *
   * @return string
   *   The title.
   *
   * @see \Drupal\mymodule\Entity\MyEntityInterface::setTitle()
   */
  public function getTitle();

  /**
   * Sets the entity title.
   *
   * @param string $title
   *   The title to set.
   *
   * @return $this
   *
   * @see \Drupal\mymodule\Entity\MyEntityInterface::getTitle()
   */
  public function setTitle($title);
}
