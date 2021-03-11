<?php

namespace Zelenin\SmsRu\Auth\TokenCache;

interface CacheInterface {

  /**
   * @param string $key
   *
   * @return boolean
   */
  public function exists(string $key): bool;

  /**
   * @param string $key
   *
   * @return mixed
   */
  public function get(string $key);

  /**
   * @param string $key
   * @param mixed $value
   * @param integer|null $ttl
   *
   * @return mixed
   */
  public function set(string $key, $value, ?int $ttl = NULL);

  /**
   * @param string $key
   *
   * @return boolean
   */
  public function remove(string $key): bool;
}
