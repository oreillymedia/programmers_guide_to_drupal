<?php

/**
 * @file
 * Contains \Drupal\mymodule\Plugin\Field\FieldWidget\MyCustomText.
 */

namespace Drupal\mymodule\Plugin\Field\FieldWidget;

use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * Custom widget for choosing an option from a map.
 *
 * From "Defining a field widget in Drupal 8", chapter 4.
 *
 * @FieldWidget(
 *   id = "mymodule_mywidget",
 *   label = @Translation("Custom text input"),
 *   field_types = {
 *     "string"
 *   }
 * )
 */
class MyCustomText extends WidgetBase {
  public function formElement(FieldItemListInterface $items, $delta,
    array $element, array &$form, FormStateInterface $form_state) {

    $value = isset($items[$delta]->value) ? $items[$delta]->value : NULL;

    // Set up the editing form element. Substitute your custom
    // code here, instead of using an HTML select.
    $element['value'] = $element + array(
      '#type' => 'select',
      '#options' => array(
        'x_stored' => $this->t('x label'),
        'y_stored' => $this->t('y label'),
      ),
      '#default_value' => $value,
    );

    return $element;
  }
}
