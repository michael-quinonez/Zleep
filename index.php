<?php
define('BASE_URL', '/zleep/');

$url = $_GET['url'] ?? 'dashboard';
$url = rtrim($url, '/');
$urlParams = explode('/', $url);

$controllerName = ucfirst($urlParams[0] ?? 'Dashboard') . 'Controller';
$actionName = $urlParams[1] ?? 'index';

// Transforma 'mi-hotel' o 'mi_hotel' en 'miHotel' de forma automática
$actionName = lcfirst(str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $actionName))));

$controllerFile = __DIR__ . '/controllers/' . $controllerName . '.php';

if (file_exists($controllerFile)) {
    require_once $controllerFile;
    
    if (class_exists($controllerName)) {
        $controller = new $controllerName();
        
        if (method_exists($controller, $actionName)) {
            $controller->$actionName();
        } else {
            echo "<h3>Error de Método</h3>";
            echo "Se encontró la clase <b>{$controllerName}</b>, pero la función <b>{$actionName}()</b> no existe.";
        }
    } else {
        echo "<h3>Error de Clase</h3>";
        echo "El archivo existe, pero la clase <b>{$controllerName}</b> no está declarada correctamente dentro de él.";
    }
} else {
    echo "<h3>404 - Controlador no encontrado</h3>";
    echo "<p><b>El archivo buscado es:</b> " . htmlspecialchars($controllerFile) . "</p>";
}