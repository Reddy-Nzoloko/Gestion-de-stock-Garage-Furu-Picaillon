<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/config.php';
$pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4', DB_USER, DB_PASSWORD, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
$columns = $pdo->query("SHOW COLUMNS FROM caisse LIKE 'mouvement_id'")->fetch();
if (!$columns) {
    $pdo->exec('ALTER TABLE caisse ADD mouvement_id INT NULL AFTER utilisateur_id, ADD CONSTRAINT fk_caisse_mouvement FOREIGN KEY (mouvement_id) REFERENCES mouvements(id) ON DELETE SET NULL');
    echo "Colonne caisse.mouvement_id ajoutée.\n";
} else {
    echo "Migration déjà appliquée.\n";
}
