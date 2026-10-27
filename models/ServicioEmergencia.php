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

    public function insertar($id_cliente, $tipo_emergencia, $ubicacion, $detalles) {
        $sql = "INSERT INTO emergencias (id_cliente, tipo_emergencia, ubicacion, detalles) VALUES (:id_cliente, :tipo_emergencia, :ubicacion, :detalles)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id_cliente'      => $id_cliente,
            ':tipo_emergencia' => $tipo_emergencia,
            ':ubicacion'       => $ubicacion,
            ':detalles'        => $detalles
        ]);
    }

    public function obtenerTodas() {
        $stmt = $this->db->prepare("SELECT id, tipo_emergencia AS tipo, ubicacion, detalles, 'Activo' AS estado FROM emergencias ORDER BY id DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}