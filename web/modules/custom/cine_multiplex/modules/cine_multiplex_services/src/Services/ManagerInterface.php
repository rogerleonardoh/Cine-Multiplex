<?php

namespace Drupal\cine_multiplex_services\Services;

interface ManagerInterface {

  /**
   * Entity type id.
   */
  const ENTITY_TYPE_ID = 'score_entity';

  /**
   * Load entity.
   *
   * @param string $id
   *   Score service id.
   *
   * @return \Drupal\cine_multiplex_services\Services\ManagerInterface
   *   Manager interface.
   */
  public function load($id);

  /**
   * Set headers for request.
   *
   * @param array $headers
   *   Headers params.
   *
   * @return \Drupal\cine_multiplex_services\Services\ManagerInterface
   *   Manager interface.
   */
  public function setHeaders(array $headers);

  /**
   * Set query for request.
   *
   * @param array $query
   *   Query params.
   *
   * @return \Drupal\cine_multiplex_services\Services\ManagerInterface
   *   Manager interface.
   */
  public function setQuery(array $query);

  /**
   * Set body for request.
   *
   * @param array $body
   *   Body params.
   *
   * @return \Drupal\cine_multiplex_services\Services\ManagerInterface
   *   Manager interface.
   */
  public function setBody(array $body);

  /**
   * Encode body data.
   *
   * @return \Drupal\cine_multiplex_services\Services\ManagerInterface
   *   Manager interface.
   */
  public function encode();

  /**
   * Create and send an HTTP request.
   *
   * @throws \GuzzleHttp\Exception
   */
  public function sendRequest();

  /**
   * Retrieve response for the request.
   *
   * @return array
   *   Decodes a JSON array
   */
  public function getResponse();

  /**
   * Retrieve response for the request.
   *
   * @return array
   *   Decodes a JSON array
   */
  public function getResponseDecode();

}
