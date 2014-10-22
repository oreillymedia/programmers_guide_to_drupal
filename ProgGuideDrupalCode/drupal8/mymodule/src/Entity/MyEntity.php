<?php

/**
 * Contains \Drupal\mymodule\Entity\MyEntity.
 */

namespace Drupal\mymodule\Entity;

use Drupal\mymodule\Entity\MyEntityInterface;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;

/**
 * Represents a MyEntity entity object.
 *
 * From "Defining a Content Entity Type in Drupal 8", chapter 4.
 *
 * @ContentEntityType(
 *   id = "myentity",
 *   label = @Translation("My entity"),
 *   bundle_label = @Translation("My entity subtype"),
 *   fieldable = TRUE,
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "form" = {
 *       "default" = "Drupal\mymodule\Entity\MyEntityForm",
 *       "edit" = "Drupal\mymodule\Entity\MyEntityForm",
 *       "delete" = "Drupal\mymodule\Entity\MyEntityDeleteForm"
 *     }
 *   },
 *   admin_permission = "administer my entities",
 *   base_table = "myentity",
 *   entity_keys = {
 *     "id" = "eid",
 *     "bundle" = "subtype",
 *     "label" = "title",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "entity.myentity.canonical",
 *     "delete-form" = "entity.myentity.delete_form",
 *     "edit-form" = "entity.myentity.edit_form",
 *   },
 *   field_ui_base_route = "entity.myentity_type.edit_form",
 *   bundle_entity_type = "myentity_type"
 * )
 */
class MyEntity extends ContentEntityBase implements MyEntityInterface {

  /**
   * {@inheritdoc}
   */
  public function getTitle() {
    return $this->get('title')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setTitle($title) {
    $this->set('title', $title);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    // Define base fields for the items in the entity_keys
    // annotation. Note that as this is a static method, you cannot use
    // $this->t() here; use t() for translation instead.
    $fields['eid'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('My entity ID'))
      ->setReadOnly(TRUE)
      ->setSetting('unsigned', TRUE);

    $fields['subtype'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Subtype'))
      ->setSetting('target_type', 'myentity_type');

    // The title field is the only editable field in the base
    // data. Set it up to be configurable in Manage Form Display
    // and Manage Display.
    $fields['title'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Title'))
      ->setTranslatable(TRUE)
      ->setRequired(TRUE)
      ->setDisplayOptions('view', array(
          'label' => 'hidden',
          'type' => 'string',
          'hidden' => TRUE,
          'weight' => 5,
        ))
      ->setDisplayConfigurable('view', TRUE)
      ->setDisplayOptions('form', array(
          'type' => 'string',
          'weight' => 5,
        ))
      ->setDisplayConfigurable('form', TRUE);

    $fields['uuid'] = BaseFieldDefinition::create('uuid')
      ->setLabel(t('UUID'))
      ->setReadOnly(TRUE);

    return $fields;
  }
}
