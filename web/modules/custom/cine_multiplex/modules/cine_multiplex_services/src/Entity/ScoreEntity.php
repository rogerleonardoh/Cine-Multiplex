<?php

namespace Drupal\cine_multiplex_services\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;

/**
 * Defines the Score Service entity.
 *
 * @ConfigEntityType(
 *   id = "score_entity",
 *   label = "Score",
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\cine_multiplex_services\ScoreEntityListBuilder",
 *     "form" = {
 *       "add" = "Drupal\cine_multiplex_services\Form\ScoreEntityForm",
 *       "edit" = "Drupal\cine_multiplex_services\Form\ScoreEntityForm",
 *       "delete" = "Drupal\cine_multiplex_services\Form\ScoreEntityDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\cine_multiplex_services\ScoreEntityHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "score_entity",
 *   admin_permission = "administer site configuration",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/admin/config/cine-multiplex/entities/config/score/{score_entity}",
 *     "add-form" = "/admin/config/cine-multiplex/entities/config/score/add",
 *     "edit-form" = "/admin/config/cine-multiplex/entities/config/score/{score_entity}/edit",
 *     "delete-form" = "/admin/config/cine-multiplex/entities/config/score/{score_entity}/delete",
 *     "collection" = "/admin/config/cine-multiplex/entities/config/score"
 *   }
 * )
 */
class ScoreEntity extends ConfigEntityBase implements ScoreEntityInterface {

  /**
   * The Score Service ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The Score Service label.
   *
   * @var string
   */
  protected $label;

  /**
   * The Score Service endpoint.
   *
   * @var string
   */
  protected $endpoint;

  /**
   * The Score Service aesEncryption.
   *
   * @var string
   */
  protected $aesEncryption;

}
