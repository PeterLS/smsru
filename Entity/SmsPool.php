<?php

namespace Zelenin\SmsRu\Entity;

class SmsPool extends AbstractSms {

  /**
   * @var Sms[]
   */
  public array $messages;

  /**
   * @param Sms[] $messages
   */
  public function __construct(array $messages) {
    $this->messages = $messages;
  }
}
