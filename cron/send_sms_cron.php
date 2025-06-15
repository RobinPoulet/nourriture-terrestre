<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Controllers\SendSMSController;
use App\Model\Menu;
use App\Model\Order;
use App\Model\SmsResponse;
use Dotenv\Dotenv;
use GuzzleHttp\Exception\GuzzleException;
use Pusher\PusherException;
use function PHPUnit\Framework\throwException;

$dotenv = Dotenv::createImmutable(__DIR__.'/../');
$dotenv->load();

$options = array(
    'cluster' => $_ENV['PUSHER_CLUSTER'],
    'useTLS'  => true
);

$sendSMSController = new SendSMSController();

// On récupère les quantités totals de la commande
$currentDate = date('Y-m-d');
$tabTotalQuantity = Order::getDishTotalQuantityByDate($currentDate);
// On prend le dernier menu de la base
$lastMenu = Menu::last();

if (
    $lastMenu->is_open
    && !empty($tabTotalQuantity)
) {
    $sendSMSController->send($tabTotalQuantity, $lastMenu->id, $_ENV['SMS_DEVICE_ID'], $_ENV['API_KEY_SMS']);

    try {
        $pusher = new Pusher\Pusher(
            $_ENV['PUSHER_KEY'],
            $_ENV['PUSHER_SECRET'],
            $_ENV['PUSHER_APP_ID'],
            $options
        );
        $smsResponse = SmsResponse::query()
            ->where('menu_id', '=', $lastMenu->id)
            ->first();
        if (
            $smsResponse
            && $smsResponse->status === 'success'
        ) {
            $data['message'] = 'Envoyé avec succès à '. (new \DateTime($smsResponse->created_at))->format('H:i');
            $data['status'] = $smsResponse->status;
        } else {
            $data['message'] = 'Echec de l\'envoi';
            $data['status'] = 'danger';
        }
        $pusher->trigger('send-sms', 'send-sms', $data);
    } catch (PusherException|GuzzleException|PDOException|DateMalformedStringException $e) {
        throwException($e);
    }
}
