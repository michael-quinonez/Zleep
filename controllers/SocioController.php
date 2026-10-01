<?php
class SocioController {
    
    // Método para mostrar el panel principal (dashboard)
    public function dashboard() {
        require_once dirname(__DIR__) . '/views/socio/dashboard.php';
    }

    public function crear() {
        $this->crearHabitacion();
    }

    public function crearHabitacion() {
        require_once dirname(__DIR__) . '/views/socio/crear_habitacion.php';
    }

    public function habitaciones() {
        $this->crearHabitacion();
    }

    // Método para gestionar las reservas del hotel
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

    // Método principal para la propiedad del hotel
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

    // Alias y método mágico para capturar cualquier variante (mi-hotel, mi_hotel)
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
    }
}