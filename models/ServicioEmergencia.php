<?php
require_once __DIR__ . '/../config/Database.php';

class ServicioEmergencia {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function obtenerPorHotel($hotelId) {
        $stmt = $this->db->prepare("SELECT * FROM servicios_emergencia WHERE hotel_id = :hotel_id ORDER BY distancia_metros ASC");
        $stmt->execute([':hotel_id' => $hotelId]);
        return $stmt->fetchAll();
    }
}