<?php

namespace Zelenin\SmsRu\Auth\TokenCache;

class ArrayCache implements CacheInterface {

  /**
   * @var array
   */
  protected array $cache = [];

  /**
   * @inheritdoc
   */
  public function exists(string $key): bool {
    return isset($this->cache[$key]) && ($this->cache[$key][1] === 0 || $this->cache[$key][1] > microtime(TRUE));
  }

  /**
   * @inheritdoc
   */
  public function get(string $key): bool {
    return isset($this->cache[$key]) && ($this->cache[$key][1] === 0 || $this->cache[$key][1] > microtime(TRUE)) ? $this->cache[$key][0] : FALSE;
  }

  /**
   * @inheritdoc
   */
  public function set(string $key, $value, $ttl = NULL): bool {
    $ttl = (int)$ttl;
    $ttl = empty($ttl) ? 31536000 // 1 year
      : $ttl;
    $this->cache[$key] = [$value, microtime(TRUE) + $ttl];

    return TRUE;
  }

  /**
   * @inheritdoc
   */
  public function remove(string $key): bool {
    unset($this->cache[$key]);

    return TRUE;
  }
}
