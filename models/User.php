<?php
declare(strict_types=1);

final class User extends Model
{
    public function all(): array
    {
        return $this->db->query('SELECT u.id, u.nom, u.prenom, u.email, u.actif, u.cree_le, r.nom AS role_nom FROM utilisateurs u JOIN roles r ON r.id = u.role_id ORDER BY u.nom, u.prenom')->fetchAll();
    }

    public function roles(): array
    {
        return $this->db->query("SELECT id, nom, description FROM roles WHERE nom IN ('Administrateur', 'Vendeur', 'Dépôt') ORDER BY FIELD(nom, 'Administrateur', 'Vendeur', 'Dépôt')")->fetchAll();
    }

    public function create(array $data): void
    {
        $nom = trim((string) ($data['nom'] ?? ''));
        $prenom = trim((string) ($data['prenom'] ?? ''));
        $email = trim((string) ($data['email'] ?? ''));
        $password = (string) ($data['mot_de_passe'] ?? '');
        $roleId = (int) ($data['role_id'] ?? 0);
        if ($nom === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 8 || $roleId < 1) {
            throw new InvalidArgumentException('Nom, email, rôle et mot de passe de 8 caractères minimum sont obligatoires.');
        }
        $role = $this->db->prepare("SELECT id FROM roles WHERE id = ? AND nom IN ('Administrateur', 'Vendeur', 'Dépôt')");
        $role->execute([$roleId]);
        if (!$role->fetchColumn()) {
            throw new InvalidArgumentException('Rôle non autorisé.');
        }
        $statement = $this->db->prepare('INSERT INTO utilisateurs (role_id, nom, prenom, email, mot_de_passe) VALUES (?, ?, ?, ?, ?)');
        $statement->execute([$roleId, $nom, $prenom ?: null, $email, password_hash($password, PASSWORD_DEFAULT)]);
    }

    public function delete(int $userId, int $currentUserId): void
    {
        if ($userId < 1 || $userId === $currentUserId) {
            throw new InvalidArgumentException('Vous ne pouvez pas supprimer votre propre compte.');
        }
        $statement = $this->db->prepare('SELECT r.nom FROM utilisateurs u JOIN roles r ON r.id = u.role_id WHERE u.id = ?');
        $statement->execute([$userId]);
        $role = $statement->fetchColumn();
        if (!$role) {
            throw new InvalidArgumentException('Utilisateur introuvable.');
        }
        if ($role === 'Administrateur') {
            $count = (int) $this->db->query("SELECT COUNT(*) FROM utilisateurs u JOIN roles r ON r.id = u.role_id WHERE r.nom = 'Administrateur' AND u.actif = 1")->fetchColumn();
            if ($count <= 1) {
                throw new InvalidArgumentException('Le dernier administrateur ne peut pas être supprimé.');
            }
        }
        $delete = $this->db->prepare('DELETE FROM utilisateurs WHERE id = ?');
        $delete->execute([$userId]);
    }
}
