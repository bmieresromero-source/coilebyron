<?php
// config.php - configura la conexión a la base de datos
// Ajusta estos valores a tu servidor MySQL
define('DB_HOST', 'localhost');
define('DB_NAME', 'coile_gestion');
define('DB_USER', 'root');
define('DB_PASS', 'password');

// Conexión PDO
function db() {
    static $pdo = null;
    if ($pdo === null) {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    }
    return $pdo;
}
?>