<?php

namespace App\Controllers;

use App\Core\Http;
use App\DataFetcher\DataFetcher;
use App\Helper\Date;
use App\Helper\HelperOrder;
use App\Http\Request;
use App\Model\Dish;
use App\Model\Order;
use App\Model\User;
use App\Service\CookieManager;
use Exception;

require_once './config/constants.php';
class OrderController extends AbstractController
{
    /** @var string Nom du cookie pour l'id user  */
    private const string COOKIE_NAME = 'selected_user';

    /** @var CookieManager Manager de gestion des cookies */
    private CookieManager $cookieManager;

    /**
     * Constructeur de la classe
     */
    public function __construct()
    {
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
        $menu = $postData['success'];
        $dateMenu = $menu->creation_date;
        $currentDate = date('Y-m-d');
        $resultsOrder = Order::query()
            ->where('creation_date', '=', $currentDate)
            ->get();
        $tabTotalQuantity = Order::getDishTotalQuantityByDate($currentDate);
        $cookieData = $this->cookieManager->get(self::COOKIE_NAME);
        $selectedUserId = (isset($cookieData['user_id']) ? (int)$cookieData['user_id'] : null);

        return $this->render('display-orders', [
            'dishes'           => $menu->dishes(),
            'orders'           => $resultsOrder,
            'dateMenu'         => $dateMenu,
            'tabTotalQuantity' => $tabTotalQuantity,
            'selectedUserId'   => $selectedUserId,
        ]);
    }

    /**
     * Editer une commande
     *
     * @param Request $request Objet request du formulaire
     * @param int $orderId Id de la commande à modifier
     *
     * @return void
     */
    public function edit(Request $request, int $orderId): void
    {
        $perso = htmlspecialchars($request->post('perso') ?? '', ENT_QUOTES, 'UTF-8');
        $dishes = ($request->post('dishes') ?? []);
        $dishesMapped = array_map(fn ($dish) => (['quantity' => (int) $dish]), $dishes);
        $dishesInputErrors = HelperOrder::checkDishesInput($dishes);
        if (!empty($dishesInputErrors)) {
            $tabFlashMessage['errors'][] = $dishesInputErrors;
        }
        if (empty($tabFlashMessage['errors'])) {
            $userName = $request->post('user-name');
            /** @var Order $orderToUpdate */
            $orderToUpdate = Order::find($orderId);
            $orderToUpdate->syncDishes($dishesMapped);
            if (!empty($perso)) {
                Order::update($orderId, ['perso' => $perso]);
            }

            if ($this->hasMatchingQuantities($orderToUpdate->dishes(), $dishesMapped)) {
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

    /**
     * Suuprimer une commande
     *
     * @param int $orderId Id de la commande à supprimer
     *
     * @return void
     */
    public function delete(int $orderId): void
    {
        $order = Order::find($orderId);

        if ($order !== null) {
            $user = $order->user();
            if (Order::delete($orderId)) {
                $tabFlashMessage['success'] = "Ta commande a bien été supprimée $user->name";
            } else {
                $tabFlashMessage['errors'][] = "Erreur lors de la suppression de te commande $user->name";
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
     * Afficher le formulaire de prise de commande
     *
     * @return false|string
     * @throws Exception
     */
    public function create(): false|string
    {
        $postData = DataFetcher::getData();
        $menu = $postData['success'];
        $dateMenu = $menu->creation_date;
        $canDisplayForm = Date::canDisplayOrderForm($dateMenu);
        $users = User::all('name');
        $canDisplayForm = true;
        $cookieData = $this->cookieManager->get(self::COOKIE_NAME);
        $selectedUserId = (isset($cookieData['user_id']) ? (int)$cookieData['user_id'] : null);

        return $this->render('commande', [
            'users'          => $users,
            'dishes'         => $menu->dishes(),
            'dateMenu'       => $dateMenu,
            'createOrderUrl' => COMPLETE_URL . '/create-order',
            'canDisplayForm' => $canDisplayForm,
            'selectedUserId' => $selectedUserId,
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
        $dishes = ($request->post('dishes') ?? []);
        $userId = ($request->post('user') ?? null);
        $perso = htmlspecialchars($request->post('perso') ?? '', ENT_QUOTES, 'UTF-8');
        $dishesInputErrors = HelperOrder::checkDishesInput($dishes);
        if (!empty($dishesInputErrors)) {
            $tabFlashMessage['errors'][] = $dishesInputErrors;
        }
        if (!isset($userId)) {
            $tabFlashMessage['errors'][] = "Merci de sélectionner un nom";
        }
        if (empty($tabFlashMessage['errors'])) {
            /** @var Order $newOrder */
            $newOrder = Order::create(['user_id' => $userId, 'perso' => $perso]);
            if ($newOrder) {
                $newOrder->attachDishes($dishes);
                $this->cookieManager->refreshIfNeeded(self::COOKIE_NAME, ['user_id' => (int)$userId]);
                $user = User::find($userId);
                $tabFlashMessage['success'] = "Ta commande a bien été enregistrée $user->name";
            } else {
                $tabFlashMessage['errors'][] = "Erreur lors de l'enregistrement de la commande.";
            }
        }

        // Stocker un message flash
        session_start();
        $_SESSION['tab_flash_message'] = $tabFlashMessage;

        if (!empty($tabFlashMessage['errors'])) {
            Http::redirect('commande');
        } else {
            Http::redirect('display-orders');
        }

    }

    /**
     * Vérifie si chaque dish dans $dishes a une quantité correspondant à $expectedQuantities[dish_id].
     *
     * @param Dish[] $dishes
     * @param array<int, array> $expectedQuantities
     * @return bool
     */
    public function hasMatchingQuantities(array $dishes, array $expectedQuantities): bool
    {
        foreach ($dishes as $dish) {
            if (!isset($expectedQuantities[$dish->id]['quantity'])) {
                return false; // id manquant
            }

            $expectedQuantity = $expectedQuantities[$dish->id]['quantity'];
            $actualQuantity = $dish->pivot->quantity ?? null;
            if ($actualQuantity !== $expectedQuantity) {
                return false;
            }
        }

        return true;
    }
}