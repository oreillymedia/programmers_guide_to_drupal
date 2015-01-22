<?php

/**
 * Contains \Drupal\mymodule\Entity\MyEntityType.
 */

namespace Drupal\mymodule\Entity;

use Drupal\Core\Config\ConfigException;
use Drupal\Core\Config\Entity\ConfigEntityBundleBase;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\mymodule\Entity\MyEntityTypeInterface;

/**
 * Defines the My Entity bundle configuration entity.
 *
 * From "Defining a Configuration Entity Type in Drupal 8", chapter 4.
 *
 * @ConfigEntityType(
 *   id = "myentity_type",
 *   label = @Translation("My entity subtype"),
 *   handlers = {
 *     "form" = {
 *       "add" = "Drupal\mymodule\Entity\MyEntityTypeForm",
 *       "edit" = "Drupal\mymodule\Entity\MyEntityTypeForm",
 *       "delete" = "Drupal\mymodule\Entity\MyEntityTypeDeleteForm"
 *     },
 *     "list_builder" = "Drupal\mymodule\Entity\MyEntityTypeListBuilder",
 *   },
 *   admin_permission = "administer my entities",
 *   bundle_of = "myentity",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *   },
 *   links = {
 *     "add-form" = "entity.myentity_type.add_form",
 *     "edit-form" = "entity.myentity_type.edit_form",
 *     "delete-form" = "entity.myentity_type.delete_form"
 *   }
 * )
 */
class MyEntityType extends ConfigEntityBundleBase implements MyEntityTypeInterface {
  /**
   * Machine name or ID of the entity bundle.
   *
   * @var string
   */
  public $id;

  /**
   * Human-readable name of the entity bundle.
   *
   * @var string
   */
  public $label;

  /**
   * Description of the entity bundle.
   *
   * @var string
   */
  public $description;

  /**
   * Settings for the entity bundle.
   *
   * @var array
   */
  public $settings = array();

  /**
   * {@inheritdoc}
   */
  public function getDescription() {
    return $this->description;
  }

  /**
   * Disallows changing the machine name, during pre-save.
   *
   * If the machine name of the bundle was changed, all of the
   * existing entities with that bundle would be invalid.
   */
  public function preSave(EntityStorageInterface $storage) {
    parent::preSave($storage);

    if (!$this->isNew() && ($this->getOriginalId() != $this->id())) {
      throw new ConfigException('Cannot change machine name');
    }
  }
}
