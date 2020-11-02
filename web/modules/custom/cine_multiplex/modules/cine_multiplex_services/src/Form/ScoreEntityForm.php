<?php

namespace Drupal\cine_multiplex_services\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class ScoreEntityForm.
 */
class ScoreEntityForm extends EntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);
    $entity = $this->getEntity();

    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Service'),
      '#maxlength' => 255,
      '#default_value' => $entity->label(),
      '#description' => $this->t("Name the Score Service."),
      '#required' => TRUE,
    ];
    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $entity->id(),
      '#machine_name' => [
        'exists' => '\Drupal\cine_multiplex_services\Entity\ScoreEntity::load',
      ],
      '#disabled' => !$entity->isNew(),
    ];
    $form['endpoint'] = [
      '#type' => 'url',
      '#title' => $this->t('Endpoint'),
      '#maxlength' => 255,
      '#default_value' => $entity->get('endpoint'),
      '#description' => $this->t("URL for the Score Service."),
      '#required' => TRUE,
    ];
    $form['method'] = [
      '#type' => 'select',
      '#title' => $this->t('Metodo'),
      '#options' => [
        'GET' => 'GET',
        'POST' => 'POST',
        'PUT' => 'PUT',
        'DELETE' => 'DELETE',
      ],
      '#default_value' => $entity->get('method'),
      '#description' => $this->t('Call method'),
      '#required' => TRUE,
    ];
    $form['aesEncryption'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('AES Encryption'),
      '#default_value' => $entity->get('aesEncryption'),
      '#description' => $this->t("Flag to implement AES encryption for the Score Service."),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $entity = $this->getEntity();
    $entity->set('endpoint', $form_state->getValue('endpoint'));
    $entity->set('method', $form_state->getValue('method'));
    $entity->set('aesEncryption', $form_state->getValue('aesEncryption'));
    $status = $entity->save();

    switch ($status) {
      case SAVED_NEW:
        $this->messenger()->addMessage($this->t('Created the %label Score.', [
          '%label' => $entity->label(),
        ]));
        break;

      default:
        $this->messenger()->addMessage($this->t('Saved the %label Score.', [
          '%label' => $entity->label(),
        ]));
    }
    $form_state->setRedirectUrl($entity->toUrl('collection'));
  }

}
