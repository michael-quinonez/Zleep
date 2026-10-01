<?php
require_once __DIR__ . '/../config/Database.php';

class Habitacion {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function obtenerPorHotel($hotelId) {
        $stmt = $this->db->prepare("SELECT * FROM habitaciones WHERE hotel_id = :hotel_id");
        $stmt->execute([':hotel_id' => $hotelId]);
        return $stmt->fetchAll();
    }
}