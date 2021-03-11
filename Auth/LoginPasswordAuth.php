<?php

namespace Zelenin\SmsRu\Auth;

class LoginPasswordAuth extends AbstractAuth {
  /**
   * @var string
   */
  private string $login;

  /**
   * @var string
   */
  private string $password;

  /**
   * @var null|string
   */
  private ?string $partnerId;

  /**
   * @param string $login
   * @param string $password
   * @param null|string $partnerId
   */
  public function __construct(string $login, string $password, ?string $partnerId = NULL) {
    $this->login = $login;
    $this->password = $password;
    $this->partnerId = $partnerId;
  }

  /**
   * @return array
   */
  public function getAuthParams(): array {
    return [
      'login' => $this->login,
      'password' => $this->password,
    ];
  }

  /**
   * @return null|string
   */
  public function getPartnerId(): ?string {
    return $this->partnerId;
  }
}
