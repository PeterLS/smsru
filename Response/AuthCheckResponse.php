<?php

namespace Zelenin\SmsRu\Response;

class AuthCheckResponse extends AbstractResponse {

  /**
   * @var array
   */
  protected array $availableDescriptions = [
    '100' => 'ОК, номер телефона и пароль совпадают.',
    '300' => 'Неправильный token (возможно истек срок действия, либо ваш IP изменился).',
    '301' => 'Неправильный пароль, либо пользователь не найден.',
    '302' => 'Пользователь авторизован, но аккаунт не подтвержден (пользователь не ввел код, присланный в регистрационной смс).',
  ];
}
