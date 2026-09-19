<?php
declare(strict_types=1);

session_start();

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/core/Database.php';
require_once __DIR__ . '/core/Controller.php';
require_once __DIR__ . '/core/Model.php';
require_once __DIR__ . '/models/Product.php';
require_once __DIR__ . '/models/Movement.php';
require_once __DIR__ . '/models/Cash.php';
require_once __DIR__ . '/models/Dashboard.php';
require_once __DIR__ . '/models/Auth.php';
require_once __DIR__ . '/models/User.php';
require_once __DIR__ . '/models/Category.php';
require_once __DIR__ . '/models/Backup.php';
require_once __DIR__ . '/controllers/DashboardController.php';
require_once __DIR__ . '/controllers/ProductController.php';
require_once __DIR__ . '/controllers/MovementController.php';
require_once __DIR__ . '/controllers/CashController.php';
require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . '/controllers/UserController.php';
require_once __DIR__ . '/controllers/CategoryController.php';
require_once __DIR__ . '/controllers/ReportController.php';
require_once __DIR__ . '/controllers/BackupController.php';

$page = $_GET['page'] ?? 'home';
$routes = [
    'home' => [AuthController::class, 'home'],
    'login' => [AuthController::class, 'login'],
    'authenticate' => [AuthController::class, 'authenticate'],
    'logout' => [AuthController::class, 'logout'],
    'password' => [AuthController::class, 'password'],
    'password-save' => [AuthController::class, 'passwordSave'],
    'backup' => [BackupController::class, 'download'],
    'utilisateurs' => [UserController::class, 'index'],
    'utilisateur-save' => [UserController::class, 'save'],
    'utilisateur-delete' => [UserController::class, 'delete'],
    'utilisateur-delete-page' => [UserController::class, 'deletions'],
    'categories' => [CategoryController::class, 'index'],
    'categorie-save' => [CategoryController::class, 'save'],
    'dashboard' => [DashboardController::class, 'index'],
    'produits' => [ProductController::class, 'index'],
    'produit-form' => [ProductController::class, 'form'],
    'produit-save' => [ProductController::class, 'save'],
    'produit-delete' => [ProductController::class, 'delete'],
    'mouvements' => [MovementController::class, 'index'],
    'mouvement-save' => [MovementController::class, 'save'],
    'facture' => [ReportController::class, 'invoice'],
    'rapport' => [ReportController::class, 'daily'],
    'caisse' => [CashController::class, 'index'],
    'caisse-save' => [CashController::class, 'save'],
];

if (!in_array($page, ['home', 'login', 'authenticate'], true) && empty($_SESSION['user'])) {
    $page = 'login';
}

$publicPages = ['home', 'login', 'authenticate'];
$adminPages = ['utilisateurs', 'utilisateur-save', 'utilisateur-delete', 'utilisateur-delete-page', 'categories', 'categorie-save', 'produits', 'produit-form', 'produit-save', 'produit-delete', 'caisse', 'caisse-save', 'rapport', 'backup'];
$operatorPages = ['dashboard', 'mouvements', 'mouvement-save', 'facture', 'password', 'password-save'];
if (!in_array($page, $publicPages, true) && !empty($_SESSION['user'])) {
    $role = $_SESSION['user']['role'] ?? '';
    if (in_array($page, $adminPages, true) && $role !== 'Administrateur') {
        http_response_code(403);
        $page = 'dashboard';
    } elseif (in_array($page, $operatorPages, true) && !in_array($role, ['Administrateur', 'Vendeur', 'Dépôt'], true)) {
        http_response_code(403);
        $page = 'dashboard';
    }
}

if (!isset($routes[$page])) {
    http_response_code(404);
    $page = 'dashboard';
}

[$controllerClass, $method] = $routes[$page];
$controller = new $controllerClass();
$controller->{$method}();
