<?php

namespace Drupal\cine_multiplex_services\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Exception\ConnectException;
use Alchemy\Zippy\Exception\RuntimeException;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use GuzzleHttp\Exception\TooManyRedirectsException;
use Drupal\cine_multiplex_services\Services\AesEncryptService;

/**
 * Class Manager.
 */
class Manager implements ManagerInterface {

  /**
   * Endpoint storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $endpointStorage;

  /**
   * Guzzle client.
   *
   * @var \GuzzleHttp\Client
   */
  protected $client;

  /**
   * Custom service encode/decode.
   *
   * @var \Drupal\cine_multiplex_services\Services\AesEncryptService
   */
  protected $aesService;

  /**
   * Entity.
   *
   * @var \Drupal\score_entity\EndpointInterface
   */
  protected $entity;

  /**
   * Headers for request.
   *
   * @var array
   */
  protected $headers = [];

  /**
   * Query for request.
   *
   * @var array
   */
  protected $query = [];

  /**
   * Body for request.
   *
   * @var array
   */
  protected $body = [];

  /**
   * ApiHandler constructor.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   *   Entity storage.
   * @param \GuzzleHttp\Client $client
   *   Guzzle client.
   * @param \Drupal\cine_multiplex_services\Services\AesEncryptService $aesService
   *   Entity storage.
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function __construct(EntityTypeManagerInterface $entityTypeManager, Client $client, AesEncryptService $aesService) {
    $this->endpointStorage = $entityTypeManager->getStorage(static::ENTITY_TYPE_ID);
    $this->client = $client;
    $this->aesService = $aesService;
  }

  /**
   * {@inheritdoc}
   */
  public function load($id) {
    $this->entity = $this->endpointStorage->load($id);

    if (empty($this->entity)) {
      throw new RuntimeException('The score service "' . $id . '" was not found.');
    }

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setHeaders(array $headers) {
    $this->headers = $headers;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setQuery(array $query) {
    $this->query = $query;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setBody(array $body) {
    $this->body = $body;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function encode() {
    $body = json_encode($this->body);
    $this->body = $this
      ->aesService
      ->encrypt($body);

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function sendRequest() {
    $method = $this->entity->get('method');
    $url = $this->entity->get('endpoint');

    $options = [
      'headers' => $this->headers,
      'query' => $this->query,
      'timeout' => 60,
    ];

    if (!empty($this->body)) {
      $options['body'] = json_encode($this->body, TRUE);
    }

    try {
      $this->response = $this->client
        ->request($method, $url, $options);

      return $this;
    }
    catch (ServerException $exception) {
      $this->setErrorLog($exception);
      throw $exception;
    }
    catch (ClientException $exception) {
      $this->setErrorLog($exception);
      throw $exception;
    }
    catch (ConnectException $exception) {
      $this->setErrorLog($exception);
      throw $exception;
    }
    catch (TooManyRedirectsException $exception) {
      $this->setErrorLog($exception);
      throw $exception;
    }
    catch (GuzzleException $exception) {
      $this->setErrorLog($exception);
      throw $exception;
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getResponse() {
    $body = (string) $this->response->getBody();
    return json_decode($body, TRUE);
  }


  /**
   * {@inheritdoc}
   */
  public function getResponseDecode() {
    $response = $this->getResponse();
    $request = $this
      ->aesService
      ->decrypt($response[0]['request']);

    return json_decode($request, TRUE);
  }

  /**
   * Set error information in drupal log.
   *
   * @param \GuzzleHttp\Exception $exception
   *   Exception when a client error is encountered.
   *
   * @throws \GuzzleHttp\Exception
   */
  private function setErrorLog(GuzzleException $exception) {
    $params = [
      '@url' => $this->entity->get('endpoint'),
      '@method' => $this->entity->get('method'),
      '@headers' => json_encode($this->headers),
      '@query' => json_encode($this->query),
      '@body' => json_encode($this->body),
      '@message' => $exception->getMessage(),
    ];

    \Drupal::logger('multiplex_services')
      ->error('url: @url<br> params: @method<br> headers: @headers<br> query: @query<br> body: @body<br> response: @message', $params);
  }

}
