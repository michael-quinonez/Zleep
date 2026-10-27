<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!defined('BASE_URL')) {
    define('BASE_URL', '/zleep/');
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zleep - Reservas</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;700;800&family=Syne:wght@700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>style.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">

    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #05070a;
            color: #fff;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .layout-container {
            display: flex;
            min-height: 100vh;
            width: 100%;
        }

        .main-content {
            flex: 1;
            padding: 2.5rem;
            overflow-x: hidden;
        }

        /* BOTÓN DE NUEVA RESERVA */
        .btn-nueva-reserva {
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            background: linear-gradient(135deg, #cca462 0%, #e5b973 100%);
            color: #090d14;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 700;
            font-size: 0.95rem;
            padding: 0.75rem 1.4rem;
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 14px;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(204, 164, 98, 0.25);
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .btn-nueva-reserva:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(204, 164, 98, 0.4);
            background: linear-gradient(135deg, #e5b973 0%, #f0c987 100%);
        }

        .btn-nueva-reserva:active {
            transform: translateY(0);
        }

        .btn-nueva-reserva .material-icons-round {
            font-size: 1.2rem;
            font-weight: 800;
        }

        /* ESTILOS DEL MODAL */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.75);
            backdrop-filter: blur(8px);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .modal-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .modal-card {
            background: #0d1117;
            border: 1px solid rgba(204, 164, 98, 0.3);
            border-radius: 24px;
            width: 100%;
            max-width: 520px;
            padding: 2rem;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.6);
            transform: translateY(20px);
            transition: all 0.3s ease;
        }

        .modal-overlay.active .modal-card {
            transform: translateY(0);
        }

        .form-group {
            margin-bottom: 1.2rem;
        }

        .form-group label {
            display: block;
            color: #8b949e;
            font-size: 0.85rem;
            margin-bottom: 0.4rem;
        }

        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #fff;
            outline: none;
            box-sizing: border-box;
            font-family: inherit;
        }

        .form-control:focus {
            border-color: #cca462;
        }

        .form-control option {
            background-color: #0d1117;
            color: #ffffff;
        }
    </style>
</head>
<body>

    <div class="layout-container">
        
        <?php include __DIR__ . '/../components/sidebar.php'; ?>

        <main class="main-content">
            <header class="top-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
                <div>
                    <h1 class="welcome-title" style="font-size: 2rem; margin: 0; font-family: 'Syne', sans-serif;">Gestión de Reservas</h1>
                    <p class="subtitle" style="margin-top: 0.4rem; color: #8b949e;">Consulta y administra las habitaciones reservadas.</p>
                </div>
                
                <button onclick="openModal()" class="btn-nueva-reserva">
                    <span class="material-icons-round">add</span>
                    <span>Nueva Reserva</span>
                </button>
            </header>

            <?php if (!empty($reservas)): ?>
                <div class="table-container" style="background: rgba(255, 255, 255, 0.02); border-radius: 20px; padding: 1.5rem; border: 1px solid rgba(255,255,255,0.08);">
                    <table style="width: 100%; border-collapse: collapse; color: white; text-align: left;">
                        <thead>
                            <tr style="border-bottom: 1px solid rgba(255,255,255,0.1); color: #cca462;">
                                <th style="padding: 1rem;"># ID</th>
                                <th style="padding: 1rem;">Cliente ID</th>
                                <th style="padding: 1rem;">Habitación ID</th>
                                <th style="padding: 1rem;">Check-In</th>
                                <th style="padding: 1rem;">Check-Out</th>
                                <th style="padding: 1rem;">Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($reservas as $reserva): ?>
                                <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                                    <td style="padding: 1rem;"><?= htmlspecialchars($reserva['id_reserva']) ?></td>
                                    <td style="padding: 1rem;"><?= htmlspecialchars($reserva['id_cliente']) ?></td>
                                    <td style="padding: 1rem;"><?= htmlspecialchars($reserva['id_habitacion']) ?></td>
                                    <td style="padding: 1rem;"><?= htmlspecialchars($reserva['fecha_checkin']) ?></td>
                                    <td style="padding: 1rem;"><?= htmlspecialchars($reserva['fecha_checkout']) ?></td>
                                    <td style="padding: 1rem;">
                                        <span style="background: rgba(204, 164, 98, 0.15); color: #cca462; padding: 0.3rem 0.8rem; border-radius: 10px; font-size: 0.85rem; border: 1px solid rgba(204, 164, 98, 0.3);">
                                            <?= htmlspecialchars($reserva['estado_reserva']) ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="empty-state" style="background: rgba(255, 255, 255, 0.02); border: 1px dashed rgba(255, 255, 255, 0.15); border-radius: 24px; padding: 4rem 2rem; text-align: center; margin-top: 1rem;">
                    <div style="background: rgba(204, 164, 98, 0.1); width: 70px; height: 70px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem auto;">
                        <span class="material-icons-round" style="font-size: 2.2rem; color: #cca462;">bed</span>
                    </div>
                    <h3 style="color: #fff; font-family: 'Syne', sans-serif; font-size: 1.4rem; margin-bottom: 0.5rem;">Tus reservas estarán aquí</h3>
                    <p style="color: #8b949e; max-width: 400px; margin: 0 auto; font-size: 0.95rem;">Aún no hay ninguna reserva registrada en el sistema.</p>
                </div>
            <?php endif; ?>
        </main>
    </div>

    <div class="modal-overlay" id="modalReserva">
        <div class="modal-card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                <h3 style="margin: 0; font-family: 'Syne', sans-serif; font-size: 1.3rem; color: #fff;">Crear Nueva Reserva</h3>
                <span onclick="closeModal()" class="material-icons-round" style="color: #8b949e; cursor: pointer; font-size: 1.5rem;">close</span>
            </div>

            <form action="<?= BASE_URL ?>index.php?url=reservas/crear" method="POST">
                <!-- CAMPO HOTEL (Visual/Informativo, ya no rompe la inserción) -->
                <div class="form-group">
                    <label>Hotel</label>
                    <select class="form-control" required>
                        <option value="">Selecciona un hotel</option>
                        <option value="1">Grand Hotel Zleep</option>
                        <option value="2">Zleep Boutique & Spa</option>
                        <option value="3">Hotel Zleep Central</option>
                    </select>
                </div>

                <!-- CAMPO HABITACIÓN -->
                <div class="form-group">
                    <label>Habitación / Tipo</label>
                    <select name="id_habitacion" class="form-control" required>
                        <option value="">Selecciona una opción</option>
                        <option value="1">Suite Deluxe 101</option>
                        <option value="2">Habitación Estándar 204</option>
                        <option value="3">Penthouse 501</option>
                    </select>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label>Fecha de Check-In</label>
                        <input type="date" name="fecha_checkin" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Fecha de Check-Out</label>
                        <input type="date" name="fecha_checkout" class="form-control" required>
                    </div>
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 1rem; margin-top: 1.5rem;">
                    <button type="button" onclick="closeModal()" style="background: transparent; border: 1px solid rgba(255,255,255,0.1); color: #fff; padding: 0.75rem 1.2rem; border-radius: 12px; cursor: pointer;">Cancelar</button>
                    <button type="submit" class="btn-nueva-reserva">Confirmar Reserva</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal() {
            document.getElementById('modalReserva').classList.add('active');
        }

        function closeModal() {
            document.getElementById('modalReserva').classList.remove('active');
        }

        function toggleSidebar() {
            const sidebar = document.getElementById('mainSidebar');
            if (sidebar) {
                sidebar.classList.toggle('collapsed');
            }
        }
    </script>
</body>
</html>