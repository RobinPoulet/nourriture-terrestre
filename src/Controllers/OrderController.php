<?php

namespace App\Controllers;

use App\Controllers\AbstractController;
use App\Core\Http;
use App\DataFetcher\DataFetcher;
use App\Entity\Orders;
use App\Entity\Users;
use App\Helper\Date;
use App\Helper\HelperUser;
use App\Helper\Order;
use App\Http\Request;
use Exception;

require_once './config/constants.php';
class OrderController extends AbstractController
{
    private Orders $objOrder;
    public function __construct()
    {
        $this->objOrder = new Orders();
    }

    /**
     * @throws Exception
     */
    public function index()
    {
        $postData = DataFetcher::getData();
        $objUsers = new Users();
        $users = $objUsers->getAllUsers();
        $displayUsers = HelperUser::tabUsersById($users);
        $dishes = $postData["success"]["dishes"];
        $dateMenu = $postData["success"]["menu"]["CREATION_DATE"];
        $resultsOrder = $this->objOrder->getTodayOrders();
        $displayResults = Order::handleTodayOrders($resultsOrder);
        $tabTotalQuantity = $this->objOrder->getTodayDishTotalQuantity();

        return $this->render("display-orders", [
            "dishes" => $dishes,
            "dateMenu" => $dateMenu,
            "displayResults" => $displayResults,
            "tabTotalQuantity" => $tabTotalQuantity,
            "users" => $displayUsers,
        ]);
    }

    public function edit(Request $request, string $orderId): void
    {
        $perso = htmlspecialchars($request->post('perso') ?? '', ENT_QUOTES, 'UTF-8');
        $dishes = ($request->post('dishes') ?? []);
        $dishesInputErrors = Order::checkDishesInput($dishes);
        if (!empty($dishesInputErrors)) {
            $tabFlashMessage['errors'][] = Order::checkDishesInput($dishes);
        }
        if (empty($tabFlashMessage['errors'])) {
            $userName = $request->post('user-name');
            if ($this->objOrder->edit($orderId, $dishes, $perso)) {
                $tabFlashMessage['success'] = "Ta commande a bien été modifiée $userName";
            } else {
                // La requête a échoué, renvoyer une réponse d'erreur
                $tabFlashMessage['errors'][] = "Erreur lors de la modification de ta commande $userName";
            }
        }

        // Stocker un message flash
        session_start();
        $_SESSION['tab_flash_message'] = $tabFlashMessage;

        Http::redirect('display-orders');
    }

    public function delete($orderId): void
    {
        $objOrder = new Orders();
        $order = $objOrder->find($orderId);

        if (isset($order)) {
            $objUser = new Users();
            $user = $objUser->find($order["USER_ID"]);
            $userName = $user["NAME"];
            if ($objOrder->delete($orderId)) {
                $tabFlashMessage['success'] = "Ta commande a bien été supprimée $userName";
            } else {
                $tabFlashMessage['errors'][] = "Erreur lors de ta suppression $userName";
            }
        } else {
            $tabFlashMessage['error'][] = "La commande n'existe pas";
        }

        // Stocker un message flash
        session_start();
        $_SESSION['tab_flash_message'] = $tabFlashMessage;

        Http::redirect('display-orders');
    }
    
    /**
     * @throws Exception
     */
    public function create()
    {
        $postData = DataFetcher::getData();
        $objUser = new Users();
        $dishes = $postData["success"]["dishes"];
        $dateMenu = $postData["success"]["menu"]["CREATION_DATE"];
        $canDisplayForm = Date::canDisplayOrderForm($dateMenu);
        $users = $objUser->getAllUsers();
        $canDisplayForm = true;

        return $this->render("commande", [
            "users" => $users,
            "dishes" => $dishes,
            "dateMenu" => $dateMenu,
            "createOrderUrl" => COMPLETE_URL."/create-order",
            "canDisplayForm" => $canDisplayForm,
        ]);
    }

    public function store(Request $request)
    {
        $objOrder = new Orders();
        $objUser = new Users();
        $dishes = ($request->post('dishes') ?? []);
        $userId = ($request->post('user') ?? null);
        $perso = htmlspecialchars($request->post('perso') ?? '', ENT_QUOTES, 'UTF-8');
        $dishesInputErrors = Order::checkDishesInput($dishes);
        if (!empty($dishesInputErrors)) {
            $tabFlashMessage['errors'][] = Order::checkDishesInput($dishes);
        }
        if (!isset($userId)) {
            $tabFlashMessage['errors'][] = 'Merci de sélectionner un nom';
        }
        if (empty($tabFlashMessage['errors'])) {
            if ($objOrder->insert((int) $userId, $dishes, $perso)) {
                $user = $objUser->find((int) $userId);
                $userName = $user["NAME"];
                $tabFlashMessage['success'] = "Ta commande a bien été enregistrée $userName";
            } else {
                $tabFlashMessage['errors'][] = 'Erreur lors de l\'enregistrement de la commande.';
            }
        }

        // Stocker un message flash
        session_start();
        $_SESSION['tab_flash_message'] = $tabFlashMessage;

        Http::redirect('display-orders');
    }
}