<?php

namespace Zelenin\SmsRu\Entity;

abstract class AbstractSms {

  /**
   * @var string
   */
  public string $from;

  /**
   * @var string
   */
  public string $time;

  /**
   * @var string
   */
  public string $translit;

  /**
   * @var string
   */
  public string $test;

  /**
   * @var string
   */
  public string $partner_id;
}
