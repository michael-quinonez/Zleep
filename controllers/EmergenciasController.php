<?php

class EmergenciasController {
    public function index() {
        // Inicialmente pasamos un arreglo vacío
        $emergencias = []; 
        require_once __DIR__ . '/../views/emergencias/index.php';
    }
}