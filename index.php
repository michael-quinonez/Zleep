<?php
define('BASE_URL', '/zleep/'); // Ajusta a /ZLEEP/ si tu carpeta está en mayúsculas

$url = $_GET['url'] ?? 'dashboard';
$url = rtrim($url, '/');
$urlParams = explode('/', $url);

$controllerName = ucfirst($urlParams[0] ?? 'Dashboard') . 'Controller';
$actionName = $urlParams[1] ?? 'index';

// 🛠️ LÍNEA CLAVE: Transforma 'mi-hotel' o 'mi_hotel' en 'miHotel' de forma automática
$actionName = lcfirst(str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $actionName))));

$controllerFile = __DIR__ . '/controllers/' . $controllerName . '.php';

// DEPURACIÓN PARA ENCONTRAR EL ERROR EXACTO
if (file_exists($controllerFile)) {
    require_once $controllerFile;
    $controller = new $controllerName();
    
    if (method_exists($controller, $actionName)) {
        $controller->$actionName();
    } else {
        echo "<h3>Error de Método</h3>";
        echo "Se encontró el archivo, pero la función <b>{$actionName}()</b> no existe dentro de la clase <b>{$controllerName}</b>.";
    }
} else {
    echo "<h3>404 - Controlador no encontrado</h3>";
    echo "<p><b>El archivo buscado es:</b> " . htmlspecialchars($controllerFile) . "</p>";
    echo "<p><b>¿Existe ese archivo exacto en esa carpeta?:</b> " . (file_exists($controllerFile) ? 'SÍ' : 'NO') . "</p>";
    echo "<p><b>Directorio actual del proyecto (__DIR__):</b> " . htmlspecialchars(__DIR__) . "</p>";
}