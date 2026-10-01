<?php

class IncidenciasController {
    public function index() {
        $incidencias = []; 
        require_once __DIR__ . '/../views/incidencias/index.php';
    }
}