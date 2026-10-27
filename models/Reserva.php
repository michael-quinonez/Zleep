<?php
require_once __DIR__ . '/../config/Database.php';

class Reserva {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function obtenerTodas() {
        $sql = "SELECT r.*, h.nombre AS nombre_hotel, hab.numero AS numero_habitacion 
                FROM reservas r
                LEFT JOIN habitaciones hab ON r.id_habitacion = hab.id_habitacion
                LEFT JOIN hoteles h ON hab.id_hotel = h.id_hotel
                ORDER BY r.id_reserva ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insertar($id_cliente, $id_habitacion, $fecha_checkin, $fecha_checkout, $total_pago) {
        $this->db->exec("SET FOREIGN_KEY_CHECKS = 0");
        
        $sql = "INSERT INTO reservas (id_cliente, id_habitacion, fecha_checkin, fecha_checkout, estado_reserva, total_pago) VALUES (?, ?, ?, ?, 'Confirmada', ?)";
        $stmt = $this->db->prepare($sql);
        $resultado = $stmt->execute([$id_cliente, $id_habitacion, $fecha_checkin, $fecha_checkout, $total_pago]);
        
        $this->db->exec("SET FOREIGN_KEY_CHECKS = 1");
        
        return $resultado;
    }

    public function cancelar($id_reserva) {
        $sql = "UPDATE reservas SET estado_reserva = 'Cancelada' WHERE id_reserva = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id_reserva]);
    }
}