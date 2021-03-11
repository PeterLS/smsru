<?php

namespace Zelenin\SmsRu\Auth\TokenCache;

use Zelenin\SmsRu\Exception\Exception;

class FileCache implements CacheInterface {

  /**
   * @var null|string
   */
  private ?string $path;

  /**
   * @param string|null $path
   * @throws Exception
   */
  public function __construct(?string $path = NULL) {
    $this->path = empty($path) ? implode(DIRECTORY_SEPARATOR, [sys_get_temp_dir(), 'cache']) : $path;

    if (!is_dir($this->path)) {
      $this->mkdir($this->path);
    }

    if (!is_writable($this->path)) {
      throw new Exception(sprintf('Cache directory is not writable: %s', $path));
    }
  }

  /**
   * @inheritdoc
   */
  public function exists(string $key): bool {
    $cacheFile = $this->getCacheFile($key);

    return @filemtime($cacheFile) > time();
  }

  /**
   * @inheritdoc
   */
  public function get(string $key) {
    $cacheFile = $this->getCacheFile($key);

    if (@filemtime($cacheFile) > time()) {
      $fp = @fopen($cacheFile, 'rb');
      if ($fp !== FALSE) {
        @flock($fp, LOCK_SH);
        $cacheValue = @stream_get_contents($fp);
        @flock($fp, LOCK_UN);
        @fclose($fp);

        return empty($cacheValue) ? FALSE : $cacheValue;
      }
    }

    return FALSE;
  }

  /**
   * @inheritdoc
   * @throws Exception
   */
  public function set(string $key, $value, ?int $ttl = NULL): bool {
    $cacheFile = $this->getCacheFile($key);

    if (@file_put_contents($cacheFile, $value, LOCK_EX) !== FALSE) {
      @chmod($cacheFile, 0666);

      $ttl = (int)$ttl;
      $ttl = empty($ttl) ? 31536000 // 1 year
        : $ttl;

      return @touch($cacheFile, $ttl + time());
    }

    $error = error_get_last();

    throw new Exception(sprintf('Unable to write cache file "%s": %s', $cacheFile, $error['message']));
  }

  /**
   * @inheritdoc
   */
  public function remove(string $key): bool {
    $cacheFile = $this->getCacheFile($key);

    return @unlink($cacheFile);
  }

  /**
   * @param string $key
   *
   * @return string
   */
  private function getCacheFile(string $key): string {
    return $this->path . DIRECTORY_SEPARATOR . $key . '.bin';
  }

  /**
   * @param string $path
   * @param integer $mode
   * @param bool $recursive
   *
   * @return bool
   * @throws Exception
   */
  private function mkdir(string $path, int $mode = 0775, bool $recursive = TRUE): bool {
    if (is_dir($path)) {
      return TRUE;
    }

    $parentDir = dirname($path);
    // recurse if parent dir does not exist and we are not at the root of the file system.
    if ($recursive && $parentDir !== $path && !is_dir($parentDir)) {
      $this->mkdir($parentDir, $mode);
    }

    try {
      if (!mkdir($path, $mode) && !is_dir($path)) {
        return FALSE;
      }
    } catch (\Exception $e) {
      if (!is_dir($path)) {
        throw new Exception(sprintf('Failed to create directory "%s": %s', $path, $e->getMessage()), $e->getCode(), $e);
      }
    }

    try {
      return chmod($path, $mode);
    } catch (\Exception $e) {
      throw new Exception(sprintf('Failed to change permissions for directory "%s": %s', $path, $e->getMessage()), $e->getCode(), $e);
    }
  }
}
