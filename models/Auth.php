<?php
declare(strict_types=1);

final class Auth extends Model
{
    public function login(string $email, string $password): bool
    {
        $statement = $this->db->prepare('SELECT u.*, r.nom AS role_nom FROM utilisateurs u JOIN roles r ON r.id = u.role_id WHERE u.email = ? AND u.actif = 1 LIMIT 1');
        $statement->execute([trim($email)]);
        $user = $statement->fetch();
        if (!$user || !password_verify($password, $user['mot_de_passe'])) {
            return false;
        }
        $_SESSION['user'] = ['id' => (int) $user['id'], 'nom' => trim($user['prenom'] . ' ' . $user['nom']), 'role' => $user['role_nom']];
        return true;
    }

    public function logout(): void
    {
        unset($_SESSION['user']);
    }
}
