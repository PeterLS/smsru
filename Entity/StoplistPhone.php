<?php

namespace Zelenin\SmsRu\Entity;

class StoplistPhone {

  /**
   * @var string
   */
  public string $phone;

  /**
   * @var string
   */
  public string $text;

  /**
   * @param string $phone
   * @param string $text
   */
  public function __construct(string $phone, string $text) {
    $this->phone = $phone;
    $this->text = $text;
  }
}
