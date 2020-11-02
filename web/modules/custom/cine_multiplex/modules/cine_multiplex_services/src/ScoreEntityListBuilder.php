<?php

namespace Drupal\cine_multiplex_services;

use Drupal\Core\Config\Entity\ConfigEntityListBuilder;
use Drupal\Core\Entity\EntityInterface;

/**
 * Provides a listing of Score Service entities.
 */
class ScoreEntityListBuilder extends ConfigEntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['label'] = $this->t('Service');
    $header['method'] = $this->t('Method');
    $header['endpoint'] = $this->t('Endpoint');
    $header['aesEncryption'] = $this->t('AES encryption');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /** @var \Drupal\cine_multiplex_services\Entity\ScoreEntityInterface $entity */
    $row['label'] = $entity->label();
    $row['method'] = $entity->get('method');
    $row['endpoint'] = $entity->get('endpoint');
    $row['aesEncryption'] = ($entity->get('aesEncryption')) ? 'Si' : 'No';

    return $row + parent::buildRow($entity);
  }

}
