<?php

/**
 * Contains \Drupal\mymodule\Plugin\views\wizard\MyEntityViewsWizard.
 */

namespace Drupal\mymodule\Plugin\views\wizard;

use Drupal\Core\Form\FormStateInterface;
use Drupal\views\Plugin\views\wizard\WizardPluginBase;

/**
 * Provides a views wizard for My Entity entities.
 *
 * From "Defining a Content Entity Type in Drupal 8", chapter 4.
 *
 * @ViewsWizard(
 *   id = "myentity",
 *   base_table = "myentity_field_data",
 *   title = @Translation("My Entity")
 * )
 */
class MyEntityViewsWizard extends WizardPluginBase {
}

