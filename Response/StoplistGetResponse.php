<?php

namespace Zelenin\SmsRu\Response;

use Zelenin\SmsRu\Entity\StoplistPhone;

class StoplistGetResponse extends AbstractResponse {

  /**
   * @var StoplistPhone[]
   */
  public array $phones = [];

  /**
   * @var array
   */
  protected array $availableDescriptions = [
    '100' => 'Запрос обработан. На последующих строчках будут идти номера телефонов, указанных в стоплисте в формате номер;примечание.',
  ];
}
