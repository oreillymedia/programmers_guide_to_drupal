<?php

/**
 * @file
 * Contains \Drupal\mymodule\Form\PersonalDataForm.
 */

namespace Drupal\mymodule\Form;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Ajax\CssCommand;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Component\Utility\NestedArray;
use Drupal\Component\Utility\String;

/**
 * Presents a personal data form.
 *
 * From "Basic Form Generation and Processing in Drupal 8", chapter 4.
 */
class PersonalDataForm extends FormBase {

  /**
   * Returns the chosen form ID, which must be unique.
   */
  public function getFormId() {
    return 'mymodule_personal_data_form';
  }

  /**
   * Builds the form array.
   *
   * Form array in function body from "Form Arrays, Form State Arrays, and
   * Form State Objects", chapter 4.
   *
   * JavaScript attachment from "Creating Render Arrays for Page and Block
   * Output", chapter 4.
   *
   * Auto-complete field from "Adding Auto-Complete to Forms", chapter 4.
   *
   * Ajax fields from "Setting Up a Form for Ajax", chapter 4.
   *
   * @see mymodule_permission()
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    // Plain text input element for first name.
    $form['first_name'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('First name'),
    );

    // Plain text element for company name, only visible to some
    // users.
    $form['company'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Company'),
      // This assumes permission 'use company field' has been defined.
      '#access' => $this->currentUser()->hasPermission('use company field'),
    );

    $my_information = 'stuff';

    // Some hidden information to be used later.
    $form['information'] = array(
      '#type' => 'value',
      '#value' => $my_information,
    );

    // Submit button.
    $form['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    );

    // Attach a JavaScript file, which is in a library.
    $form['#attached']['library'][] = 'mymodule/myjslib';

    // Auto-complete field.
    $form['my_autocomplete_field'] = array(
      '#type' => 'textfield',
      '#title' => t('Autocomplete field'),
      '#autocomplete_route_name' => 'mymodule.autocomplete',
    );

    // Ajax elements.

    $form['ajax_output_1'] = array(
      '#type' => 'markup',
      '#markup' => '<div id="ajax-output-spot"></div>',
    );

    $form['text_trigger'] = array(
      '#type' => 'textfield',
      '#title' => t('Type here to trigger Ajax'),
      '#ajax' => array(
        'event' => 'keyup',
        'wrapper' => 'ajax-output-spot',
        'callback' => 'Drupal\mymodule\Form\PersonalDataForm::ajaxTextCallback',
      ),
    );

    $form['ajax_output_2'] = array(
      '#type' => 'markup',
      '#markup' => '<div id="other-ajax-spot"></div>',
    );

    $form['button_trigger'] = array(
      '#type' => 'button',
      '#value' => t('Click here to trigger Ajax'),
      '#ajax' => array(
        'callback' => 'Drupal\mymodule\Form\PersonalDataForm::ajaxButtonCallback',
      ),
    );

    return $form;
  }


  /**
   * Processes form submissions.
   *
   * From "Basic Form Generation and Processing in Drupal 8", chapter 4.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Extract the values submitted by the user.
    $values = $form_state->getValues();
    $name = $values['first_name'];
    // Values you stored in the form array are also available.
    $info = $values['information'];

    // Get another value, using a method where it could be nested.
    $parents = $form['company']['#array_parents'];
    $company = NestedArray::getValue($values, $parents);
    $company = String::checkPlain($company);

    // Processing code would go here. As a proxy, display a message with the
    // values. Note that since the values are not sanitized, insert them
    // into t() with @variable. If inserting into the database, do not
    // sanitize.
    if ($company) {
      drupal_set_message($this->t('Thank you @name from @company', array('@name' => $name, '@company' => $company)));
    }
    else {
      drupal_set_message($this->t('Thank you @name', array('@name' => $name)));
    }

    // Make sure the form is rebuilt properly for Ajax.
    $form_state->setRebuild();
  }

  /**
   * Processes the Ajax response for the text field.
   *
   * From "Wrapper-based Ajax Callback Functions", chapter 4.
   */
  public static function ajaxTextCallback(array $form, FormStateInterface $form_state) {
    // Read the text from the text field.
    $text = $form_state->getValues()['text_trigger'];
    if (!$text) {
      $text = t('nothing');
    }

    // Set a message.
    drupal_set_message(t('You have triggered Ajax'));

    // Return a render array for markup to replace the wrapper <div> contents.
    return array(
      '#type' => 'markup',
      // Text was not sanitized, so use @variable in t() to sanitize.
      // Be sure to include the wrapper div!
      '#markup' => '<div id="ajax-output-spot">' .
      t('You typed @text', array('@text' => $text)) . '</div>',
    );
  }

  /**
   * Processes the Ajax response for the button.
   *
   * From "Command-based Ajax Callback Functions in Drupal 8", chapter 4.
   */
  public static function ajaxButtonCallback(array $form, FormStateInterface $form_state) {
    $response = new AjaxResponse();

    // Replace HTML markup inside the div via a selector.
    $text = t('The button has been clicked');
    $response->addCommand(
      new HtmlCommand('div#other-ajax-spot', $text));

    // Add some CSS to the div.
    $css = array('background-color' => '#ddffdd', 'color' => '#000000');
    $response->addCommand(
      new CssCommand('div#other-ajax-spot', $css));

    return $response;
  }

}
