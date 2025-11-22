<?php

// DATA BASE
if (!defined('DB_HOST')) {
    define('DB_HOST', '185.98.131.160');
}
if (!defined('DB_NAME')) {
    define('DB_NAME', 'aulon1930571_53tgcy');
}
if (!defined('DB_USER')) {
    define('DB_USER', 'aulon1930571_53tgcy');
}
if (!defined('DB_PASS')) {
    define('DB_PASS', 'ZCrKxhAM3szY2g_');
}

// ENVIRONNEMENT
if (!defined('APP_ENV')) {
    define('APP_ENV', 'production');
}

// Détection automatique du chemin
if (!defined('BASE_URL')) {
    $basePath = dirname($_SERVER['SCRIPT_NAME']);
    define('BASE_URL', rtrim($basePath, '/'));
}