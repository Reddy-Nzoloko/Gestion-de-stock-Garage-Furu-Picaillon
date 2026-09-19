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
require_once __DIR__ . '/controllers/DashboardController.php';
require_once __DIR__ . '/controllers/ProductController.php';
require_once __DIR__ . '/controllers/MovementController.php';
require_once __DIR__ . '/controllers/CashController.php';
require_once __DIR__ . '/controllers/AuthController.php';

$page = $_GET['page'] ?? 'dashboard';
$routes = [
    'login' => [AuthController::class, 'login'],
    'authenticate' => [AuthController::class, 'authenticate'],
    'logout' => [AuthController::class, 'logout'],
    'dashboard' => [DashboardController::class, 'index'],
    'produits' => [ProductController::class, 'index'],
    'produit-form' => [ProductController::class, 'form'],
    'produit-save' => [ProductController::class, 'save'],
    'produit-delete' => [ProductController::class, 'delete'],
    'mouvements' => [MovementController::class, 'index'],
    'mouvement-save' => [MovementController::class, 'save'],
    'caisse' => [CashController::class, 'index'],
    'caisse-save' => [CashController::class, 'save'],
];

if (!in_array($page, ['login', 'authenticate'], true) && empty($_SESSION['user'])) {
    $page = 'login';
}

if (!isset($routes[$page])) {
    http_response_code(404);
    $page = 'dashboard';
}

[$controllerClass, $method] = $routes[$page];
$controller = new $controllerClass();
$controller->{$method}();
