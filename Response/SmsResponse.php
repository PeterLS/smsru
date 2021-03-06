<?php

namespace Zelenin\SmsRu\Response;

class SmsResponse extends AbstractResponse {

  /**
   * @var integer[]
   */
  public array $ids = [];

  /**
   * @var array
   */
  protected array $availableDescriptions = [
    '100' => 'Сообщение принято к отправке. На следующих строчках вы найдете идентификаторы отправленных сообщений в том же порядке, в котором вы указали номера, на которых совершалась отправка.',
    '200' => 'Неправильный api_id.',
    '201' => 'Не хватает средств на лицевом счету.',
    '202' => 'Неправильно указан получатель.',
    '203' => 'Нет текста сообщения.',
    '204' => 'Имя отправителя не согласовано с администрацией.',
    '205' => 'Сообщение слишком длинное (превышает 8 СМС).',
    '206' => 'Будет превышен или уже превышен дневной лимит на отправку сообщений.',
    '207' => 'На этот номер (или один из номеров) нельзя отправлять сообщения, либо указано более 100 номеров в списке получателей.',
    '208' => 'Параметр time указан неправильно.',
    '209' => 'Вы добавили этот номер (или один из номеров) в стоп-лист.',
    '210' => 'Используется GET, где необходимо использовать POST.',
    '211' => 'Метод не найден.',
    '212' => 'Текст сообщения необходимо передать в кодировке UTF-8 (вы передали в другой кодировке).',
    '220' => 'Сервис временно недоступен, попробуйте чуть позже.',
    '230' => 'Сообщение не принято к отправке, так как на один номер в день нельзя отправлять более 60 сообщений.',
    '300' => 'Неправильный token (возможно истек срок действия, либо ваш IP изменился).',
    '301' => 'Неправильный пароль, либо пользователь не найден.',
    '302' => 'Пользователь авторизован, но аккаунт не подтвержден (пользователь не ввел код, присланный в регистрационной смс).',
  ];
}
