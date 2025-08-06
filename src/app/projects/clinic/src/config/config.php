<?php
// src/config/config.php

/**
 * Configuración básica del sistema IPSFANB Dental
 */

// Mostrar errores solo en desarrollo
error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('log_errors', '1');
ini_set('error_log', __DIR__ . '/../../logs/php_errors.log');

// Configuración de zona horaria
date_default_timezone_set('America/Caracas');

// Rutas base del proyecto
define('ROOT_PATH', realpath(dirname(__DIR__) . '/..') . '/');
define('APP_PATH', ROOT_PATH . 'src/');
define('PUBLIC_PATH', ROOT_PATH . 'public/');
define('VIEWS_PATH', PUBLIC_PATH . 'views/');
define('COMPONENTS_PATH', PUBLIC_PATH . 'components/');
define('ASSETS_PATH', PUBLIC_PATH . 'assets/');

// Configuración de la base de datos
define('DB_CONFIG', [
    'host' => 'localhost',
    'port' => '3306',
    'dbname' => 'ipsfanb_dental',
    'charset' => 'utf8mb4',
    'username' => 'ipsfanb_user',
    'password' => 'TuContraseñaSegura123!',
    'options' => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]
]);

// Configuración de la aplicación
define('APP_CONFIG', [
    'app_name' => 'IPSFANB Dental',
    'app_version' => '1.0.0',
    'environment' => 'development', // 'production' o 'development'
    'maintenance' => false,
    'debug' => true,
    'session' => [
        'name' => 'ipsfanb_session',
        'timeout' => 3600, // 1 hora en segundos
        'secure' => true,
        'httponly' => true,
        'samesite' => 'Strict'
    ],
    'auth' => [
        'max_attempts' => 5,
        'lockout_time' => 300, // 5 minutos en segundos
        'password_reset_expiry' => 3600 // 1 hora en segundos
    ]
]);

// Configuración de correo electrónico
define('MAIL_CONFIG', [
    'host' => 'smtp.gmail.com',
    'port' => 587,
    'username' => 'notificaciones@ipsfanb.com',
    'password' => 'TuContraseñaDeEmail',
    'from' => 'notificaciones@ipsfanb.com',
    'from_name' => 'Sistema IPSFANB Dental',
    'smtp_secure' => 'tls',
    'smtp_auth' => true
]);

// Autoload de clases
spl_autoload_register(function ($className) {
    $file = APP_PATH . str_replace('\\', '/', $className) . '.php';
    if (file_exists($file)) {
        require $file;
    }
});

// Iniciar sesión segura
function startSecureSession() {
    $sessionConfig = APP_CONFIG['session'];
    
    session_name($sessionConfig['name']);
    
    session_set_cookie_params([
        'lifetime' => $sessionConfig['timeout'],
        'path' => '/',
        'domain' => $_SERVER['HTTP_HOST'],
        'secure' => $sessionConfig['secure'],
        'httponly' => $sessionConfig['httponly'],
        'samesite' => $sessionConfig['samesite']
    ]);
    
    session_start();
    
    // Regenerar ID de sesión periódicamente para prevenir fixation
    if (!isset($_SESSION['last_regeneration'])) {
        session_regenerate_id(true);
        $_SESSION['last_regeneration'] = time();
    } elseif (time() - $_SESSION['last_regeneration'] > 1800) { // 30 minutos
        session_regenerate_id(true);
        $_SESSION['last_regeneration'] = time();
    }
}

// Iniciar la sesión si no está en modo mantenimiento
if (!APP_CONFIG['maintenance']) {
    startSecureSession();
}

// Conexión a la base de datos
function getDBConnection() {
    static $db = null;
    
    if ($db === null) {
        try {
            $dsn = sprintf(
                'mysql:host=%s;port=%s;dbname=%s;charset=%s',
                DB_CONFIG['host'],
                DB_CONFIG['port'],
                DB_CONFIG['dbname'],
                DB_CONFIG['charset']
            );
            
            $db = new PDO(
                $dsn,
                DB_CONFIG['username'],
                DB_CONFIG['password'],
                DB_CONFIG['options']
            );
        } catch (PDOException $e) {
            error_log('Database connection error: ' . $e->getMessage());
            if (APP_CONFIG['debug']) {
                die('Error de conexión a la base de datos: ' . $e->getMessage());
            } else {
                die('Error de conexión a la base de datos. Por favor intente más tarde.');
            }
        }
    }
    
    return $db;
}

// Función para cargar variables de entorno (si usas .env)
function loadEnv($path = null) {
    if ($path === null) {
        $path = ROOT_PATH . '.env';
    }
    
    if (!file_exists($path)) {
        return false;
    }
    
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue;
        }
        
        list($name, $value) = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value);
        
        if (!array_key_exists($name, $_SERVER) && !array_key_exists($name, $_ENV)) {
            putenv(sprintf('%s=%s', $name, $value));
            $_ENV[$name] = $value;
            $_SERVER[$name] = $value;
        }
    }
    
    return true;
}

// Cargar variables de entorno si el archivo existe
if (file_exists(ROOT_PATH . '.env')) {
    loadEnv();
}

// Función para generar URLs absolutas
function url($path = '') {
    static $baseUrl = null;
    
    if ($baseUrl === null) {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'];
        $baseUrl = $protocol . '://' . $host;
    }
    
    return $baseUrl . '/' . ltrim($path, '/');
}

// Función para incluir componentes
function component($name, $data = []) {
    $filePath = COMPONENTS_PATH . $name . '.php';
    
    if (file_exists($filePath)) {
        extract($data);
        require $filePath;
    } else {
        error_log("Component not found: {$name}");
        if (APP_CONFIG['debug']) {
            echo "<div class='bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded'>Componente no encontrado: {$name}</div>";
        }
    }
}