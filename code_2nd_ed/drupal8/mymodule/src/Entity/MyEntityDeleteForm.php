<?php

/**
 * Contains \Drupal\mymodule\Entity\MyEntityForm.
 */

namespace Drupal\mymodule\Entity;

use Drupal\Core\Entity\ContentEntityConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

/**
 * Provides a confirmation form for deleting My Entity entities.
 *
 * From "Defining a Content Entity Type in Drupal 8", chapter 4.
 */
class MyEntityDeleteForm extends ContentEntityConfirmFormBase {

  /**
   * {@inheritdoc}
   */
  public function getQuestion() {
    return $this->t('Are you sure you want to delete the My Entity %title?', array('%title' => $this->entity->getTitle()));
  }

  /**
   * Defines where to go on cancel (the view page).
   */
  public function getCancelUrl() {
    return new Url('entity.myentity.canonical', array('myentity' => $this->entity->id()));
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $title = $this->entity->getTitle();
    $this->entity->delete();

    drupal_set_message($this->t('Deleted the My Entity %title', array('%title' => $title)));

    // Redirect to the site home page.
    $form_state->setRedirect('<front>');
  }
}
