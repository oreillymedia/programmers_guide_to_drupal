<?php

/**
 * @file
 * Contains \Drupal\mymodule\Form\ConfirmDeleteForm.
 */

namespace Drupal\mymodule\Form;

use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

/**
 * Presents a delete confirm form for ficticious content.
 *
 * From "Creating Confirmation Forms", chapter 4.
 */
class ConfirmDeleteForm extends ConfirmFormBase {

  /**
   * Stores the ID of the item being deleted.
   *
   * @var int
   */
  protected $to_delete_id;

  /**
   * Returns the chosen form ID, which must be unique.
   */
  public function getFormId() {
    return 'mymodule_confirm_delete';
  }

  /**
   * Builds the form array.
   *
   * Note that when adding arguments to buildForm(), you need to give
   * them default values, to avoid PHP errors.
   */
  public function buildForm(array $form, FormStateInterface $form_state, $id = '') {
    // Sanitize and save the ID.
    $id = (int) $id;
    $this->to_delete_id = $id;

    $form = parent::buildForm($form, $form_state);
    return $form;
  }

  /**
   * Defines the question for confirmation.
   */
  public function getQuestion() {
    return  $this->t('Are you sure you want to delete content item %id?',
      array('%id' => $this->to_delete_id));
  }

  /**
   * Defines the URL to go to if the user cancels.
   */
  public function getCancelUrl() {
    return new Url('mymodule.mydescription');
  }

  /**
   * Processes form submissions.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $id = $this->to_delete_id;

    // Perform the data deletion, if it were real. As a proxy, just put up
    // a message.
    drupal_set_message($this->t('Would have deleted @id', array('@id' => $id)));

    // Redirect somewhere.
    $form_state->setRedirect('mymodule.personal_data_form');
  }
}
