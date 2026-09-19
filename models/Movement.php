<?php
declare(strict_types=1);

final class Movement extends Model
{
    public function recent(int $limit = 8): array
    {
        $limit = max(1, min($limit, 50));
        return $this->db->query("SELECT m.*, p.nom AS produit_nom, p.sku FROM mouvements m LEFT JOIN produits p ON p.id = m.produit_id ORDER BY m.date_mouvement DESC LIMIT {$limit}")->fetchAll();
    }

    public function all(): array
    {
        return $this->db->query('SELECT m.*, p.nom AS produit_nom, p.sku FROM mouvements m LEFT JOIN produits p ON p.id = m.produit_id ORDER BY m.date_mouvement DESC')->fetchAll();
    }

    public function findById(int $id): ?array
    {
        $statement = $this->db->prepare('SELECT m.*, p.nom AS produit_nom, p.sku, p.prix_vente FROM mouvements m LEFT JOIN produits p ON p.id = m.produit_id WHERE m.id = ?');
        $statement->execute([$id]);
        return $statement->fetch() ?: null;
    }

    public function today(): array
    {
        return $this->db->query('SELECT m.*, p.nom AS produit_nom, p.sku FROM mouvements m LEFT JOIN produits p ON p.id = m.produit_id WHERE DATE(m.date_mouvement) = CURDATE() ORDER BY m.date_mouvement DESC')->fetchAll();
    }

    public function register(array $data): int
    {
        $productId = (int) $data['produit_id'];
        $quantity = filter_var($data['quantite'], FILTER_VALIDATE_INT);
        $unitPrice = (float) $data['prix_unitaire'];
        if ($quantity === false || $quantity <= 0 || $unitPrice < 0) {
            throw new InvalidArgumentException('La quantité doit être entière et positive, et le prix ne peut pas être négatif.');
        }
        $this->db->beginTransaction();
        try {
            $lock = $this->db->prepare('SELECT quantite, prix_vente FROM produits WHERE id = ? FOR UPDATE');
            $lock->execute([$productId]);
            $product = $lock->fetch();
            if (!$product) {
                throw new InvalidArgumentException('Référence introuvable.');
            }
            $type = $data['type_mouvement'];
            if (!in_array($type, ['ACHAT', 'VENTE'], true)) {
                throw new InvalidArgumentException('Type de mouvement invalide.');
            }
            $newQuantity = $type === 'VENTE' ? (int) $product['quantite'] - $quantity : (int) $product['quantite'] + $quantity;
            if ($type === 'VENTE' && $newQuantity < 0) {
                throw new InvalidArgumentException('Vente refusée : le stock disponible est insuffisant.');
            }
            $total = $quantity * $unitPrice;
            $update = $this->db->prepare('UPDATE produits SET quantite = ? WHERE id = ?');
            $update->execute([$newQuantity, $productId]);
            $insert = $this->db->prepare('INSERT INTO mouvements (type_mouvement, produit_id, utilisateur_id, quantite, prix_unitaire, montant_total, tier_nom, mode_paiement) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
            $insert->execute([$type, $productId, (int) ($_SESSION['user']['id'] ?? 0), $quantity, $unitPrice, $total, trim((string) ($data['tier_nom'] ?? '')) ?: null, $data['mode_paiement'] ?: null]);
            $movementId = (int) $this->db->lastInsertId();
            $cashType = $type === 'VENTE' ? 'ENTREE' : 'SORTIE';
            $cashMotif = ($type === 'VENTE' ? 'Vente ' : 'Achat ') . 'mouvement #' . $movementId;
            $cash = $this->db->prepare('INSERT INTO caisse (type_operation, montant, motif, utilisateur_id, mouvement_id) VALUES (?, ?, ?, ?, ?)');
            $cash->execute([$cashType, $total, $cashMotif, (int) ($_SESSION['user']['id'] ?? 0), $movementId]);
            $this->db->commit();
            return $movementId;
        } catch (Throwable $exception) {
            $this->db->rollBack();
            throw $exception;
        }
    }
}
