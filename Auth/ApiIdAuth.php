<?php

namespace Zelenin\SmsRu\Auth;

class ApiIdAuth extends AbstractAuth {

  /**
   * @var string
   */
  private string $apiId;

  /**
   * @var null|string
   */
  private ?string $partnerId;

  /**
   * @param string $apiId
   * @param null|string $partnerId
   */
  public function __construct(string $apiId, ?string $partnerId = NULL) {
    $this->apiId = $apiId;
    $this->partnerId = $partnerId;
  }

  /**
   * @return array
   */
  public function getAuthParams(): array {
    return [
      'api_id' => $this->apiId,
    ];
  }

  /**
   * @return string
   */
  public function getApiId(): string {
    return $this->apiId;
  }

  /**
   * @return null|string
   */
  public function getPartnerId(): ?string {
    return $this->partnerId;
  }
}
