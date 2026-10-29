<?php
require_once __DIR__ . '/../models/Reserva.php';
require_once __DIR__ . '/../config/Database.php';

class ReservasController {

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
        $reservaModel = new Reserva();
        $usuario_id = $_SESSION['usuario_id'] ?? $_SESSION['user_id'] ?? 0;
        
        $id_hotel = $_SESSION['id_hotel'] ?? null;
        $rolUsuario = isset($_SESSION['usuario_rol']) ? trim(mb_strtolower($_SESSION['usuario_rol'], 'UTF-8')) : '';
        $rolId = isset($_SESSION['usuario_rol_id']) ? intval($_SESSION['usuario_rol_id']) : 0;

        $esSocio = false;
        if (in_array($rolId, [1, 4]) && !in_array($rolUsuario, ['cliente', 'user', 'usuario'])) {
            $esSocio = true;
        } elseif (in_array($rolUsuario, ['socio', 'propietario', 'dueno', 'dueño', 'administrador', 'admin', 'hotel_admin'])) {
            $esSocio = true;
        }

        $hoteles = [];
        $habitaciones = [];
        $reservas = [];

        try {
            $conexion = Database::getConnection();

            if ($esSocio && !$id_hotel && $usuario_id) {
                $stmtH = $conexion->prepare("SELECT id_hotel FROM hoteles WHERE usuario_id = ?");
                $stmtH->execute([$usuario_id]);
                $hotelData = $stmtH->fetch(PDO::FETCH_ASSOC);
                if ($hotelData) {
                    $id_hotel = $hotelData['id_hotel'];
                    $_SESSION['id_hotel'] = $id_hotel;
                }
            }

            if ($esSocio && $id_hotel) {
                $reservas = $reservaModel->obtenerReservasPorHotel($id_hotel);
            } else {
                $reservas = $reservaModel->obtenerTodas();
            }

            // Obtenemos todos los hoteles para alimentar los selects de la vista
            $stmtH = $conexion->query("SELECT * FROM hoteles ORDER BY nombre ASC");
            $hoteles = $stmtH->fetchAll(PDO::FETCH_ASSOC);

            $stmtHab = $conexion->query("SELECT * FROM habitaciones");
            $habitaciones = $stmtHab->fetchAll(PDO::FETCH_ASSOC);

        } catch (Exception $e) {
            $reservas = $reservaModel->obtenerTodas();
        }

        require_once __DIR__ . '/../views/reservas/index.php';
    }

    public function crear() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_cliente     = $_SESSION['usuario_id'] ?? $_SESSION['user_id'] ?? 1;
            $id_habitacion  = $_POST['id_habitacion'] ?? 1;
            
            $fecha_checkin  = $_POST['fecha_checkin'] ?? null;
            $fecha_checkout = $_POST['fecha_checkout'] ?? null;
            $total_pago     = 150.00;

            $reservaModel = new Reserva();
            
            try {
                $reservaModel->insertar($id_cliente, $id_habitacion, $fecha_checkin, $fecha_checkout, $total_pago);
            } catch (Exception $e) {
                $reservaModel->insertar(1, $id_habitacion, $fecha_checkin, $fecha_checkout, $total_pago);
            }

            header("Location: " . BASE_URL . "index.php?url=reservas");
            exit();
        }
    }

    public function cancelar() {
        if (isset($_GET['id'])) {
            $id_reserva = $_GET['id'];
            $reservaModel = new Reserva();
            $reservaModel->cancelar($id_reserva);
        }
        header("Location: " . BASE_URL . "index.php?url=reservas");
        exit();
    }
}