<?php

namespace App\Controllers;

use App\Core\Http;
use App\Entity\Users;
use App\Http\Request;
use Exception;

class UserController extends AbstractController
{
    /**
     * @throws Exception
     */
    public function index(): false|string
    {
        $objUsers = new Users();
        $users = $objUsers->getAllUsers();

        return $this->render("users", [
            "users" => $users,
        ]);
    }

    public function create(Request $request): void
    {
        $objUsers = new Users();
        $name = htmlspecialchars($request->post('name') ?? '', ENT_QUOTES, 'UTF-8');

        if (empty($name)) {
            $tabFlashMessage['errors'][] = 'Il faut un nom pour l\'utilisateur';
        } else {
            if ($objUsers->insert($name)) {
                $tabFlashMessage['success'] = 'L\'utilisateur ' . $name . ' a bien été enregistré';
            } else {
                $tabFlashMessage['errors'][] = 'Erreur lors de l\'ajout de l\'utilisateur ' . $name;
            }

        }

        // Stocker un message flash
        session_start();
        $_SESSION['tab_flash_message'] = $tabFlashMessage;

        Http::redirect('users');
    }

    public function edit(Request $request, string $userId): void
    {
        $objUsers = new Users();
        $name = htmlspecialchars($request->post('name') ?? '', ENT_QUOTES, 'UTF-8');
        $id = (int) $userId;

        if (empty($name)) {
            $tabFlashMessage['errors'][] = 'Il faut un nom pour l\'utilisateur';
        } else {
            if ($objUsers->edit($id, $name)) {
                $tabFlashMessage['success'] = "Le nom de l'utilisateur $name a bien été modifié";
            } else {
                $tabFlashMessage['error'][] = 'Erreur lors de la modification du nom de l\'utilisateur';
            }
        }

        // Stocker un message flash
        session_start();
        $_SESSION['tab_flash_message'] = $tabFlashMessage;

        Http::redirect('users');
    }

    public function delete($userId): void
    {
        $objUsers = new Users();
        $user = $objUsers->find($userId);

        if ($objUsers->delete($userId)) {
            $tabFlashMessage['success'] = 'L\'utilisateur ' . $user['NAME'] . ' a bien été supprimé';
        } else {
            $tabFlashMessage['errors'][] = 'Erreur lors de la suppression de ' . $user['NAME'];
        }

        // Stocker un message flash
        session_start();
        $_SESSION['tab_flash_message'] = $tabFlashMessage;

       Http::redirect('users');
    }

    public function getAllUsers($data)
    {
        $objUsers = new Users();
        $response = [];

        $users = $objUsers->getAllUsers();
        if (!isset($users['error'])) {
            $response['success'] = $users;
        } else {
            $response = $users;
        }

        return $response;
    }
}