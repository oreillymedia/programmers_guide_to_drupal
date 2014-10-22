<?php

/**
 * Contains \Drupal\mymodule\Entity\MyEntityForm.
 */

namespace Drupal\mymodule\Entity;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

/**
 * Provides an entity form for My Entity entities.
 *
 * From "Defining a Content Entity Type in Drupal 8", chapter 4.
 */
class MyEntityForm extends ContentEntityForm {

  /**
   * Saves the entity; note that ContentEntityForm does most of the work.
   */
  public function save(array $form, FormStateInterface $form_state) {
    $entity = $this->entity;
    $entity->save();

    // You could do some logging here, set a message, etc.

    // Redirect to the entity display page.
    $form_state->setRedirect('entity.myentity.canonical', array('myentity' => $entity->id()));

  }
}
