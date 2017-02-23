<?php

/**
 * Contains \Drupal\mymodule\Entity\MyEntityTypeDeleteForm.
 */

namespace Drupal\mymodule\Entity;

use Drupal\Core\Entity\EntityConfirmFormBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Entity\Query\QueryFactory;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a confirmation form for deleting bundles of My Entity.
 *
 * From "Defining a Configuration Entity Type in Drupal 8", chapter 4.
 */
class MyEntityTypeDeleteForm extends EntityConfirmFormBase {

  /**
   * The entity manager class.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $manager;

  /**
   * The entity query factory class.
   *
   * @var \Drupal\Core\Entity\Query\QueryFactory
   */
  protected $queryFactory;

  /**
   * Constructs the delete confirm form, using dependency injection.
   */
  public function __construct(QueryFactory $query_factory, EntityTypeManagerInterface $manager) {
    $this->queryFactory = $query_factory;
    $this->manager = $manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity.query'),
      $container->get('entity_type.manager')
    );
  }

  /**
   * Defines the question to show on the screen.
   */
  public function getQuestion() {
    return $this->t('Are you sure you want to delete %label?',
      array('%label' => $this->entity->label()));
  }

  /**
   * Adds a description.
   */
  public function getDescription() {
    return $this->t('All entities of this type will also be deleted!');
  }

  /**
   * Defines where to go on cancel (the admin page for these bundles).
   */
  public function getCancelUrl() {
    return new Url('mymodule.myentity_type.list');
  }

  /**
   * Carries out entity bundle deletion and deletes entities of this type.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    // Find all the entities of this type, using an entity query.
    $query = $this->queryFactory->get('myentity');
    $query->condition('subtype', $this->entity->id());
    $ids = $query->execute();

    // Delete the found entities, using the storage controller.
    // You may actually need to use a batch here, if there could be
    // many entities.
    $storage = $this->manager->getStorage('myentity');
    $entities = $storage->loadMultiple($ids);
    $storage->delete($entities);

    // Delete the bundle entity itself.
    $this->entity->delete();

    $form_state->setRedirectUrl($this->getCancelUrl());
  }
}
