<?php
declare(strict_types=1);

const APP_NAME = 'Garage FURU / HAOJUE';
// Le chemin est automatique: /GestionStockGarage en local, / en production.
$basePath = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
$basePath = $basePath === '/' || $basePath === '.' ? '' : rtrim($basePath, '/');
define('BASE_URL', $basePath);
const DB_HOST = '127.0.0.1';
const DB_NAME = 'garage_furu';
const DB_USER = 'root';
const DB_PASSWORD = '';
const STOCK_LOW_THRESHOLD = 10;
const CURRENCY = '$';
