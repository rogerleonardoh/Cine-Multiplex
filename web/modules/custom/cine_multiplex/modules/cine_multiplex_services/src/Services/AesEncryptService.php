<?php

namespace Drupal\cine_multiplex_services\Services;

/**
 * Implement class AesEncryptService.
 */
class AesEncryptService {

  /**
   * Encrypt data.
   *
   * @param mixed $data
   *   Data.
   *
   * @return string
   *   Encrypted information.
   */
  public function encrypt($data) {
    $aesSettings = \Drupal::config('cine_multiplex_services.score_settings')->get('aes');
    return openssl_encrypt($data, $this->encryptionMethod(), $aesSettings['secret_key'], 0, $aesSettings['iv']);
  }

  /**
   * Decrypt data.
   *
   * @param mixed $data
   *   Data.
   *
   * @return string
   *   Decrypted information.
   */
  public function decrypt($data) {
    $aesSettings = \Drupal::config('cine_multiplex_services.score_settings')->get('aes');
    return openssl_decrypt($data, $this->encryptionMethod(), $aesSettings['secret_key'], 0, $aesSettings['iv']);
  }

  /**
   * Get encryption method.
   *
   * @return string
   *   Encryption method.
   */
  private function encryptionMethod() {
    $aesSettings = \Drupal::config('cine_multiplex_services.score_settings')->get('aes');
    $methods = openssl_get_cipher_methods();
    return $methods[$aesSettings['method']];
  }

}
