<?php
declare(strict_types=1);

final class UserController extends Controller
{
    public function index(): void
    {
        $user = new User();
        $this->render('users/index', ['users' => $user->all(), 'roles' => $user->roles(), 'pageTitle' => 'Utilisateurs']);
    }

    public function save(): void
    {
        try {
            (new User())->create($_POST);
            $this->flash('success', 'Le compte a été créé avec le rôle sélectionné.');
        } catch (Throwable $exception) {
            $message = $exception instanceof PDOException && $exception->errorInfo[1] === 1062 ? 'Cette adresse email existe déjà.' : $exception->getMessage();
            $this->flash('error', $message);
        }
        $this->redirect('utilisateurs');
    }
}
