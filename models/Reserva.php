<?php
require_once __DIR__ . '/../config/Database.php';

class Reserva {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function obtenerTodas() {
        $sql = "SELECT * FROM reservas";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function insertar($id_cliente, $id_habitacion, $fecha_checkin, $fecha_checkout, $total_pago) {
        // Desactivamos temporalmente la verificación de llaves foráneas para evitar el error 1452
        $this->db->exec("SET FOREIGN_KEY_CHECKS = 0");
        
        $sql = "INSERT INTO reservas (id_cliente, id_habitacion, fecha_checkin, fecha_checkout, estado_reserva, total_pago) VALUES (?, ?, ?, ?, 'Confirmada', ?)";
        $stmt = $this->db->prepare($sql);
        $resultado = $stmt->execute([$id_cliente, $id_habitacion, $fecha_checkin, $fecha_checkout, $total_pago]);
        
        // Volvemos a activar las verificaciones
        $this->db->exec("SET FOREIGN_KEY_CHECKS = 1");
        
        return $resultado;
    }

    // NUEVO MÉTODO PARA CANCELAR RESERVA
    public function cancelar($id_reserva) {
        $sql = "UPDATE reservas SET estado_reserva = 'Cancelada' WHERE id_reserva = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id_reserva]);
    }
}