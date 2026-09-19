<?php
declare(strict_types=1);

final class Category extends Model
{
    public function all(): array
    {
        return $this->db->query('SELECT c.id, c.nom, COUNT(p.id) AS produits FROM categories c LEFT JOIN produits p ON p.category_id = c.id GROUP BY c.id, c.nom ORDER BY c.nom')->fetchAll();
    }

    public function create(string $name): void
    {
        $name = trim($name);
        if ($name === '' || mb_strlen($name) > 100) {
            throw new InvalidArgumentException('Le nom de catégorie est obligatoire et doit rester court.');
        }
        $statement = $this->db->prepare('INSERT INTO categories (nom) VALUES (?)');
        $statement->execute([$name]);
    }
}
