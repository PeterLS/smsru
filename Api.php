<?php

namespace Zelenin\SmsRu;

use Zelenin\SmsRu\Auth\AuthInterface;
use Zelenin\SmsRu\Client\Client;
use Zelenin\SmsRu\Client\ClientInterface;
use Zelenin\SmsRu\Entity\AbstractSms;
use Zelenin\SmsRu\Entity\Sms;
use Zelenin\SmsRu\Entity\SmsPool;
use Zelenin\SmsRu\Entity\StoplistPhone;
use Zelenin\SmsRu\Exception\Exception;
use Zelenin\SmsRu\Response\AuthCheckResponse;
use Zelenin\SmsRu\Response\MyBalanceResponse;
use Zelenin\SmsRu\Response\MyLimitResponse;
use Zelenin\SmsRu\Response\MySendersResponse;
use Zelenin\SmsRu\Response\SmsCostResponse;
use Zelenin\SmsRu\Response\SmsResponse;
use Zelenin\SmsRu\Response\SmsStatusResponse;
use Zelenin\SmsRu\Response\StoplistAddResponse;
use Zelenin\SmsRu\Response\StoplistDelResponse;
use Zelenin\SmsRu\Response\StoplistGetResponse;

class Api {
  /**
   * @var AuthInterface
   */
  private AuthInterface $auth;

  /**
   * @var ClientInterface
   */
  private ClientInterface $client;

  /**
   * @param AuthInterface $auth
   */
  public function __construct(AuthInterface $auth) {
    $this->auth = $auth;
    $this->auth->setContext($this);
  }

  /**
   * @param AbstractSms $sms
   *
   * @return SmsResponse
   * @throws Exception
   */
  public function smsSend(AbstractSms $sms): SmsResponse {
    $params = [];

    if ($sms instanceof Sms) {
      $params['to'] = $sms->to;
      $params['text'] = $sms->text;
    } elseif ($sms instanceof SmsPool) {
      foreach ($sms->messages as $message) {
        $params['multi'][$message->to] = $message->text;
      }
    } else {
      throw new Exception('Only Sms or SmsPool instances');
    }

    if ($sms->from) {
      $params['from'] = $sms->from;
    }

    if ($sms->time && $sms->time < (time() + 7 * 24 * 60 * 60)) {
      $params['time'] = $sms->time;
    }

    if ($sms->translit) {
      $params['translit'] = 1;
    }

    if ($sms->test) {
      $params['test'] = 1;
    }

    if ($sms->partner_id) {
      $params['partner_id'] = $sms->partner_id;
    } elseif ($this->getAuth()->getPartnerId()) {
      $params['partner_id'] = $this->getAuth()->getPartnerId();
    }

    $response = $this->request('sms/send', $params);
    $response = explode("\n", $response);

    $smsResponse = new SmsResponse(array_shift($response));

    if ($smsResponse->code == 100) {
      foreach ($response as $id) {
        if (!preg_match('/=/', $id)) {
          $smsResponse->ids[] = $id;
        }
        //                else {
        //                    $result = explode('=', $id);
        //                    $response[$result[0]] = $result[1];
        //                }
      }
    }

    return $smsResponse;
  }

  /**
   * @param string $id
   *
   * @return SmsStatusResponse
   */
  public function smsStatus(string $id): SmsStatusResponse {
    $response = $this->request('sms/status', [
        'id' => $id,
      ]);

    $response = explode("\n", $response);

    return new SmsStatusResponse(array_shift($response));
  }

  /**
   * @param Sms $sms
   *
   * @return SmsCostResponse
   */
  public function smsCost(Sms $sms): SmsCostResponse {
    $params = [
      'to' => $sms->to,
      'text' => $sms->text,
    ];

    $response = $this->request('sms/cost', $params);
    $response = explode("\n", $response);

    $smsCostResponse = new SmsCostResponse(array_shift($response));
    $smsCostResponse->price = (float)$response[0];
    $smsCostResponse->length = (int)$response[1];

    return $smsCostResponse;
  }

  /**
   * @return MyBalanceResponse
   */
  public function myBalance(): MyBalanceResponse {
    $response = $this->request('my/balance');
    $response = explode("\n", $response);

    $myBalanceResponse = new MyBalanceResponse(array_shift($response));
    $myBalanceResponse->balance = (float)$response[0];

    return $myBalanceResponse;
  }

  /**
   * @return MyLimitResponse
   */
  public function myLimit(): MyLimitResponse {
    $response = $this->request('my/limit');
    $response = explode("\n", $response);

    $myLimitResponse = new MyLimitResponse(array_shift($response));
    $myLimitResponse->limit = (int)$response[0];
    $myLimitResponse->current = (int)$response[1];

    return $myLimitResponse;
  }

  /**
   * @return MySendersResponse
   */
  public function mySenders(): MySendersResponse {
    $response = $this->request('my/senders');
    $response = explode("\n", $response);

    $mySendersResponse = new MySendersResponse(array_shift($response));

    foreach ($response as $phone) {
      if ($phone) {
        $mySendersResponse->phones[] = $phone;
      }
    }

    return $mySendersResponse;
  }

  /**
   * @return AuthCheckResponse
   */
  public function authCheck(): AuthCheckResponse {
    $response = $this->request('auth/check');
    $response = explode("\n", $response);

    return new AuthCheckResponse(array_shift($response));
  }

  /**
   * @param string $stoplistPhone
   * @param string $stoplistText
   *
   * @return StoplistAddResponse
   */
  public function stoplistAdd(string $stoplistPhone, string $stoplistText): StoplistAddResponse {
    $response = $this->request('stoplist/add', [
        'stoplist_phone' => $stoplistPhone,
        'stoplist_text' => $stoplistText,
      ]);

    $response = explode("\n", $response);

    return new StoplistAddResponse(array_shift($response));
  }

  /**
   * @param string $stoplistPhone
   *
   * @return StoplistDelResponse
   */
  public function stoplistDel(string $stoplistPhone): StoplistDelResponse {
    $response = $this->request('stoplist/del', [
        'stoplist_phone' => $stoplistPhone,
      ]);

    $response = explode("\n", $response);

    return new StoplistDelResponse(array_shift($response));
  }

  /**
   * @return StoplistGetResponse
   */
  public function stoplistGet(): StoplistGetResponse {
    $response = $this->request('stoplist/get');
    $response = explode("\n", $response);

    $stoplistGetResponse = new StoplistGetResponse(array_shift($response));

    foreach ($response as $phone) {
      $phone = explode(';', $phone);
      $stoplistGetResponse->phones[] = new StoplistPhone($phone[0], $phone[1]);
    }

    return $stoplistGetResponse;
  }

  /**
   * @param string $method
   * @param array $params
   *
   * @return string
   */
  public function request(string $method, array $params = []): string {
    return $this->getClient()->request($method, array_merge($params, $this->getAuth()->getAuthParams()));
  }

  /**
   * @return ClientInterface
   */
  public function getClient(): ClientInterface {
    if ($this->client === NULL) {
      $this->client = new Client();
    }

    return $this->client;
  }

  /**
   * @param ClientInterface $client
   */
  public function setClient(ClientInterface $client) {
    $this->client = $client;
  }

  /**
   * @return AuthInterface
   */
  private function getAuth(): AuthInterface {
    return $this->auth;
  }
}
