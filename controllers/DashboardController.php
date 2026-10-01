<?php
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

        // CAMBIO TEMPORAL: Si quieres forzar que esta cuenta específica (por ID o email) actúe como cliente
        // mientras corriges tu base de datos o el AuthController:
        $emailUsuario = $_SESSION['usuario_email'] ?? '';
        if ($emailUsuario === 'hola2@gmail.com') { // O el correo de tu cuenta de cliente
            $esSocio = false;
        } else {
            // Lógica normal de roles
            $esSocio = false;
            if (in_array($rolId, [1, 4]) && !in_array($rolUsuario, ['cliente', 'user', 'usuario'])) {
                $esSocio = true;
            } elseif (in_array($rolUsuario, ['socio', 'propietario', 'dueno', 'dueño', 'administrador', 'admin', 'hotel_admin'])) {
                $esSocio = true;
            }
        }

        $habitaciones = [];
        try {
            $pdo = new PDO('mysql:host=localhost;dbname=zleep_db;charset=utf8mb4', 'root', '', [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);

            if ($esSocio) {
                $stmt = $pdo->prepare("
                    SELECT h.numero, h.categoria, h.estado 
                    FROM habitaciones h
                    INNER JOIN hoteles ht ON h.hotel_id = ht.id
                    WHERE ht.usuario_id = :usuario_id
                ");
                $stmt->execute(['usuario_id' => $_SESSION['usuario_id'] ?? $_SESSION['user_id'] ?? 0]);
                $habitaciones = $stmt->fetchAll();
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