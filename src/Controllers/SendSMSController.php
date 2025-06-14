<?php

namespace App\Controllers;

use App\Model\Dish;
use App\Model\SmsResponse;

class SendSMSController extends AbstractController
{
    private const string PHONE_NUMBER_DESTINATION = '+33636149634';
    private const int HTTP_CODE_SUCCESS = 201;
    public function send(array $totalQuantity, int $menuId, string $deviceId, string $apiKey): void
    {
        $url = 'https://api.textbee.dev/api/v1/gateway/devices/'.$deviceId.'/send-sms';

        $message = $this->formatSmsKeyValue($totalQuantity);

        $data = [
            'recipients' => [self::PHONE_NUMBER_DESTINATION], // Numéro en format international
            'message'    => $message,
        ];

        $headers = [
            'Content-Type: application/json',
            'x-api-key: '.$apiKey
        ];

        $curl = curl_init($url);
        curl_setopt_array($curl, [
            CURLOPT_POST           => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER         => true,               // On veut récupérer les entêtes HTTP + le corps
            CURLOPT_HTTPHEADER     => $headers,
            CURLOPT_POSTFIELDS     => json_encode($data),
        ]);

        $response = curl_exec($curl);

        // Si une erreur cURL est survenue
        if (curl_errno($curl)) {
            echo '❌ Erreur cURL : ' . curl_error($curl);
            curl_close($curl);
            exit;
        }

        // On récupère les infos
        $headerSize = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $body = substr($response, $headerSize);

        if ($httpCode === self::HTTP_CODE_SUCCESS) {
            $jsonResponse = json_decode($body, true);
            $this->storeMessageResponse($jsonResponse, $message, $menuId);
        }

        curl_close($curl);
    }

    private function storeMessageResponse(array $jsonResponse, string $message, int $menuId): void
    {
        $dateNow = date('Y-m-d H:i:s');

        SmsResponse::create([
            'message'      => $message,
            'destination'  => self::PHONE_NUMBER_DESTINATION,
            'sms_batch_id' => $jsonResponse['data']['smsBatchId'],
            'status'       => 'success',
            'menu_id'      => $menuId,
            'created_at'   => $dateNow,
        ]);
    }

    function formatSmsKeyValue(array $data): string {
        $lines = [];

        foreach ($data as $key => $value) {
            $lines[] = explode(" ",Dish::find($key)->name)[0] . ': ' . $value;
        }

        // Double quote obligatoire sinon le saut de ligne ne se fait pas sur le téléphone
        return "Commande MyDSO\n".implode("\n", $lines);
    }
}