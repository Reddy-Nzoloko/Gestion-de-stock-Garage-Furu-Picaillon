<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/config.php';

$pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4', DB_USER, DB_PASSWORD, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
$roles = [
    'Administrateur' => 'Accès complet à tous les modules',
    'Vendeur' => 'Tableau de bord et mouvements uniquement',
    'Dépôt' => 'Même accès que le vendeur: tableau de bord et mouvements',
];
$statement = $pdo->prepare('INSERT INTO roles (nom, description) VALUES (?, ?) ON DUPLICATE KEY UPDATE description = VALUES(description)');
foreach ($roles as $name => $description) {
    $statement->execute([$name, $description]);
}
$oldRole = $pdo->query("SELECT id FROM roles WHERE nom = 'Gestionnaire'")->fetchColumn();
$adminRole = $pdo->query("SELECT id FROM roles WHERE nom = 'Administrateur'")->fetchColumn();
if ($oldRole && $adminRole) {
    $pdo->prepare('UPDATE utilisateurs SET role_id = ? WHERE role_id = ?')->execute([$adminRole, $oldRole]);
    $pdo->prepare("DELETE FROM roles WHERE id = ?")->execute([$oldRole]);
}
$roleId = $adminRole;
$statement = $pdo->prepare("INSERT INTO utilisateurs (role_id, nom, prenom, email, mot_de_passe) VALUES (?, 'FURU', 'Administrateur', 'admin@furu.local', ?) ON DUPLICATE KEY UPDATE role_id = VALUES(role_id), actif = 1");
$statement->execute([$roleId, '$2y$12$meIPNoFKEGoeWPA/GbzO4eQTYOjCQ1p0ywlErSE9I3JogduTrc5be']);
$statement = $pdo->prepare('INSERT INTO categories (nom) VALUES (?) ON DUPLICATE KEY UPDATE nom = VALUES(nom)');
foreach (['Moteur', 'Freinage', 'Transmission', 'Électricité', 'Carrosserie'] as $category) {
    $statement->execute([$category]);
}
echo "Données initiales ajoutées.\n";
