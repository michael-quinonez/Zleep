<?php
// views/components/sidebar.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$nombreUsuario = $_SESSION['usuario_nombre'] ?? $_SESSION['nombre'] ?? 'Stephanie';
$seccionActual = $_GET['url'] ?? 'dashboard';
?>

<aside class="sidebar" id="mainSidebar">
    
    <!-- WIDGET DE USUARIO CON EVENTO PARA COLAPSAR -->
    <div 
        class="user-widget-sidebar"
        onclick="toggleSidebar()"
        title="Haz clic para ocultar/mostrar menú"
        style="display: flex; align-items: center; gap: 0.8rem; background: #0d1117; border: 1px solid rgba(255, 255, 255, 0.08); padding: 0.5rem 0.8rem 0.5rem 0.5rem; border-radius: 99px; cursor: pointer; user-select: none; margin-bottom: 2rem; transition: background 0.2s ease;"
        onmouseover="this.style.background='#161b22';"
        onmouseout="this.style.background='#0d1117';"
    >
        <div style="width: 36px; height: 36px; border-radius: 50%; background: linear-gradient(135deg, #cca462, #8b6e38); color: #000; font-weight: 800; display: flex; align-items: center; justify-content: center; font-size: 0.95rem; text-transform: uppercase; flex-shrink: 0;">
            <?= htmlspecialchars(substr($nombreUsuario, 0, 1)) ?>
        </div>
        <div class="sidebar-text" style="display: flex; flex-direction: column; overflow: hidden;">
            <span style="color: #fff; font-weight: 700; font-size: 0.85rem; line-height: 1.1; text-overflow: ellipsis; overflow: hidden;">
                <?= htmlspecialchars($nombreUsuario) ?>
            </span>
            <span style="color: #10b981; font-size: 0.7rem; display: flex; align-items: center; gap: 0.2rem; margin-top: 2px;">
                <span style="width: 6px; height: 6px; background-color: #10b981; border-radius: 50%; display: inline-block;"></span>
                En línea
            </span>
        </div>
        <span class="material-icons-round sidebar-text" style="color: #8b949e; font-size: 1.1rem; margin-left: auto;">menu_open</span>
    </div>

    <!-- NAVEGACIÓN DENTRO DE LA BARRA LATERAL -->
    <nav style="display: flex; flex-direction: column; gap: 0.8rem;">
        <a href="<?= BASE_URL ?>index.php?url=dashboard" class="nav-item" style="color: <?= ($seccionActual === 'dashboard') ? '#cca462' : '#8b949e' ?>; display: flex; align-items: center; gap: 0.8rem; text-decoration: none; font-weight: <?= ($seccionActual === 'dashboard') ? '700' : 'normal' ?>;">
            <span class="material-icons-round">space_dashboard</span>
            <span class="nav-text">Panel Principal</span>
        </a>
        <a href="<?= BASE_URL ?>index.php?url=reservas" class="nav-item" style="color: <?= (strpos($seccionActual, 'reservas') !== false) ? '#cca462' : '#8b949e' ?>; display: flex; align-items: center; gap: 0.8rem; text-decoration: none; font-weight: <?= (strpos($seccionActual, 'reservas') !== false) ? '700' : 'normal' ?>;">
            <span class="material-icons-round">bed</span>
            <span class="nav-text">Reservas</span>
        </a>
        <a href="<?= BASE_URL ?>index.php?url=emergencias" class="nav-item" style="color: <?= (strpos($seccionActual, 'emergencias') !== false) ? '#cca462' : '#8b949e' ?>; display: flex; align-items: center; gap: 0.8rem; text-decoration: none; font-weight: <?= (strpos($seccionActual, 'emergencias') !== false) ? '700' : 'normal' ?>;">
            <span class="material-icons-round">near_me</span>
            <span class="nav-text">Emergencias</span>
        </a>
        <a href="<?= BASE_URL ?>index.php?url=seguridad" class="nav-item" style="color: <?= (strpos($seccionActual, 'seguridad') !== false) ? '#cca462' : '#8b949e' ?>; display: flex; align-items: center; gap: 0.8rem; text-decoration: none; font-weight: <?= (strpos($seccionActual, 'seguridad') !== false) ? '700' : 'normal' ?>;">
            <span class="material-icons-round">gpp_good</span>
            <span class="nav-text">Seguridad</span>
        </a>
        <a href="<?= BASE_URL ?>index.php?url=incidencias" class="nav-item" style="color: <?= (strpos($seccionActual, 'incidencias') !== false) ? '#cca462' : '#8b949e' ?>; display: flex; align-items: center; gap: 0.8rem; text-decoration: none; font-weight: <?= (strpos($seccionActual, 'incidencias') !== false) ? '700' : 'normal' ?>;">
            <span class="material-icons-round">history_edu</span>
            <span class="nav-text">Incidencias</span>
        </a>
        <a href="<?= BASE_URL ?>index.php?url=auth/logout" class="nav-item" style="margin-top: 2rem; color: #f43f5e; display: flex; align-items: center; gap: 0.8rem; text-decoration: none;">
            <span class="material-icons-round">logout</span>
            <span class="nav-text">Cerrar Sesión</span>
        </a>
    </nav>
</aside>