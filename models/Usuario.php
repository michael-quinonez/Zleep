<?php
require_once __DIR__ . '/../config/Database.php';

class Usuario {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    // Método agregado para exponer la conexión PDO al AuthController u otros controladores
    public function getConexion() {
        return $this->db;
    }

    public function buscarPorEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE email = :email LIMIT 1");
        $stmt->execute([':email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function registrar($nombre, $email, $password) {
        // Si tu tabla requiere hotel_id o rol_id por defecto:
        $sql = "INSERT INTO usuarios (nombre, email, password, rol_id, hotel_id) 
                VALUES (:nombre, :email, :password, 1, NULL)";
            
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':nombre'   => $nombre,
            ':email'    => $email,
            ':password' => $password
        ]);
    }
}