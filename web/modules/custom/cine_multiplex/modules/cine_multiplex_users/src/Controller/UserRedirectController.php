<?php

namespace Drupal\cine_multiplex_users\Controller;

use Drupal\user\Controller\UserController;

/**
 * Controller routines for user routes.
 */
class UserRedirectController extends UserController {

  /**
   * Redirects users to the front page.
   *
   * This controller assumes that it is only invoked for anonymous users.
   * This is enforced for the 'user.page' route with the '_user_is_logged_in'
   * requirement.
   *
   * @return \Symfony\Component\HttpFoundation\RedirectResponse
   *   Returns a redirect to the front page.
   */
  public function userPage() {
    return $this->redirect('<front>');
  }

}
