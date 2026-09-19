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
}
