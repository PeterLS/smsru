# sms_ru

PHP-класс для работы с api сервиса [sms.ru](http://sms.ru)

## Установка
официальная
[документация](https://getcomposer.org/doc/05-repositories.md#loading-a-package-from-a-vcs-repository)
по добавлению

добавьте

```js
"repositories": [
  {
    "type": "vcs",
    "url": "https://github.com/peterls/smsru"
  }
],

"require": {
  "peterls/smsru": "dev-bugfix"
}
```

в ваш файл ```composer.json```

## Использование

Простая авторизация (с помощью api_id):

```php
$client = new \Zelenin\SmsRu\Api(new \Zelenin\SmsRu\Auth\ApiIdAuth($apiId));
```

Усиленная авторизация (с помощью api_id, логина и пароля):

```php
$client = new \Zelenin\SmsRu\Api(new \Zelenin\SmsRu\Auth\LoginPasswordSecureAuth($login, $password, $apiId));
```

Усиленная авторизация (с помощью логина и пароля):

```php
$client = new \Zelenin\SmsRu\Api(new \Zelenin\SmsRu\Auth\LoginPasswordAuth($login, $password));
```

Отправка SMS:

```php
$sms1 = new \Zelenin\SmsRu\Entity\Sms($phone1, $text1);
$sms1->translit = 1;
$sms2 = new \Zelenin\SmsRu\Entity\Sms($phone2, $text2);

$client->smsSend($sms1);
$client->smsSend($sms2);

$client->smsSend(new \Zelenin\SmsRu\Entity\SmsPool([$sms1, $sms2]));
```

Статус SMS:

```php
$send = $client->smsSend($sms);
$smsId = $send->ids[0];
$client->smsStatus($smsId);
```

Стоимость SMS:

```php
$client->smsCost(new \Zelenin\SmsRu\Entity\Sms($phone, $text));
```

Баланс:

```php
$client->myBalance();
```

Дневной лимит:

```php
$client->myLimit();
```

Отправители:

```php
$client->mySenders();
```

Проверка валидности логина и пароля:

```php
$client->authCheck();
```

Добавить номер в стоплист:

```php
$client->stoplistAdd($phone, $text);
```

Удалить номер из стоп-листа:

```php
$client->stoplistDel($phone);
```

Получить номера стоплиста:

```php
$client->stoplistGet();
```
