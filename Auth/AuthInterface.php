<?php

namespace Zelenin\SmsRu\Auth;

use Zelenin\SmsRu\Api;

interface AuthInterface {
  /**
   * @return array
   */
  public function getAuthParams(): array;

  /**
   * @return null|string
   */
  public function getPartnerId(): ?string;

  /**
   * @return Api
   */
  public function getContext(): Api;

  /**
   * @param Api $context
   */
  public function setContext(Api $context): void;
}
