<?php
require_once __DIR__ . '/../models/Reserva.php';

class ReservasController {

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Proteger ruta: verificamos tanto 'usuario_id' como 'user_id' para evitar expulsiones erróneas
        if (empty($_SESSION['usuario_id']) && empty($_SESSION['user_id'])) {
            header("Location: " . BASE_URL . "index.php?url=auth/login");
            exit();
        }
    }

    public function index() {
        $reservaModel = new Reserva();
        
        // Opcional: si tu modelo permite filtrar por el ID del usuario actual, puedes usarlo aquí:
        // $usuarioId = $_SESSION['usuario_id'] ?? $_SESSION['user_id'];
        // $reservas = $reservaModel->obtenerPorUsuario($usuarioId);

        $reservas = $reservaModel->obtenerTodas();

        require_once __DIR__ . '/../views/reservas/index.php';
    }
}