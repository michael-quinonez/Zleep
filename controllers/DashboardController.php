<?php
require_once __DIR__ . '/../models/Reserva.php';

class DashboardController {

    public function index() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['usuario_id']) && empty($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . 'index.php?url=auth/login');
            exit();
        }

        $nombreUsuario = $_SESSION['usuario_nombre'] ?? $_SESSION['user_nombre'] ?? 'Usuario';
        $rolUsuario    = isset($_SESSION['usuario_rol']) ? trim(mb_strtolower($_SESSION['usuario_rol'], 'UTF-8')) : '';
        $rolId         = isset($_SESSION['usuario_rol_id']) ? intval($_SESSION['usuario_rol_id']) : 0;
        $usuario_id    = $_SESSION['usuario_id'] ?? $_SESSION['user_id'] ?? 0;
        $id_hotel      = $_SESSION['id_hotel'] ?? null;

        // Lógica de roles para identificar si es socio
        $esSocio = false;
        $emailUsuario = $_SESSION['usuario_email'] ?? '';
        if ($emailUsuario === 'hola2@gmail.com') {
            $esSocio = false;
        } else {
            if (in_array($rolId, [1, 4]) && !in_array($rolUsuario, ['cliente', 'user', 'usuario'])) {
                $esSocio = true;
            } elseif (in_array($rolUsuario, ['socio', 'propietario', 'dueno', 'dueño', 'administrador', 'admin', 'hotel_admin'])) {
                $esSocio = true;
            }
        }

        $habitaciones = [];
        $reservas = [];

        try {
            $pdo = new PDO('mysql:host=localhost;dbname=zleep_db;charset=utf8mb4', 'root', '', [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);

            // Si es socio y no tiene id_hotel en sesión, lo buscamos
            if ($esSocio && !$id_hotel && $usuario_id) {
                $stmtH = $pdo->prepare("SELECT id_hotel FROM hoteles WHERE usuario_id = ?");
                $stmtH->execute([$usuario_id]);
                $hotelData = $stmtH->fetch();
                if ($hotelData) {
                    $id_hotel = $hotelData['id_hotel'];
                    $_SESSION['id_hotel'] = $id_hotel;
                }
            }

            if ($esSocio) {
                // Habitaciones del hotel del socio
                $stmt = $pdo->prepare("
                    SELECT h.id_habitacion, h.numero, h.categoria, h.estado, h.id_hotel 
                    FROM habitaciones h
                    INNER JOIN hoteles ht ON h.id_hotel = ht.id_hotel
                    WHERE ht.usuario_id = :usuario_id
                ");
                $stmt->execute(['usuario_id' => $usuario_id]);
                $habitaciones = $stmt->fetchAll();

                // Reservas del hotel del socio para el panel principal
                if ($id_hotel) {
                    $reservaModel = new Reserva();
                    $reservas = $reservaModel->obtenerReservasPorHotel($id_hotel);
                }
            } else {
                $stmt = $pdo->query("SELECT numero, categoria, estado FROM habitaciones LIMIT 10");
                $habitaciones = $stmt->fetchAll();
            }
        } catch (PDOException $e) {
            $habitaciones = [
                ['numero' => '101', 'categoria' => 'Suite Deluxe', 'estado' => 'DISPONIBLE'],
                ['numero' => '102', 'categoria' => 'Estándar King', 'estado' => 'OCUPADA']
            ];
        }

        $vistaFile = __DIR__ . '/../views/dashboard/index.php';
        if (file_exists($vistaFile)) {
            require_once $vistaFile;
        } else {
            echo "Error crítico: No se encontró la vista del dashboard en " . htmlspecialchars($vistaFile);
        }
    }
}