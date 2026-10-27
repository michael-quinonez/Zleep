<?php
require_once __DIR__ . '/../models/Reserva.php';

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
        $reservas = $reservaModel->obtenerTodas();

        // Obtener hoteles y habitaciones directamente por PDO para evitar archivos de modelo faltantes
        $hoteles = [];
        $habitaciones = [];
        
        try {
            $conexion = new PDO('mysql:host=localhost;dbname=zleep_db;charset=utf8', 'root', '');
            $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $stmtH = $conexion->query("SELECT * FROM hoteles");
            $hoteles = $stmtH->fetchAll(PDO::FETCH_ASSOC);

            $stmtHab = $conexion->query("SELECT * FROM habitaciones");
            $habitaciones = $stmtHab->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Manejo silencioso si ocurre algún detalle de conexión
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