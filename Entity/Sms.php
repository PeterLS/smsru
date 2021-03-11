<?php

namespace Zelenin\SmsRu\Entity;

class Sms extends AbstractSms {

  /**
   * @var string
   */
  public string $to;

  /**
   * @var null|string
   */
  public ?string $text;

  /**
   * @param $to
   * @param null|string $text
   */
  public function __construct($to, ?string $text = NULL) {
    $this->to = (string)$to;
    $this->text = $text;
  }
}
