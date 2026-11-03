<?php
require_once dirname(__DIR__) . '/config/Database.php';

class SocioController {
    
    public function dashboard() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $totalHabitaciones = 0;
        $totalDisponibles = 0;
        $totalOcupadas = 0;

        try {
            $db = Database::getConnection();
            $id_hotel = $this->obtenerIdHotelActual($db);

            // Contar habitaciones totales del hotel actual
            $stmtTotal = $db->prepare("SELECT COUNT(*) FROM habitaciones WHERE id_hotel = ?");
            $stmtTotal->execute([$id_hotel]);
            $totalHabitaciones = $stmtTotal->fetchColumn();

            // Contar habitaciones disponibles del hotel actual
            $stmtDisp = $db->prepare("SELECT COUNT(*) FROM habitaciones WHERE id_hotel = ? AND estado = 'Disponible'");
            $stmtDisp->execute([$id_hotel]);
            $totalDisponibles = $stmtDisp->fetchColumn();

            // Contar habitaciones ocupadas del hotel actual
            $stmtOcup = $db->prepare("SELECT COUNT(*) FROM habitaciones WHERE id_hotel = ? AND estado = 'Ocupada'");
            $stmtOcup->execute([$id_hotel]);
            $totalOcupadas = $stmtOcup->fetchColumn();

        } catch (Exception $e) {
            // Excepción controlada
        }

        require_once dirname(__DIR__) . '/views/socio/dashboard.php';
    }

    private function obtenerIdHotelActual($db) {
        if (isset($_SESSION['id_hotel']) && !empty($_SESSION['id_hotel'])) {
            return $_SESSION['id_hotel'];
        }

        $id_usuario = $_SESSION['id_usuario'] ?? $_SESSION['usuario_id'] ?? null;
        if ($id_usuario) {
            try {
                $stmt = $db->prepare("SELECT id_hotel FROM hoteles WHERE id_usuario = ? LIMIT 1");
                $stmt->execute([$id_usuario]);
                $hotel = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($hotel && !empty($hotel['id_hotel'])) {
                    $_SESSION['id_hotel'] = $hotel['id_hotel'];
                    return $hotel['id_hotel'];
                }
            } catch (Exception $e) {}
        }

        // Fallback por defecto si no se encuentra sesión activa
        return 2;
    }

    public function crear() {
        $this->crearHabitacion();
    }

    public function crearHabitacion() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['id_habitacion'])) {
            $numero = $_POST['numero'] ?? $_POST['nombre'] ?? '';
            $tipo   = $_POST['tipo'] ?? $_POST['categoria'] ?? 'Estándar';
            $precio = $_POST['precio_noche'] ?? $_POST['precio'] ?? 0;
            $estado = $_POST['estado'] ?? 'Disponible';
            
            try {
                $db = Database::getConnection();
                $id_hotel = $this->obtenerIdHotelActual($db);
            } catch (Exception $e) {
                $id_hotel = 2;
            }

            if (!empty($numero)) {
                $pathModel = dirname(__DIR__) . '/models/Habitacion.php';
                if (!file_exists($pathModel)) {
                    $pathModel = dirname(__DIR__) . '/models/habitacion.php';
                }
                require_once $pathModel;

                $habitacionModel = new Habitacion();
                $habitacionModel->crear($id_hotel, $numero, $tipo, $precio, $estado);
                
                header('Location: ' . BASE_URL . 'index.php?url=socio/habitaciones');
                exit;
            }
        }

        $archivoVista = dirname(__DIR__) . '/views/socio/crear_habitacion.php';
        if (file_exists($archivoVista)) {
            require_once $archivoVista;
        } else {
            echo "No se encontró la vista crear_habitacion.php";
        }
    }

    public function editarHabitacion() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_habitacion = $_POST['id_habitacion'] ?? null;
            $numero = $_POST['numero'] ?? '';
            $tipo   = $_POST['tipo'] ?? '';
            $estado = $_POST['estado'] ?? 'Disponible';

            if ($id_habitacion) {
                try {
                    $db = Database::getConnection();
                    $stmt = $db->prepare("UPDATE habitaciones SET numero = ?, tipo = ?, estado = ? WHERE id_habitacion = ?");
                    $stmt->execute([$numero, $tipo, $estado, $id_habitacion]);
                } catch (Exception $e) {
                    // Excepción controlada
                }
            }

            header('Location: ' . BASE_URL . 'index.php?url=socio/habitaciones');
            exit;
        }
    }

    public function habitaciones() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $habitaciones = [];

        try {
            $db = Database::getConnection();
            $id_hotel = $this->obtenerIdHotelActual($db);

            $stmt = $db->prepare("SELECT id_habitacion, id_hotel, numero, tipo, precio_noche, COALESCE(estado, 'Disponible') AS estado_habitacion FROM habitaciones WHERE id_hotel = ? ORDER BY id_habitacion ASC");
            $stmt->execute([$id_hotel]);
            $habitaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (Exception $e) {
            $habitaciones = [];
        }

        $archivoVista = dirname(__DIR__) . '/views/socio/gestionar_cuartos.php';
        if (file_exists($archivoVista)) {
            require_once $archivoVista;
        } else {
            echo "No se encontró la vista gestionar_cuartos.php";
        }
    }

    public function reservas() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $reservas = [];

        try {
            $db = Database::getConnection();
            $stmt = $db->query("SELECT * FROM reservas ORDER BY id_reserva DESC");
            $reservas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            $reservas = [];
        }

        $archivoVista = dirname(__DIR__) . '/views/socio/reservas.php';
        if (file_exists($archivoVista)) {
            require_once $archivoVista;
        } else {
            echo "No se encontró la vista reservas.php";
        }
    }

    public function reservas_hotel() {
        return $this->reservas();
    }

    public function miHotel() {
        $archivoVista = dirname(__DIR__) . '/views/socio/mi_hotel.php';
        if (file_exists($archivoVista)) {
            require_once $archivoVista;
        }
    }

    public function mi_hotel() {
        return $this->miHotel();
    }

    public function __call($name, $arguments) {
        $metodoLimpio = lcfirst(str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $name))));
        if (method_exists($this, $metodoLimpio)) {
            return $this->$metodoLimpio();
        }
        if ($name === 'mi-hotel' || $name === 'mi_hotel') {
            return $this->miHotel();
        }
        if ($name === 'reservas' || $name === 'reservas-hotel') {
            return $this->reservas();
        }
        if ($name === 'habitaciones' || $name === 'gestionar-cuartos') {
            return $this->habitaciones();
        }
        if ($name === 'crear' || $name === 'crear-habitacion') {
            return $this->crearHabitacion();
        }
        if ($name === 'editar-habitacion' || $name === 'editar_habitacion') {
            return $this->editarHabitacion();
        }
    }
}