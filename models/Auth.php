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

    public function changePassword(int $userId, string $currentPassword, string $newPassword): void
    {
        if (strlen($newPassword) < 8) {
            throw new InvalidArgumentException('Le nouveau mot de passe doit contenir au moins 8 caractères.');
        }
        $statement = $this->db->prepare('SELECT mot_de_passe FROM utilisateurs WHERE id = ? AND actif = 1');
        $statement->execute([$userId]);
        $hash = $statement->fetchColumn();
        if (!$hash || !password_verify($currentPassword, $hash)) {
            throw new InvalidArgumentException('Le mot de passe actuel est incorrect.');
        }
        $update = $this->db->prepare('UPDATE utilisateurs SET mot_de_passe = ? WHERE id = ?');
        $update->execute([password_hash($newPassword, PASSWORD_DEFAULT), $userId]);
    }
}
