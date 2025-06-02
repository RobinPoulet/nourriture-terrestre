<?php

namespace App\Controllers;

use App\Http\Request;
use App\Model\Dish;
use Infobip\Configuration;
use Infobip\ApiException;
use Infobip\Model\SmsRequest;
use Infobip\Model\SmsDestination;
use Infobip\Model\SmsMessage;
use Infobip\Api\SmsApi;
use Infobip\Model\SmsTextContent;

class SendSMSController extends AbstractController
{
    private const string PHONE_NUMBER_DESTINATION = '33636149634';
    private Configuration $configuration;
    public function __construct()
    {
        $this->configuration = new Configuration(
            host: HOST_SMS,
            apiKey: API_KEY_SMS,
        );
    }

    public function send(Request $request): void
    {
        $message = $this->buildMessage($request->post('totalQuantity'));

        $sendSmsApi = new SmsApi(config: $this->configuration);

        $message = new SmsMessage(
            destinations: [
                new SmsDestination(
                    to: self::PHONE_NUMBER_DESTINATION,
                )
            ],
            content: new SmsTextContent(
                text: $message
            ),
            sender: '447491163443'
        );

        $request = new SmsRequest(messages: [$message]);

        try {
            $smsResponse = $sendSmsApi->sendSmsMessages($request);
            var_dump($smsResponse);
        } catch (ApiException $apiException) {
            // HANDLE THE EXCEPTION
        }
    }

    private function buildMessage(array $tabTotalDishes): string
    {
        $tabMessages = [];
        // Reconstitution du total en message
        foreach ($tabTotalDishes as $dishId => $quantity) {
            if ($quantity > 0) {
                $dishName = Dish::find($dishId)->name;
                $tabMessages[] = $quantity . " " . $dishName;
            }
        }

        return "Commande MyDSO : ".implode(", ", $tabMessages);
    }
}