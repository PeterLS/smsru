<?php

namespace Zelenin\SmsRu\Client;

use GuzzleHttp\Exception\GuzzleException;
use Zelenin\SmsRu\Exception\Exception;

class Client implements ClientInterface {
  /**
   * @var string
   */
  private string $baseUrl = 'https://sms.ru/{method}';

  /**
   * @var \GuzzleHttp\Client
   */
  private \GuzzleHttp\Client $client;

  /**
   * @param array $config Additional configuration for Guzzle Client
   */
  public function __construct(array $config = []) {
    $this->client = new \GuzzleHttp\Client($config);
  }

  /**
   * @param string $method
   * @param array $params
   *
   * @return string
   *
   * @throws Exception|GuzzleException
   */
  public function request(string $method, array $params = []): string {
    $response = $this->client->post($this->getUrl($method), ['query' => $params]);

    if ($response->getStatusCode() === 200) {
      return (string)$response->getBody();
    }

    throw new Exception(sprintf('Sms.ru problem. Status code is %s', $response->getStatusCode()), $response->getStatusCode());
  }

  /**
   * @param string $method
   *
   * @return string
   */
  private function getUrl(string $method): string {
    return strtr($this->baseUrl, ['{method}' => $method]);
  }
}
