<?php
require_once __DIR__ . '/../models/ServicioEmergencia.php';

class EmergenciasController {

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (empty($_SESSION['usuario_id']) && empty($_SESSION['user_id'])) {
            header("Location: " . BASE_URL . "index.php?url=auth/login");
            exit();
        }
    }

    public function index() {
        $emergenciaModel = new ServicioEmergencia();
        // Cambiamos el arreglo vacío por la llamada al modelo para obtener los registros
        $emergencias = $emergenciaModel->obtenerTodas(); 
        
        require_once __DIR__ . '/../views/emergencias/index.php';
    }

    public function crear() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $tipo_emergencia = $_POST['tipo_emergencia'] ?? '';
            $ubicacion       = $_POST['ubicacion'] ?? '';
            $detalles        = $_POST['detalles'] ?? '';
            $id_cliente      = $_SESSION['usuario_id'] ?? $_SESSION['user_id'] ?? 1;

            $emergenciaModel = new ServicioEmergencia();
            $emergenciaModel->insertar($id_cliente, $tipo_emergencia, $ubicacion, $detalles);

            header("Location: " . BASE_URL . "index.php?url=emergencias");
            exit();
        }
    }
}