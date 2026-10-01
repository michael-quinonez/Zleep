<?php
require_once __DIR__ . '/../config/Database.php';

class Reserva {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function obtenerTodas() {
        $sql = "SELECT r.*, h.numero AS habitacion_numero, c.nombre AS cliente_nombre 
                FROM reservas r 
                LEFT JOIN habitaciones h ON r.habitacion_id = h.id 
                LEFT JOIN clientes c ON r.cliente_id = c.id 
                ORDER BY r.id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}