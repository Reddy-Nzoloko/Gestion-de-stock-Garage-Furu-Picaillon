<?php
declare(strict_types=1);

final class AuthController extends Controller
{
    public function home(): void
    {
        require __DIR__ . '/../views/home/index.php';
    }

    public function login(): void
    {
        if (!empty($_SESSION['user'])) {
            $this->redirect('dashboard');
        }
        $this->render('auth/login', ['pageTitle' => 'Connexion']);
    }

    public function authenticate(): void
    {
        if ((new Auth())->login((string) $this->post('email'), (string) $this->post('password'))) {
            $this->redirect('dashboard');
        }
        $this->flash('error', 'Email ou mot de passe incorrect.');
        $this->redirect('login');
    }

    public function logout(): void
    {
        (new Auth())->logout();
        $this->redirect('login');
    }

    public function password(): void
    {
        $this->render('auth/password', ['pageTitle' => 'Changer mon mot de passe']);
    }

    public function passwordSave(): void
    {
        try {
            if ($this->post('new_password') !== $this->post('confirm_password')) {
                throw new InvalidArgumentException('Les deux nouveaux mots de passe ne correspondent pas.');
            }
            (new Auth())->changePassword((int) $_SESSION['user']['id'], (string) $this->post('current_password'), (string) $this->post('new_password'));
            $this->flash('success', 'Votre mot de passe a été modifié.');
        } catch (Throwable $exception) {
            $this->flash('error', $exception->getMessage());
        }
        $this->redirect('password');
    }
}
