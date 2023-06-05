<?php

namespace app\common\behaviors;

use yii\httpclient\Client;

class SmsNotification extends \yii\base\Behavior
{
    const EVENT_SEND_SMS_NOTIFICATION = 'event_send_sms_notification';

    public Client $client;
    private string $key;

    public function __construct($config = [])
    {
        $this->client = new \yii\httpclient\Client(['baseUrl' => 'http://smspilot.ru']);
        $this->key = \Yii::$app->params['smsNotificationApiKey'];
        parent::__construct($config);
    }

    /**
     * @return string[]
     */
    public function events()
    {
        return [
            self::EVENT_SEND_SMS_NOTIFICATION => 'sendSmsNotification'
        ];
    }

    /**
     * @param $event
     * @return void
     */
    public function sendSmsNotification($event)
    {
        if ($event->sender instanceof \app\common\interfaces\SmsNotification) {
            if ($phoneNumbers = $event->sender->getPhoneNumbersForSms()) {
                $text = $event->sender->getTextForSms();
                foreach ($phoneNumbers as $phoneNumber) {
                    try {
                        $response = $this->client->get('/api.php', [
                            'send' => $text,
                            'to' => $phoneNumber,
                            'apikey' => $this->key
                        ])->send()->getData();
                        if (isset($response['ERROR'])) {
                            throw new \Exception('Bad response: ' . $response['ERROR']);
                        }
                    } catch (\Exception $exception) {
                        \Yii::error($exception, 'smsNotification');
                    }
                }
            }
        }
    }
}