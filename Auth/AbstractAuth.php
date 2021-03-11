<?php

namespace Zelenin\SmsRu\Auth;

use Zelenin\SmsRu\Api;

abstract class AbstractAuth implements AuthInterface {

  /**
   * @var Api
   */
  private Api $context;

  /**
   * @return array
   */
  abstract public function getAuthParams(): array;

  /**
   * @return null|string
   */
  abstract public function getPartnerId(): ?string;

  /**
   * @return Api
   */
  public function getContext(): Api {
    return $this->context;
  }

  /**
   * @param Api $context
   */
  public function setContext(Api $context): void {
    $this->context = $context;
  }
}
