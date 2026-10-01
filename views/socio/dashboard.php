<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$nombreUsuario = $_SESSION['usuario_nombre'] ?? 'Hotelero';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zleep - Panel Hotelero</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;700;800&family=Syne:wght@700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">

    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            background-color: #05070a;
            color: #fff;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2.5rem 1.5rem;
        }
        .header-panel {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2.5rem;
            flex-wrap: wrap;
            gap: 1rem;
        }
        .header-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
            flex-wrap: wrap;
        }
        .grid-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2.5rem;
        }
        .card-stat {
            background: #0d1117;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 18px;
            padding: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .card-stat .icon-box {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            background: rgba(16, 185, 129, 0.1);
            color: #10b981;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .grid-main {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 1.5rem;
        }
        @media (max-width: 900px) {
            .grid-main { grid-template-columns: 1fr; }
        }
        .panel-box {
            background: #0d1117;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 20px;
            padding: 1.8rem;
        }
        .btn-action {
            background: #10b981;
            color: #000;
            padding: 0.75rem 1.2rem;
            border-radius: 99px;
            font-weight: 700;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            border: none;
            cursor: pointer;
            transition: background 0.2s ease;
        }
        .btn-action:hover {
            background: #059669;
        }
        .btn-home {
            background: rgba(204, 164, 98, 0.1);
            border: 1px solid #cca462;
            color: #cca462;
            padding: 0.75rem 1.2rem;
            border-radius: 99px;
            font-weight: 700;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: background 0.2s ease;
        }
        .btn-home:hover {
            background: rgba(204, 164, 98, 0.2);
        }
        .quick-link {
            color: #fff;
            text-decoration: none;
            padding: 0.8rem;
            background: #05070a;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: background 0.2s ease;
        }
        .quick-link:hover {
            background: #161b22;
        }
    </style>
</head>
<body>

    <div class="container">
        <!-- ENCABEZADO -->
        <div class="header-panel">
            <div>
                <h1 style="font-family: 'Syne', sans-serif; font-size: 2rem; margin: 0; color: #fff;">
                    Gestión de Propiedad
                </h1>
                <p style="color: #8b949e; margin-top: 0.3rem;">Bienvenido de nuevo, <span style="color: #10b981;"><?= htmlspecialchars($nombreUsuario) ?></span></p>
            </div>
            
            <!-- ACCIONES SUPERIORES -->
            <div class="header-actions">
                <!-- Botón para volver a la página principal (puedes ajustar la ruta si tu página principal tiene otra ruta) -->
                <a href="<?= BASE_URL ?>index.php" class="btn-home">
                    <span class="material-icons-round" style="font-size: 1.1rem;">home</span> Página Principal
                </a>
                <!-- Botón principal para agregar habitación -->
                <a href="<?= BASE_URL ?>index.php?url=socio/crear" class="btn-action">
                    <span class="material-icons-round">add</span> Agregar Habitación
                </a>
            </div>
        </div>

        <!-- TARJETAS DE ESTADÍSTICAS -->
        <div class="grid-stats">
            <div class="card-stat">
                <div class="icon-box"><span class="material-icons-round">king_bed</span></div>
                <div>
                    <span style="color: #8b949e; font-size: 0.8rem;">Habitaciones Totales</span>
                    <h2 style="margin: 0; font-family: 'Syne', sans-serif;">12</h2>
                </div>
            </div>
            <div class="card-stat">
                <div class="icon-box" style="background: rgba(204, 164, 98, 0.1); color: #cca462;">
                    <span class="material-icons-round">check_circle</span>
                </div>
                <div>
                    <span style="color: #8b949e; font-size: 0.8rem;">Disponibles</span>
                    <h2 style="margin: 0; font-family: 'Syne', sans-serif;">8</h2>
                </div>
            </div>
            <div class="card-stat">
                <div class="icon-box" style="background: rgba(244, 63, 94, 0.1); color: #f43f5e;">
                    <span class="material-icons-round">bed_double</span>
                </div>
                <div>
                    <span style="color: #8b949e; font-size: 0.8rem;">Ocupadas</span>
                    <h2 style="margin: 0; font-family: 'Syne', sans-serif;">4</h2>
                </div>
            </div>
        </div>

        <!-- SECCIÓN PRINCIPAL -->
        <div class="grid-main">
            <div class="panel-box">
                <h3 style="font-family: 'Syne', sans-serif; color: #10b981; margin-top: 0;">Últimas Reservas Recibidas</h3>
                <p style="color: #8b949e; font-size: 0.9rem;">Aquí puedes ver las solicitudes recientes de tus huéspedes.</p>
                <div style="border-top: 1px solid rgba(255,255,255,0.08); padding-top: 1rem; margin-top: 1rem; color: #8b949e; text-align: center;">
                    No hay reservas pendientes por confirmar hoy.
                </div>
            </div>

            <div class="panel-box">
                <h3 style="font-family: 'Syne', sans-serif; color: #cca462; margin-top: 0;">Accesos Rápidos</h3>
                <div style="display: flex; flex-direction: column; gap: 0.8rem; margin-top: 1rem;">
                    <!-- Enlace conectado al listado de habitaciones -->
                    <a href="<?= BASE_URL ?>index.php?url=socio/habitaciones" class="quick-link">
                        <span>Ver mis habitaciones</span>
                        <span class="material-icons-round" style="font-size: 1.1rem; color: #10b981;">arrow_forward</span>
                    </a>
                    <!-- Enlace conectado a la edición del hotel -->
                    <a href="<?= BASE_URL ?>index.php?url=socio/miHotel" class="quick-link">
                        <span>Mi Propiedad (Editar datos del hotel)</span>
                        <span class="material-icons-round" style="font-size: 1.1rem; color: #cca462;">edit</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

</body>
</html>