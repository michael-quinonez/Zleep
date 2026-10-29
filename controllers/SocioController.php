<?php
require_once dirname(__DIR__) . '/config/Database.php';

class SocioController {
    
    public function dashboard() {
        require_once dirname(__DIR__) . '/views/socio/dashboard.php';
    }

    public function crear() {
        $this->crearHabitacion();
    }

    public function crearHabitacion() {
        $archivoVista = dirname(__DIR__) . '/views/socio/crear_habitacion.php';
        if (file_exists($archivoVista)) {
            require_once $archivoVista;
        } else {
            echo "<div style='background:#05070a; color:#fff; font-family:sans-serif; padding:3rem; text-align:center;'>
                    <h2 style='color:#10b981; font-family:Syne,sans-serif;'>Crear Habitación</h2>
                    <p style='color:#8b949e; margin-top:1rem;'>Crea el archivo crear_habitacion.php en la carpeta views/socio/</p><br>
                    <a href='" . BASE_URL . "index.php?url=socio/dashboard' style='color:#cca462; font-weight:bold; text-decoration:none;'>← Volver al Dashboard</a>
                  </div>";
        }
    }

    public function habitaciones() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $habitaciones = [];

        try {
            $db = Database::getConnection();

            // Consulta global para traer todas las habitaciones y reservas del sistema sin restricciones
            $sql = "SELECT h.id_habitacion, h.numero, h.categoria, h.estado as estado_habitacion, 
                           r.id_reserva, r.fecha_checkin, r.fecha_checkout, r.detalles AS requerimientos, r.estado_reserva,
                           u.nombre AS nombre_huesp, u.telefono AS telefono_huesp,
                           hot.nombre AS nombre_hotel
                    FROM habitaciones h
                    LEFT JOIN hoteles hot ON h.id_hotel = hot.id_hotel
                    LEFT JOIN reservas r ON h.id_habitacion = r.id_habitacion
                    LEFT JOIN usuarios u ON r.id_cliente = u.id_usuario";

            $stmt = $db->query($sql);
            $habitaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (Exception $e) {
            $habitaciones = [];
        }

        $archivoVista = dirname(__DIR__) . '/views/socio/gestionar_cuartos.php';
        if (file_exists($archivoVista)) {
            require_once $archivoVista;
        } else {
            echo "<div style='background:#05070a; color:#fff; font-family:sans-serif; padding:3rem; text-align:center;'>
                    <h2 style='color:#10b981; font-family:Syne,sans-serif;'>Gestión de Cuartos</h2>
                    <p style='color:#8b949e; margin-top:1rem;'>Crea el archivo gestionar_cuartos.php en la carpeta views/socio/</p><br>
                    <a href='" . BASE_URL . "index.php?url=socio/dashboard' style='color:#cca462; font-weight:bold; text-decoration:none;'>← Volver al Dashboard</a>
                  </div>";
        }
    }

    public function reservas() {
        $archivoVista = dirname(__DIR__) . '/views/socio/reservas.php';
        if (file_exists($archivoVista)) {
            require_once $archivoVista;
        } else {
            echo "<div style='background:#05070a; color:#fff; font-family:sans-serif; padding:3rem; text-align:center;'>
                    <h2 style='color:#10b981; font-family:Syne,sans-serif;'>Gestión de Reservas</h2>
                    <p style='color:#8b949e; margin-top:1rem;'>Panel de reservas para el socio.</p><br>
                    <a href='" . BASE_URL . "index.php?url=socio/dashboard' style='color:#cca462; font-weight:bold; text-decoration:none;'>← Volver al Dashboard</a>
                  </div>";
        }
    }

    public function reservas_hotel() {
        return $this->reservas();
    }

    public function miHotel() {
        $archivoVista = dirname(__DIR__) . '/views/socio/mi_hotel.php';
        if (file_exists($archivoVista)) {
            require_once $archivoVista;
        } else {
            echo "<div style='background:#05070a; color:#fff; font-family:sans-serif; padding:3rem; text-align:center;'>
                    <h2 style='color:#10b981; font-family:Syne,sans-serif;'>Panel de Mi Propiedad</h2>
                    <p style='color:#8b949e; margin-top:1rem;'>El enlace funciona correctamente. Aquí cargará la gestión de tu hotel.</p><br>
                    <a href='" . BASE_URL . "index.php?url=socio/dashboard' style='color:#cca462; font-weight:bold; text-decoration:none;'>← Volver al Dashboard</a>
                  </div>";
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
    }
}