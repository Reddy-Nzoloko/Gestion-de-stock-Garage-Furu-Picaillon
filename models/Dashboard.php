<?php
declare(strict_types=1);

final class Dashboard extends Model
{
    public function indicators(): array
    {
        $query = $this->db->query("SELECT COUNT(*) AS references_actives, COALESCE(SUM(quantite * prix_achat), 0) AS valeur_stock, SUM(quantite > 0 AND quantite < " . STOCK_LOW_THRESHOLD . ") AS stocks_faibles, SUM(quantite = 0) AS ruptures FROM produits");
        return $query->fetch() ?: [];
    }

    public function dailyTotals(): array
    {
        $movements = $this->db->query("SELECT COALESCE(SUM(CASE WHEN type_mouvement='ACHAT' THEN montant_total ELSE 0 END), 0) AS achats, COALESCE(SUM(CASE WHEN type_mouvement='VENTE' THEN montant_total ELSE 0 END), 0) AS ventes FROM mouvements WHERE DATE(date_mouvement) = CURDATE()")->fetch();
        $cash = $this->db->query("SELECT COALESCE(SUM(CASE WHEN type_operation='SORTIE' THEN montant ELSE 0 END), 0) AS depenses FROM caisse WHERE DATE(date_operation) = CURDATE()")->fetch();
        return array_merge($movements ?: [], $cash ?: []);
    }
}
