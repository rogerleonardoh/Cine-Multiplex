<?php

namespace Drupal\cine_multiplex_users\Routing;

use Drupal\Core\Routing\RouteSubscriberBase;
use Symfony\Component\Routing\RouteCollection;

/**
 * Listens to the dynamic route events.
 *
 * Class OneappTfaRouteSubscriber.
 *
 * @package Drupal\oneapp_tfa\Routing
 */
class CineMultiplexUsersRouteSubscriber extends RouteSubscriberBase {

  /**
   * Overrides user.login route with our custom login form.
   *
   * @param \Symfony\Component\Routing\RouteCollection $collection
   *   Route collection.
   */
  public function alterRoutes(RouteCollection $collection) {
    if ($route = $collection->get('user.page')) {
      $route->setRequirement('_user_is_logged_in', 'FALSE');
      $route->setDefault('_controller', '\Drupal\oneapp_tfa\Controller\UserEditController::userPage');
    }
    if ($route = $collection->get('entity.user.canonical')) {
      $route->setDefault('_controller', '\Drupal\oneapp_tfa\Controller\UserEditController::userPage');
    }
    // Change path of user login to our overridden TFA login form.
    if ($route = $collection->get('user.login')) {
      $route->setPath('/secure/login');
    }
  }

}
