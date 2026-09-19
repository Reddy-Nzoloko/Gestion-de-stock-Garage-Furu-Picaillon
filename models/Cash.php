<?php
declare(strict_types=1);

final class Cash extends Model
{
    public function summary(): array
    {
        return $this->db->query("SELECT COALESCE(SUM(CASE WHEN type_operation='ENTREE' THEN montant ELSE -montant END), 0) AS solde, COUNT(*) AS operations FROM caisse")->fetch() ?: ['solde' => 0, 'operations' => 0];
    }

    public function all(): array
    {
        return $this->db->query('SELECT * FROM caisse ORDER BY date_operation DESC')->fetchAll();
    }

    public function register(array $data): void
    {
        $amount = (float) $data['montant'];
        $reason = trim((string) $data['motif']);
        if ($amount <= 0 || $reason === '') {
            throw new InvalidArgumentException('Le montant doit être positif et le motif est obligatoire.');
        }
        $statement = $this->db->prepare('INSERT INTO caisse (type_operation, montant, motif, utilisateur_id) VALUES (?, ?, ?, ?)');
        $statement->execute([$data['type_operation'], $amount, $reason, 1]);
    }
}
