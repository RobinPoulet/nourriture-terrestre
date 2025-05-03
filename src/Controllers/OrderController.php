<?php

namespace App\Controllers;

use App\Core\Http;
use App\DataFetcher\DataFetcher;
use App\Entity\Orders;
use App\Entity\Users;
use App\Helper\Date;
use App\Helper\HelperUser;
use App\Helper\Order;
use App\Http\Request;
use App\Service\CookieManager;
use Exception;

require_once "./config/constants.php";
class OrderController extends AbstractController
{
    /** @var string Nom du cookie pour l'id user  */
    private const string COOKIE_NAME = "selected_user";

    /** @var Orders Entité Orders */
    private Orders $objOrder;

    /** @var CookieManager Manager de gestion des cookies */
    private CookieManager $cookieManager;

    /**
     * Constructeur de la classe
     */
    public function __construct()
    {
        $this->objOrder = new Orders();
        $this->cookieManager = new CookieManager();
    }

    /**
     * Controller pour la route index
     *
     * @return false|string
     * @throws Exception
     */
    public function index(): false|string
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

    /**
     * Editer une commande
     *
     * @param Request $request Objet request du formulaire
     * @param string $orderId Id de la commande à modifier
     *
     * @return void
     */
    public function edit(Request $request, int $orderId): void
    {
        $perso = htmlspecialchars($request->post("perso") ?? "", ENT_QUOTES, "UTF-8");
        $dishes = ($request->post("dishes") ?? []);
        $dishesInputErrors = Order::checkDishesInput($dishes);
        if (!empty($dishesInputErrors)) {
            $tabFlashMessage["errors"][] = Order::checkDishesInput($dishes);
        }
        if (empty($tabFlashMessage["errors"])) {
            $userName = $request->post("user-name");
            if ($this->objOrder->edit($orderId, $dishes, $perso)) {
                $tabFlashMessage["success"] = "Ta commande a bien été modifiée $userName";
            } else {
                // La requête a échoué, renvoyer une réponse d'erreur
                $tabFlashMessage["errors"][] = "Erreur lors de la modification de ta commande $userName";
            }
        }

        // Stocker un message flash
        session_start();
        $_SESSION["tab_flash_message"] = $tabFlashMessage;

        Http::redirect("display-orders");
    }

    /**
     * Suuprimer une commande
     *
     * @param int $orderId Id de la commande à supprimer
     *
     * @return void
     */
    public function delete(int $orderId): void
    {
        $objOrder = new Orders();
        $order = $objOrder->find($orderId);

        if (isset($order)) {
            $objUser = new Users();
            $user = $objUser->find($order["USER_ID"]);
            $userName = $user["NAME"];
            if ($objOrder->delete($orderId)) {
                $tabFlashMessage["success"] = "Ta commande a bien été supprimée $userName";
            } else {
                $tabFlashMessage["errors"][] = "Erreur lors de ta suppression $userName";
            }
        } else {
            $tabFlashMessage["error"][] = "La commande n'existe pas";
        }

        // Stocker un message flash
        session_start();
        $_SESSION["tab_flash_message"] = $tabFlashMessage;

        Http::redirect("display-orders");
    }
    
    /**
     * Afficher le formulaire de prise de commande
     *
     * @return false|string
     * @throws Exception
     */
    public function create(): false|string
    {
        $postData = DataFetcher::getData();
        $objUser = new Users();
        $dishes = $postData["success"]["dishes"];
        $dateMenu = $postData["success"]["menu"]["CREATION_DATE"];
        $canDisplayForm = Date::canDisplayOrderForm($dateMenu);
        $users = $objUser->getAllUsers();
        $canDisplayForm = true;
        $cookieData = $this->cookieManager->get(self::COOKIE_NAME);
        $selectedUserId = (isset($cookieData["user_id"]) ? (int)$cookieData["user_id"] : null);

        return $this->render("commande", [
            "users" => $users,
            "dishes" => $dishes,
            "dateMenu" => $dateMenu,
            "createOrderUrl" => COMPLETE_URL."/create-order",
            "canDisplayForm" => $canDisplayForm,
            "selectedUserId" => $selectedUserId,
        ]);
    }

    /**
     * Enregistrer une nouvelle commande en base
     *
     * @param Request $request Objet request du formulaire
     *
     * @return void
     */
    public function store(Request $request): void
    {
        $objOrder = new Orders();
        $objUser = new Users();
        $dishes = ($request->post("dishes") ?? []);
        $userId = ($request->post("user") ?? null);
        $perso = htmlspecialchars($request->post("perso") ?? "", ENT_QUOTES, "UTF-8");
        $dishesInputErrors = Order::checkDishesInput($dishes);
        if (!empty($dishesInputErrors)) {
            $tabFlashMessage["errors"][] = Order::checkDishesInput($dishes);
        }
        if (!isset($userId)) {
            $tabFlashMessage["errors"][] = "Merci de sélectionner un nom";
        }
        if (empty($tabFlashMessage["errors"])) {
            if ($objOrder->insert((int) $userId, $dishes, $perso)) {
                $this->cookieManager->refreshIfNeeded(self::COOKIE_NAME, ["user_id" => (int)$userId]);
                $user = $objUser->find((int) $userId);
                $userName = $user["NAME"];
                $tabFlashMessage["success"] = "Ta commande a bien été enregistrée $userName";
            } else {
                $tabFlashMessage["errors"][] = "Erreur lors de l'enregistrement de la commande.";
            }
        }

        // Stocker un message flash
        session_start();
        $_SESSION["tab_flash_message"] = $tabFlashMessage;

        Http::redirect("display-orders");
    }
}