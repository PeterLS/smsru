<?php

namespace Zelenin\SmsRu\Response;

class AbstractResponse {
  public string $code;
  protected array $availableDescriptions = [];
  public function __construct($code) {
    $this->code = $code;
  }

  public function getDescription(): ?string {
    return $this->availableDescriptions[$this->code] ?? NULL;
  }
}
