<?php

namespace App\Controllers;

use App\Core\Http;
use App\Http\Request;
use App\Model\User;
use Exception;

class UserController extends AbstractController
{
    /**
     * @throws Exception
     */
    public function index(): false|string
    {
        $users = User::all("name");

        return $this->render("users", [
            "users" => $users,
        ]);
    }

    public function create(Request $request): void
    {
        $name = htmlspecialchars($request->post('name') ?? '', ENT_QUOTES, 'UTF-8');

        if (empty($name)) {
            $tabFlashMessage['errors'][] = 'Il faut un nom pour l\'utilisateur';
        } else {
            $currentDate = date('Y-m-d');
            $newUser = User::create(["name" => $name, "creation_date" => $currentDate]);
            if ($newUser) {
                $tabFlashMessage['success'] = 'L\'utilisateur ' . $newUser->name . ' a bien été enregistré';
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
        $name = htmlspecialchars($request->post('name') ?? '', ENT_QUOTES, 'UTF-8');

        if (empty($name)) {
            $tabFlashMessage['errors'][] = 'Il faut un nom pour l\'utilisateur';
        } else {
            $userUpdated = User::update($userId, ["name" => $name]);
            if ($userUpdated) {
                $tabFlashMessage['success'] = "Le nom de l'utilisateur $userUpdated->name a bien été modifié";
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
        $userName = User::find($userId)->name;
        $isDeleted = User::delete($userId);
        if ($isDeleted) {
            $tabFlashMessage['success'] = 'L\'utilisateur ' . $userName . ' a bien été supprimé';
        } else {
            $tabFlashMessage['errors'][] = 'Erreur lors de la suppression de ' . $userName;
        }

        // Stocker un message flash
        session_start();
        $_SESSION['tab_flash_message'] = $tabFlashMessage;

       Http::redirect('users');
    }
}