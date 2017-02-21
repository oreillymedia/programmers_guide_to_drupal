<?php

/**
 * Contains \Drupal\mymodule\Entity\MyEntityTypeForm.
 */

namespace Drupal\mymodule\Entity;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

/**
 * Defines the edit form for the My Entity bundle configuration entity.
 *
 * From "Defining a Configuration Entity Type in Drupal 8", chapter 4.
 */
class MyEntityTypeForm extends EntityForm {
  /**
   * Builds an editing form.
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    $form['id'] = array(
      '#title' => $this->t('Machine-readable name'),
      '#type' => 'textfield',
      '#required' => TRUE,
    );

    // If we are editing an existing entity, show the current ID and
    // do not allow it to be changed.
    if ($this->entity->id()) {
      $form['id']['#default_value'] = $this->entity->id();
      $form['id']['#disabled'] = TRUE;
    }

    $form['label'] = array(
      '#title' => $this->t('Label'),
      '#type' => 'textfield',
      '#default_value' => $this->entity->label,
    );

    $form['description'] = array(
      '#title' => $this->t('Description'),
      '#type' => 'textfield',
      '#default_value' => $this->entity->description,
    );

    $form['settings'] = array(
      '#type' => 'details',
      '#title' => $this->t('Settings'),
      '#open' => TRUE,
      '#tree' => TRUE,
    );

    $settings = $this->entity->settings;
    $form['settings']['default_status'] = array(
      '#title' => $this->t('Published by default'),
      '#type' => 'checkbox',
      '#default_value' => !empty($settings['default_status']),
    );

    return $form;
  }

  /**
   * Validates the values.
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);

    $values = $form_state->getValues();

    // Require non-empty ID.
    $id = trim($values['id']);
    if (empty($id)) {
      $form_state->setErrorByName('id', $this->t('Subtype names must not be empty'));
    }
  }

  /**
   * Saves the entity when the form is submitted.
   */
  public function save(array $form, FormStateInterface $form_state) {
    $type = $this->entity;
    $type->save();
    // You could do some logging here, set a message, etc.

    // Redirect to admin page.
    $form_state->setRedirect('mymodule.myentity_type.list');
  }
}
