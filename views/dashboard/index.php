<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!defined('BASE_URL')) {
    define('BASE_URL', '/zleep/');
}

// Las variables $nombreUsuario, $rolUsuario, $rolId, $esSocio y $habitaciones 
// ahora son inyectadas directamente desde el DashboardController.
$nombreUsuario = $nombreUsuario ?? $_SESSION['usuario_nombre'] ?? 'Usuario';
$rolUsuario    = $rolUsuario ?? mb_strtolower($_SESSION['usuario_rol'] ?? 'cliente', 'UTF-8');
$rolId         = $rolId ?? $_SESSION['usuario_rol_id'] ?? 3;
$esSocio       = isset($esSocio) ? $esSocio : (in_array($rolId, [1, 4]) || in_array($rolUsuario, ['socio', 'propietario', 'dueno', 'administrador']));
$habitaciones  = $habitaciones ?? [];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zleep - <?= $esSocio ? 'Panel de Socio' : 'Panel Principal' ?></title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;700;800&family=Syne:wght@700;800&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="<?= BASE_URL ?>style.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">

    <style>
        * { box-sizing: border-box; }
        body { margin: 0; padding: 0; background-color: #05070a; color: #fff; font-family: 'Plus Jakarta Sans', sans-serif; }
        .layout-container { display: flex; min-height: 100vh; width: 100%; }
        .sidebar { width: 260px; flex-shrink: 0; background: #090d14; border-right: 1px solid rgba(255, 255, 255, 0.05); padding: 2rem 1.5rem; display: flex; flex-direction: column; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); white-space: nowrap; }
        .sidebar.collapsed { width: 80px; padding: 2rem 0.8rem; }
        .sidebar.collapsed .sidebar-text, .sidebar.collapsed .nav-text { display: none; }
        .sidebar.collapsed .nav-item { justify-content: center; padding: 0.8rem 0; }
        .sidebar.collapsed .user-widget-sidebar { justify-content: center; padding: 0.4rem; }
        .main-content { flex: 1; padding: 2.5rem; overflow-x: hidden; }
        .grid-emergencias { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem; margin-bottom: 2.5rem; }
        .tarjeta-emergencia { background: #0d1117; border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 20px; padding: 2rem 1.5rem; text-align: center; display: flex; flex-direction: column; align-items: center; transition: all 0.3s ease; }
        .btn-tarjeta-alerta { margin-top: auto; width: 100%; padding: 0.85rem; border-radius: 99px; background: rgba(255, 255, 255, 0.04); border: 1px solid rgba(255, 255, 255, 0.1); color: #fff; font-weight: 700; cursor: pointer; transition: all 0.3s ease; }
        .tarjeta-emergencia.medica:hover { border-color: #10b981; box-shadow: 0 0 15px rgba(16, 185, 129, 0.15); }
        .tarjeta-emergencia.medica:hover .btn-tarjeta-alerta { background: #10b981; border-color: #10b981; color: #000; }
        .tarjeta-emergencia.fuego:hover { border-color: #f59e0b; box-shadow: 0 0 15px rgba(245, 158, 11, 0.15); }
        .tarjeta-emergencia.fuego:hover .btn-tarjeta-alerta { background: #f59e0b; border-color: #f59e0b; color: #000; }
        .tarjeta-emergencia.robo:hover { border-color: #f43f5e; box-shadow: 0 0 15px rgba(244, 63, 94, 0.15); }
        .tarjeta-emergencia.robo:hover .btn-tarjeta-alerta { background: #f43f5e; border-color: #f43f5e; color: #fff; }
        .grid-dashboard-bottom { display: grid; grid-template-columns: 1.2fr 0.8fr; gap: 1.5rem; }
        @media (max-width: 1024px) { .grid-dashboard-bottom { grid-template-columns: 1fr; } }
        .panel-box { background: #0d1117; border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 20px; padding: 2rem; }
        .tabla-habitaciones { width: 100%; border-collapse: collapse; margin-top: 1rem; }
        .tabla-habitaciones th { text-align: left; padding: 0.8rem 0.5rem; color: #8b949e; font-size: 0.75rem; letter-spacing: 1px; border-bottom: 1px solid rgba(255, 255, 255, 0.08); }
        .tabla-habitaciones td { padding: 1rem 0.5rem; border-bottom: 1px solid rgba(255, 255, 255, 0.04); font-size: 0.9rem; }
    </style>
</head>
<body>
    <div class="layout-container">
        <!-- BARRA LATERAL -->
        <aside class="sidebar" id="mainSidebar">
            <div class="user-widget-sidebar" onclick="toggleSidebar()" title="Haz clic para alternar menú" style="display: flex; align-items: center; gap: 0.8rem; background: #0d1117; border: 1px solid rgba(255, 255, 255, 0.08); padding: 0.5rem 0.8rem 0.5rem 0.5rem; border-radius: 99px; cursor: pointer; user-select: none; margin-bottom: 2rem; transition: background 0.2s ease;" onmouseover="this.style.background='#161b22';" onmouseout="this.style.background='#0d1117';">
                <div style="width: 36px; height: 36px; border-radius: 50%; background: linear-gradient(135deg, <?= $esSocio ? '#10b981, #047857' : '#cca462, #8b6e38' ?>); color: #000; font-weight: 800; display: flex; align-items: center; justify-content: center; font-size: 0.95rem; text-transform: uppercase; flex-shrink: 0;">
                    <?= htmlspecialchars(substr($nombreUsuario, 0, 1)) ?>
                </div>
                <div class="sidebar-text" style="display: flex; flex-direction: column; overflow: hidden;">
                    <span style="color: #fff; font-weight: 700; font-size: 0.85rem; line-height: 1.1; text-overflow: ellipsis; overflow: hidden;">
                        <?= htmlspecialchars($nombreUsuario) ?>
                    </span>
                    <span style="color: <?= $esSocio ? '#10b981' : '#cca462' ?>; font-size: 0.7rem; display: flex; align-items: center; gap: 0.2rem; margin-top: 2px;">
                        <span style="width: 6px; height: 6px; background-color: <?= $esSocio ? '#10b981' : '#cca462' ?>; border-radius: 50%; display: inline-block;"></span>
                        <?= $esSocio ? 'Socio Propietario' : 'Cliente' ?>
                    </span>
                </div>
                <span class="material-icons-round sidebar-text" id="toggleIcon" style="color: #8b949e; font-size: 1.1rem; margin-left: auto;">menu_open</span>
            </div>

            <nav style="display: flex; flex-direction: column; gap: 0.8rem;">
                <?php if ($esSocio): ?>
                    <a href="<?= BASE_URL ?>index.php?url=dashboard" class="nav-item" style="color: #10b981; display: flex; align-items: center; gap: 0.8rem; text-decoration: none; font-weight: 700;">
                        <span class="material-icons-round">analytics</span>
                        <span class="nav-text">Panel Propietario</span>
                    </a>
                    <a href="<?= BASE_URL ?>index.php?url=socio/mi-hotel" class="nav-item" style="color: #8b949e; display: flex; align-items: center; gap: 0.8rem; text-decoration: none;">
                        <span class="material-icons-round">domain</span>
                        <span class="nav-text">Mi Propiedad</span>
                    </a>
                    <a href="<?= BASE_URL ?>index.php?url=socio/habitaciones" class="nav-item" style="color: #8b949e; display: flex; align-items: center; gap: 0.8rem; text-decoration: none;">
                        <span class="material-icons-round">king_bed</span>
                        <span class="nav-text">Gestionar Cuartos</span>
                    </a>
                    <a href="<?= BASE_URL ?>index.php?url=socio/reservas" class="nav-item" style="color: #8b949e; display: flex; align-items: center; gap: 0.8rem; text-decoration: none;">
                        <span class="material-icons-round">receipt_long</span>
                        <span class="nav-text">Reservas Recibidas</span>
                    </a>
                <?php else: ?>
                    <a href="<?= BASE_URL ?>index.php?url=dashboard" class="nav-item" style="color: #cca462; display: flex; align-items: center; gap: 0.8rem; text-decoration: none; font-weight: 700;">
                        <span class="material-icons-round">space_dashboard</span>
                        <span class="nav-text">Panel Principal</span>
                    </a>
                    <a href="<?= BASE_URL ?>index.php?url=reservas" class="nav-item" style="color: #8b949e; display: flex; align-items: center; gap: 0.8rem; text-decoration: none;">
                        <span class="material-icons-round">bed</span>
                        <span class="nav-text">Reservas</span>
                    </a>
                    <a href="<?= BASE_URL ?>index.php?url=emergencias" class="nav-item" style="color: #8b949e; display: flex; align-items: center; gap: 0.8rem; text-decoration: none;">
                        <span class="material-icons-round">near_me</span>
                        <span class="nav-text">Emergencias</span>
                    </a>
                    <a href="<?= BASE_URL ?>index.php?url=seguridad" class="nav-item" style="color: #8b949e; display: flex; align-items: center; gap: 0.8rem; text-decoration: none;">
                        <span class="material-icons-round">gpp_good</span>
                        <span class="nav-text">Seguridad</span>
                    </a>
                    <a href="<?= BASE_URL ?>index.php?url=incidencias" class="nav-item" style="color: #8b949e; display: flex; align-items: center; gap: 0.8rem; text-decoration: none;">
                        <span class="material-icons-round">history_edu</span>
                        <span class="nav-text">Incidencias</span>
                    </a>
                <?php endif; ?>

                <a href="<?= BASE_URL ?>index.php?url=auth/login" class="nav-item" style="margin-top: 2rem; color: #f43f5e; display: flex; align-items: center; gap: 0.8rem; text-decoration: none;">
                    <span class="material-icons-round">logout</span>
                    <span class="nav-text">Cerrar Sesión</span>
                </a>
            </nav>
        </aside>

        <!-- CONTENIDO PRINCIPAL -->
        <main class="main-content">
            <div style="display: flex; justify-content: space-between; align-items: center; gap: 1.5rem; margin-bottom: 2rem; flex-wrap: wrap;">
                <div class="main-header-logo" style="display: flex; align-items: center; gap: 0.6rem;">
                    <span class="material-icons-round" style="color: #cca462; font-size: 1.8rem;">auto_awesome</span>
                    <span style="font-family: 'Syne', sans-serif; font-weight: 800; font-size: 1.6rem; color: #ffffff; letter-spacing: -0.5px;">Zleep</span>
                </div>

                <?php if ($esSocio): ?>
                    <a href="<?= BASE_URL ?>index.php?url=socio/habitaciones/crear" style="background: #10b981; color: #000; padding: 0.65rem 1.2rem; border-radius: 99px; font-weight: 700; text-decoration: none; font-size: 0.88rem; display: flex; align-items: center; gap: 0.4rem;">
                        <span class="material-icons-round">add</span> Publicar Habitación
                    </a>
                <?php else: ?>
                    <form action="<?= BASE_URL ?>index.php" method="GET" style="flex: 1; max-width: 420px; min-width: 260px; margin: 0;">
                        <input type="hidden" name="url" value="reservas/buscar">
                        <div style="position: relative; display: flex; align-items: center;">
                            <span class="material-icons-round" style="position: absolute; left: 1rem; color: #8b949e; font-size: 1.2rem; pointer-events: none;">search</span>
                            <input type="text" name="query" placeholder="Buscar hotel para reservar..." required style="width: 100%; background: #0d1117; border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 99px; padding: 0.65rem 1rem 0.65rem 2.8rem; color: #fff; font-size: 0.88rem; outline: none;">
                        </div>
                    </form>
                <?php endif; ?>
            </div>

            <div style="margin-bottom: 2.5rem;">
                <h1 style="font-family: 'Syne', sans-serif; font-size: 1.8rem; margin: 0 0 0.3rem 0; color: #fff;">
                    Bienvenido/a, <span style="color: <?= $esSocio ? '#10b981' : '#cca462' ?>;"><?= htmlspecialchars($nombreUsuario) ?></span> 👋
                </h1>
                <p style="color: #8b949e; margin: 0; font-size: 0.9rem;">
                    <?= $esSocio ? 'Administración de tu propiedad y reservas recibidas.' : 'Gestión y monitoreo en tiempo real del establecimiento.' ?>
                </p>
            </div>

            <div class="grid-emergencias">
                <div class="tarjeta-emergencia medica">
                    <span class="material-icons-round" style="font-size: 2.2rem; color: #10b981; margin-bottom: 0.8rem;">medical_services</span>
                    <h3 style="color: #fff; font-family: 'Syne', sans-serif; margin: 0 0 0.4rem 0;">Asistencia Médica</h3>
                    <p style="color: #8b949e; font-size: 0.85rem; margin: 0 0 1.5rem 0;">Protocolo de acción rápida</p>
                    <button type="button" class="btn-tarjeta-alerta" onclick="alert('⚠️ ALERTA ACTIVADA: Asistencia Médica')">Activar Alerta</button>
                </div>
                <div class="tarjeta-emergencia fuego">
                    <span class="material-icons-round" style="font-size: 2.2rem; color: #f59e0b; margin-bottom: 0.8rem;">local_fire_department</span>
                    <h3 style="color: #fff; font-family: 'Syne', sans-serif; margin: 0 0 0.4rem 0;">Incendio u Evacuación</h3>
                    <p style="color: #8b949e; font-size: 0.85rem; margin: 0 0 1.5rem 0;">Contactos directos bomberos</p>
                    <button type="button" class="btn-tarjeta-alerta" onclick="alert('⚠️ ALERTA ACTIVADA: Incendio u Evacuación')">Activar Alerta</button>
                </div>
                <div class="tarjeta-emergencia robo">
                    <span class="material-icons-round" style="font-size: 2.2rem; color: #f43f5e; margin-bottom: 0.8rem;">security</span>
                    <h3 style="color: #fff; font-family: 'Syne', sans-serif; margin: 0 0 0.4rem 0;">Intrusión / Robo</h3>
                    <p style="color: #8b949e; font-size: 0.85rem; margin: 0 0 1.5rem 0;">Aviso silencioso perimetral</p>
                    <button type="button" class="btn-tarjeta-alerta" onclick="alert('⚠️ ALERTA ACTIVADA: Intrusión / Robo')">Activar Alerta</button>
                </div>
            </div>

            <div class="grid-dashboard-bottom">
                <div class="panel-box">
                    <h3 style="color: #cca462; font-family: 'Syne', sans-serif; margin: 0 0 1rem 0;">
                        <?= $esSocio ? 'Habitaciones de mi Propiedad' : 'Disponibilidad de Habitaciones' ?>
                    </h3>
                    <table class="tabla-habitaciones">
                        <thead>
                            <tr>
                                <th>HABITACIÓN</th>
                                <th>CATEGORÍA</th>
                                <th style="text-align: right;">ESTADO</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($habitaciones)): ?>
                                <?php foreach ($habitaciones as $hab): ?>
                                    <tr>
                                        <td style="font-weight: 700;"><?= htmlspecialchars($hab['numero']) ?></td>
                                        <td style="color: #cbd5e1;"><?= htmlspecialchars($hab['categoria']) ?></td>
                                        <td style="text-align: right; font-weight: 700; color: <?= mb_strtoupper($hab['estado']) === 'OCUPADA' ? '#f43f5e' : '#cca462' ?>;">
                                            <?= htmlspecialchars(mb_strtoupper($hab['estado'])) ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="3" style="text-align: center; color: #8b949e; padding: 1.5rem;">No hay habitaciones registradas.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="panel-box" style="display: flex; flex-direction: column;">
                    <h3 style="color: #cca462; font-family: 'Syne', sans-serif; margin: 0 0 0.5rem 0;">Geolocalización de Apoyo</h3>
                    <p style="color: #8b949e; font-size: 0.85rem; margin: 0 0 1.5rem 0;">Servicios vinculados a sus coordenadas:</p>
                    <div onclick="abrirGeolocalizacion()" style="background: #05070a; border: 1px solid rgba(255, 255, 255, 0.05); border-radius: 16px; padding: 1.5rem; text-align: center; flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: center; cursor: pointer;">
                        <span class="material-icons-round" style="font-size: 2.2rem; color: #cca462; margin-bottom: 0.5rem;">place</span>
                        <p id="geoTexto" style="color: #8b949e; font-size: 0.85rem; margin: 0;">Fuerzas de Seguridad a 180m • Centro Médico Clínico a 1.1km</p>
                        <span style="margin-top: 1rem; color: #cca462; font-size: 0.75rem; font-weight: 700; text-transform: uppercase;">Rastrear ubicación</span>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('mainSidebar');
            const toggleIcon = document.getElementById('toggleIcon');
            if (sidebar) {
                sidebar.classList.toggle('collapsed');
                if (toggleIcon) {
                    toggleIcon.textContent = sidebar.classList.contains('collapsed') ? 'menu' : 'menu_open';
                }
            }
        }
        function abrirGeolocalizacion() {
            const texto = document.getElementById('geoTexto');
            texto.textContent = "Obteniendo coordenadas...";
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function(pos) {
                        texto.textContent = `Ubicación detectada (${pos.coords.latitude.toFixed(4)}, ${pos.coords.longitude.toFixed(4)})`;
                        window.open(`https://www.google.com/maps?q=${pos.coords.latitude},${pos.coords.longitude}`, '_blank');
                    },
                    function() {
                        texto.textContent = "Ubicación desactivada. Abriendo mapa general...";
                        window.open('https://www.google.com/maps', '_blank');
                    }
                );
            } else {
                texto.textContent = "Geolocalización no soportada en este navegador.";
                window.open('https://www.google.com/maps', '_blank');
            }
        }
    </script>
</body>
</html>