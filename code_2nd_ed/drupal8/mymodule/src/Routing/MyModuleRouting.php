<?php

/**
 * @file
 * Contains \Drupal\mymodule\Routing\MyModuleRouting.
 */

namespace Drupal\mymodule\Routing;

use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Drupal\Core\Routing\RouteSubscriberBase;

/**
 * Provides dynamic route and route alter.
 *
 * From "Event Subscribers in Drupal 8: Altering Routes and Providing
 * Dynamic Routes" in Chapter 4.
 */
class MyModuleRouting extends RouteSubscriberBase {
  /**
   * {@inheritdoc}
   */
  protected function alterRoutes(RouteCollection $collection) {
    // Alter the title of the People administration page (admin/people).
    $route = $collection->get('user.admin_account');
    $route->setDefault('_title', 'User accounts');
    // Make sure that the title text is translatable.
    $foo = t('User accounts');

    // Add a dynamic route at admin/people/mymodule, which could have been
    // a static route in this case.
    $path = $route->getPath();
    // Constructor parameters: path, defaults, requirements, as you would have
    // in a routing.yml file.
    $newroute = new Route($path . '/mymodule', array(
        '_controller' => '\Drupal\mymodule\Controller\MyUrlController::generateMyPage',
        '_title' => 'New page title',
      ), array(
        '_permission' => 'administer mymodule',
      ));
    // Make sure that the title text is translatable.
    $foo = t('New page title');
    $collection->add('mymodule.newroutename', $newroute);
  }
}
