<?php

/**
 * @file
 * Contains \Drupal\mymodule\Plugin\Block\MyModuleFirstBlock.
 */

namespace Drupal\mymodule\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a sample block.
 *
 * From "Registering a Block in Drupal 8", chapter 4.
 *
 * @Block(
 *   id = "mymodule_first_block",
 *   admin_label = @Translation("First block from My Module"),
 * )
 */
class MyModuleFirstBlock extends BlockBase {

  /**
   * Returns the content of the block.
   *
   * Function body is from "Creating Render Arrays for Page and Block Output",
   * chapter 4.
   */
  public function build() {
    // Build a render array.
    $output = array(
      // Introductory paragraph, using the elemnt type 'markup'.
      'introduction' => array(
        '#type' => 'markup',
        '#markup' => '<p>' . $this->t('General information goes here.') . '</p>',
      ),

      // List of items, using the theme hook 'item_list'.
      'colors' => array(
        '#theme' => 'item_list',
        '#items' => array($this->t('Red'), $this->t('Blue'), $this->t('Green')),
        '#title' => $this->t('Colors'),
      ),

      // Table, using the theme hook 'table'.
      'materials' => array(
        '#theme' => 'table',
        '#caption' => $this->t('Materials'),
        '#header' => array($this->t('Material'), $this->t('Characteristic')),
        '#rows' => array(
          array($this->t('Steel'), $this->t('Strong')),
          array($this->t('Aluminum'), $this->t('Light')),
        ),
      ),
    );

    return $output;
  }
}
