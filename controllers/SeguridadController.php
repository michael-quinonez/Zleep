<?php

class SeguridadController {
    public function index() {
        // Ejemplo de contactos de ayuda/emergencia según la sede
        $contactos = [
            ['entidad' => 'Recepción Principal Zleep', 'numero' => '+57 (601) 555-0199', 'horario' => '24/7', 'icono' => 'phone_in_talk'],
            ['entidad' => 'Seguridad del Hotel', 'numero' => 'Ext. 104 / +57 300 123 4567', 'horario' => '24/7', 'icono' => 'security'],
            ['entidad' => 'Policía Nacional (Cuadrante)', 'numero' => '123', 'horario' => '24/7', 'icono' => 'local_police'],
            ['entidad' => 'Servicio Médico / Ambulancia', 'numero' => '125', 'horario' => '24/7', 'icono' => 'medical_services']
        ];

        require_once __DIR__ . '/../views/seguridad/index.php';
    }
}