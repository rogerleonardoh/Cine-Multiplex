<?php

namespace Drupal\cine_multiplex_services\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class ScoreSettingsForm extends ConfigFormBase {

  /**
   * Config settings.
   *
   * @var string
   */
  const SETTINGS = 'cine_multiplex_services.score_settings';

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      static::SETTINGS,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'score_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config(static::SETTINGS);

    $form['#tree'] = TRUE;
    $form['encrypt'] = [
      '#type' => 'vertical_tabs',
      '#prefix' => '<h2><small>' . $this->t('Encripción') . '</small></h2>',
      '#weight' => -10,
      '#default_tab' => $config->get('active_tab'),
    ];

    $group = 'aes';
    $form[$group] = [
      '#type' => 'details',
      '#title' => $this->t('AES'),
      '#group' => 'encrypt',
    ];
    $form[$group]['secret_key'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Secret Key'),
      '#default_value' => isset($config->get($group)['secret_key']) ? $config->get($group)['secret_key'] : '',
      '#required' => TRUE,
    ];
    $form[$group]['method'] = [
      '#type' => 'select',
      '#title' => $this->t('Metodo'),
      '#options' => openssl_get_cipher_methods(),
      '#default_value' => isset($config->get($group)['method']) ? $config->get($group)['method'] : array_search('aes-128-cbc', openssl_get_cipher_methods()),
      '#description' => $this->t('Encryption method'),
      '#required' => TRUE,
    ];
    $form[$group]['iv'] = [
      '#type' => 'textfield',
      '#title' => $this->t('IV'),
      '#default_value' => isset($config->get($group)['iv']) ? $config->get($group)['iv'] : '',
      '#description' => $this->t('Enter IV Used During Encryption(Optional)'),
      '#required' => FALSE,
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    $this->configFactory
      ->getEditable(static::SETTINGS)
      ->set('aes', $form_state->getValue('aes'))
      ->save();

    parent::submitForm($form, $form_state);
    $this->messenger()
      ->addMessage($this->t('The configuration options have been saved.'));
  }

}
