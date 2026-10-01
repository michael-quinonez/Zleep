<?php
require_once __DIR__ . '/../config/Database.php';

class Incidencia {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function obtenerActivasPorHotel($hotelId) {
        $stmt = $this->db->prepare("SELECT * FROM incidencias WHERE hotel_id = :hotel_id AND estado = 'Activa' ORDER BY fecha_hora DESC");
        $stmt->execute([':hotel_id' => $hotelId]);
        return $stmt->fetchAll();
    }
}