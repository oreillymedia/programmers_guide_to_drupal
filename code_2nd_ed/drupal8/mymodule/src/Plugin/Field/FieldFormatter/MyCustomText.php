<?php

/**
 * @file
 * Contains \Drupal\mymodule\Plugin\Field\FieldFormatter\MyCustomText.
 */

namespace Drupal\mymodule\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;

/**
 * Custom text field formatter.
 *
 * From "Defining a field formatter in Drupal 8", chapter 4.
 *
 * @FieldFormatter(
 *   id = "mymodule_myformatter",
 *   label = @Translation("Custom text output"),
 *   field_types = {
 *     "string",
 *   }
 * )
 */
class MyCustomText extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $output = array();

    foreach ($items as $delta => $item) {
      // See which option was selected.
      switch ($item->value) {

        case 'x_stored':
          // Output the corresponding text or icon.
          $output[$delta] = array('#markup' => '<p>' . t('Predefined output text x') . '</p>');
          break;

        case 'y_stored':
          // Output the corresponding text or icon.
          $output[$delta] = array('#markup' => '<p>' . t('Predefined output text y') . '</p>');
          break;

        // Handle other options here.
      }
    }
    return $output;
  }

}
