<?php

namespace Zelenin\SmsRu\Auth\TokenCache;

class DummyCache implements CacheInterface {

  /**
   * @inheritdoc
   */
  public function exists(string $key): bool {
    return FALSE;
  }

  /**
   * @inheritdoc
   */
  public function get(string $key): bool {
    return FALSE;
  }

  /**
   * @inheritdoc
   */
  public function set(string $key, $value, ?int $ttl = NULL): bool {
    return TRUE;
  }

  /**
   * @inheritdoc
   */
  public function remove(string $key): bool {
    return TRUE;
  }
}
