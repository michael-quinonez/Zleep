<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!defined('BASE_URL')) {
    define('BASE_URL', '/zleep/');
}

$nombreHotel = $_SESSION['hotel_nombre'] ?? 'Mi Hotel';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zleep - Gestión de Reservas</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;700;800&family=Syne:wght@700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>style.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    
    <style>
        body {
            margin: 0;
            background-color: #05070a;
            color: #fff;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .full-page-container {
            width: 100%;
            max-width: 1400px;
            margin: 0 auto;
            padding: 2.5rem 2rem;
        }
        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            padding-bottom: 1.5rem;
            flex-wrap: wrap;
            gap: 1rem;
        }
        .card-box {
            background: #0d1117;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 20px;
            padding: 2.2rem;
            box-shadow: 0 8px 24px rgba(0,0,0,0.4);
        }
        .btn-home {
            background: rgba(204, 164, 98, 0.1);
            border: 1px solid #cca462;
            color: #cca462;
            padding: 0.7rem 1.2rem;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 700;
            font-size: 0.85rem;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            transition: background 0.2s ease;
        }
        .btn-home:hover {
            background: rgba(204, 164, 98, 0.2);
        }
        .table-container {
            width: 100%;
            overflow-x: auto;
            margin-top: 1.5rem;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            font-size: 0.95rem;
        }
        th, td {
            padding: 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }
        th {
            color: #8b949e;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 700;
        }
        td {
            color: #fff;
        }
        .badge-status {
            background: rgba(16, 185, 129, 0.1);
            border: 1px solid #10b981;
            color: #10b981;
            padding: 0.3rem 0.8rem;
            border-radius: 99px;
            font-size: 0.8rem;
            font-weight: 700;
            display: inline-block;
        }
    </style>
</head>
<body>

    <div class="full-page-container">
        
        <!-- ENCABEZADO SUPERIOR -->
        <div class="top-bar">
            <div>
                <h1 style="font-family: 'Syne', sans-serif; font-size: 2.2rem; margin: 0 0 0.3rem 0; color: #fff;">
                    Gestión de Reservas
                </h1>
                <p style="color: #8b949e; font-size: 0.95rem; margin: 0;">
                    Monitorea las solicitudes de estancia para <span style="color: #10b981; font-weight: 700;"><?= htmlspecialchars($nombreHotel) ?></span>
                </p>
            </div>
            <div style="display: flex; gap: 1rem; align-items: center;">
                <a href="<?= BASE_URL ?>index.php?url=socio/dashboard" style="color: #8b949e; text-decoration: none; font-weight: 700; font-size: 0.9rem;">← Volver al Panel</a>
                <a href="<?= BASE_URL ?>index.php" class="btn-home">
                    <span class="material-icons-round" style="font-size: 1.1rem;">home</span> Página Principal
                </a>
            </div>
        </div>

        <!-- TABLA DE RESERVAS -->
        <div class="card-box">
            <h3 style="font-family: 'Syne', sans-serif; color: #10b981; margin-top: 0; margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.5rem; font-size: 1.25rem;">
                <span class="material-icons-round">book_online</span> Historial de Solicitudes y Reservas
            </h3>
            <p style="color: #8b949e; font-size: 0.9rem; margin-bottom: 1.5rem;">Listado completo de huéspedes que han reservado habitaciones en tu establecimiento.</p>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID Reserva</th>
                            <th>Huésped</th>
                            <th>Habitación</th>
                            <th>Fechas</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="color: #cca462; font-weight: 700;">#ZLP-4021</td>
                            <td>Carlos Mendoza</td>
                            <td>Suite 202</td>
                            <td>12 Oct 2026 - 15 Oct 2026</td>
                            <td><span class="badge-status">Confirmada</span></td>
                            <td>
                                <button style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: #fff; padding: 0.4rem 0.8rem; border-radius: 8px; cursor: pointer; font-size: 0.85rem;">Detalles</button>
                            </td>
                        </tr>
                        <tr>
                            <td style="color: #cca462; font-weight: 700;">#ZLP-4025</td>
                            <td>Lucía Fernández</td>
                            <td>Habitación Doble 104</td>
                            <td>18 Oct 2026 - 20 Oct 2026</td>
                            <td><span class="badge-status">Confirmada</span></td>
                            <td>
                                <button style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: #fff; padding: 0.4rem 0.8rem; border-radius: 8px; cursor: pointer; font-size: 0.85rem;">Detalles</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</body>
</html>