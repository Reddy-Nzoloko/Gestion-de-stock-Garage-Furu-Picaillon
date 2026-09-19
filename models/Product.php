<?php
declare(strict_types=1);

final class Product extends Model
{
    public function all(string $search = '', string $category = ''): array
    {
        $sql = 'SELECT p.*, c.nom AS categorie,
                CASE WHEN p.quantite = 0 THEN \'Rupture\' WHEN p.quantite < :seuil THEN \'Stock faible\' ELSE \'En stock\' END AS etat
                FROM produits p LEFT JOIN categories c ON c.id = p.category_id WHERE 1=1';
        $params = [':seuil' => STOCK_LOW_THRESHOLD];
        if ($search !== '') {
            $sql .= ' AND (p.nom LIKE :search OR p.sku LIKE :search OR p.emplacement LIKE :search)';
            $params[':search'] = '%' . $search . '%';
        }
        if ($category !== '') {
            $sql .= ' AND p.category_id = :category';
            $params[':category'] = (int) $category;
        }
        $sql .= ' ORDER BY p.nom ASC';
        $statement = $this->db->prepare($sql);
        $statement->execute($params);
        return $statement->fetchAll();
    }

    public function find(int $id): ?array
    {
        $statement = $this->db->prepare('SELECT * FROM produits WHERE id = ?');
        $statement->execute([$id]);
        return $statement->fetch() ?: null;
    }

    public function categories(): array
    {
        return $this->db->query('SELECT * FROM categories ORDER BY nom')->fetchAll();
    }

    public function save(array $data): void
    {
        $sku = strtoupper(trim((string) $data['sku']));
        $quantity = filter_var($data['quantite'], FILTER_VALIDATE_INT);
        $purchasePrice = (float) $data['prix_achat'];
        $salePrice = (float) $data['prix_vente'];
        if ($sku === '' || trim((string) $data['nom']) === '' || $quantity === false || $quantity < 0 || $purchasePrice < 0 || $salePrice < 0) {
            throw new InvalidArgumentException('Les informations du produit sont invalides.');
        }
        $params = [$sku, trim((string) $data['nom']), $data['category_id'] ?: null, trim((string) $data['emplacement']), $quantity, $purchasePrice, $salePrice, trim((string) ($data['icone'] ?? ''))];
        if (!empty($data['id'])) {
            $params[] = (int) $data['id'];
            $statement = $this->db->prepare('UPDATE produits SET sku=?, nom=?, category_id=?, emplacement=?, quantite=?, prix_achat=?, prix_vente=?, icone=? WHERE id=?');
        } else {
            $statement = $this->db->prepare('INSERT INTO produits (sku, nom, category_id, emplacement, quantite, prix_achat, prix_vente, icone) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
        }
        $statement->execute($params);
    }

    public function delete(int $id): void
    {
        $statement = $this->db->prepare('DELETE FROM produits WHERE id = ?');
        $statement->execute([$id]);
    }
}
