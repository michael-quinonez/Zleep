<?php
require_once __DIR__ . '/../config/Database.php';

class Habitacion {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function obtenerPorHotel($hotelId) {
        $stmt = $this->db->prepare("SELECT * FROM habitaciones WHERE id_hotel = :id_hotel");
        $stmt->execute([':id_hotel' => $hotelId]);
        return $stmt->fetchAll();
    }

    public function crear($id_hotel, $numero, $tipo, $precio_noche, $estado) {
        $stmt = $this->db->prepare("INSERT INTO habitaciones (id_hotel, numero, tipo, precio_noche, estado) VALUES (:id_hotel, :numero, :tipo, :precio_noche, :estado)");
        return $stmt->execute([
            ':id_hotel' => $id_hotel,
            ':numero' => $numero,
            ':tipo' => $tipo,
            ':precio_noche' => $precio_noche,
            ':estado' => $estado
        ]);
    }
}