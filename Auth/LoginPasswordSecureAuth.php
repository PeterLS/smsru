<?php

namespace Zelenin\SmsRu\Auth;

use Zelenin\SmsRu\Auth\TokenCache\CacheInterface;
use Zelenin\SmsRu\Auth\TokenCache\DummyCache;
use Zelenin\SmsRu\Exception\Exception;

class LoginPasswordSecureAuth extends AbstractAuth {

  /**
   * @var string
   */
  private string $login;

  /**
   * @var string
   */
  private string $password;

  /**
   * @var null|string
   */
  private ?string $apiId;

  /**
   * @var null|string
   */
  private ?string $partnerId;

  /**
   * @var CacheInterface
   */
  private CacheInterface $cache;

  /**
   * @var string
   */
  private string $cacheKey = 'zelenin.smsru.auth.token';

  /**
   * @param string $login
   * @param string $password
   * @param null|string $apiId
   * @param CacheInterface|null $cache
   * @param null|string $partnerId
   */
  public function __construct(string $login, string $password, ?string $apiId = NULL, CacheInterface $cache = NULL, ?string $partnerId = NULL) {
    $this->login = $login;
    $this->password = $password;
    $this->apiId = $apiId;
    $this->cache = $cache ?? new DummyCache;
    $this->partnerId = $partnerId ?? '88622';
  }

  /**
   * @return array
   * @throws Exception
   */
  public function getAuthParams(): array {
    $token = $this->authGetToken();

    return [
      'login' => $this->login,
      'token' => $token,
      'sha512' => !empty($this->apiId) ? hash('sha512', $this->password . $token . $this->apiId) : hash('sha512', $this->password . $token),
    ];
  }

  /**
   * @return null|string
   */
  public function getApiId(): ?string {
    return $this->apiId;
  }

  /**
   * @return null|string
   */
  public function getPartnerId(): ?string {
    return $this->partnerId;
  }

  /**
   * @return string
   * @throws Exception
   */
  private function authGetToken(): ?string {
    $token = NULL;
    if ($this->cache->exists($this->cacheKey)) {
      $token = $this->cache->get($this->cacheKey);
    }

    if (!$token) {
      $token = $this->requestAuthToken();
      $this->cache->set($this->cacheKey, $token, 60 * 9);
    }

    return $token;
  }

  /**
   * @return string
   * @throws Exception
   */
  private function requestAuthToken(): string {
    return $this->getContext()->getClient()->request('auth/get_token');
  }
}
