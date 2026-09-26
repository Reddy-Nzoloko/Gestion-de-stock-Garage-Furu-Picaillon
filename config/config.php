<?php
declare(strict_types=1);

const APP_NAME = 'Garage FURU / HAOJUE';
// Le chemin est automatique: /GestionStockGarage en local, / en production.
$basePath = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
$basePath = $basePath === '/' || $basePath === '.' ? '' : rtrim($basePath, '/');
define('BASE_URL', $basePath);

// Les variables d'environnement permettent d'utiliser les identifiants de l'hebergeur.
$configValue = static function (string $name, string $default = ''): string {
	$value = getenv($name);
	if (($value === false || $value === '') && isset($_ENV[$name])) {
		$value = $_ENV[$name];
	}
	if (($value === false || $value === '') && isset($_SERVER[$name])) {
		$value = $_SERVER[$name];
	}
	return $value === false || $value === '' ? $default : $value;
};

define('DB_HOST', $configValue('GARAGE_DB_HOST', $configValue('DB_HOST', '127.0.0.1')));
define('DB_NAME', $configValue('GARAGE_DB_NAME', $configValue('DB_NAME', 'garage_furu')));
define('DB_USER', $configValue('GARAGE_DB_USER', $configValue('DB_USER', 'root')));
define('DB_PASSWORD', $configValue('GARAGE_DB_PASSWORD', $configValue('DB_PASSWORD')));
const STOCK_LOW_THRESHOLD = 10;
const CURRENCY = '$';
