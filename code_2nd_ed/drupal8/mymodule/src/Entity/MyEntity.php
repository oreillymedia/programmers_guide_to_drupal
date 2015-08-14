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
 *     "list_builder" = "Drupal\Core\Entity\EntityListBuilder",
 *     "views_data" = "Drupal\mymodule\Entity\MyEntityViewsData",
 *     "form" = {
 *       "default" = "Drupal\mymodule\Entity\MyEntityForm",
 *       "edit" = "Drupal\mymodule\Entity\MyEntityForm",
 *       "delete" = "Drupal\Core\Entity\ContentEntityDeleteForm",
 *     }
 *   },
 *   admin_permission = "administer my entities",
 *   base_table = "myentity",
 *   data_table = "myentity_field_data",
 *   translatable = TRUE,
 *   entity_keys = {
 *     "id" = "eid",
 *     "bundle" = "subtype",
 *     "label" = "title",
 *     "langcode" = "langcode",
 *     "uuid" = "uuid",
 *   },
 *   links = {
 *     "canonical" = "/myentity/{myentity}",
 *     "delete-form" = "/myentity/{myentity}/delete",
 *     "edit-form" = "/myentity/{myentity}/edit",
 *   },
 *   field_ui_base_route = "entity.myentity_type.edit_form",
 *   bundle_entity_type = "myentity_type",
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
    // Also note that the reason for the redundant descriptions is that
    // Views displays errors if they are missing.
    $fields['eid'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('My entity ID'))
      ->setDescription(t('My entity ID'))
      ->setReadOnly(TRUE)
      ->setSetting('unsigned', TRUE);

    $fields['subtype'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Subtype'))
      ->setDescription(t('Subtype'))
      ->setSetting('target_type', 'myentity_type');

    // Add a language code field so the entity can be translated.
    $fields['langcode'] = BaseFieldDefinition::create('language')
      ->setLabel(t('Language'))
      ->setDescription(t('Language code'))
      ->setTranslatable(TRUE)
      ->setDisplayOptions('view', array(
        'type' => 'hidden',
      ))
      ->setDisplayOptions('form', array(
        'type' => 'language_select',
        'weight' => 2,
      ));

    // The title field is the only editable field in the base
    // data. Set it up to be configurable in Manage Form Display
    // and Manage Display.
    $fields['title'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Title'))
      ->setDescription(t('Title'))
      ->setTranslatable(TRUE)
      ->setRequired(TRUE)
      ->setDisplayOptions('view', array(
          'label' => 'hidden',
          'type' => 'string',
          'weight' => 5,
        ))
      ->setDisplayConfigurable('view', TRUE)
      ->setDisplayOptions('form', array(
          'type' => 'string_textfield',
          'weight' => 5,
        ))
      ->setDisplayConfigurable('form', TRUE);

    $fields['uuid'] = BaseFieldDefinition::create('uuid')
      ->setLabel(t('UUID'))
      ->setDescription(t('Universally Unique ID'))
      ->setReadOnly(TRUE);

    return $fields;
  }
}
