<?php
require_once __DIR__ . '/../models/Incidencia.php';

class IncidenciaController {

    public function index() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            header("Location: " . BASE_URL . "index.php?url=auth/login");
            exit();
        }

        $hotelId = $_SESSION['hotel_id'] ?? 1;
        $incidenciaModel = new Incidencia();
        $incidencias = $incidenciaModel->obtenerActivasPorHotel($hotelId);

        require_once __DIR__ . '/../views/dashboard/index.php';
    }

    public function registrar() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
            $tipo = $_POST['tipo'] ?? 'Otra';
            $descripcion = $_POST['descripcion'] ?? '';
            $hotelId = $_SESSION['hotel_id'] ?? 1;
            $usuarioId = $_SESSION['user_id'];

            $incidenciaModel = new Incidencia();
            $incidenciaModel->crear($hotelId, $usuarioId, $tipo, $descripcion);
        }

        header("Location: " . BASE_URL . "index.php?url=dashboard");
        exit();
    }
}