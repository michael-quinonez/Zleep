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
    <title>Zleep - Emergencias</title>
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

        .sidebar {
            width: 260px;
            flex-shrink: 0;
            background: #090d14;
            border-right: 1px solid rgba(255, 255, 255, 0.05);
            padding: 2rem 1.5rem;
            display: flex;
            flex-direction: column;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-sizing: border-box;
            white-space: nowrap;
            position: sticky;
            top: 0;
            height: 100vh;
        }

        .sidebar.collapsed {
            width: 80px;
            padding: 2rem 0.8rem;
        }

        .sidebar.collapsed .sidebar-text,
        .sidebar.collapsed .nav-text {
            display: none;
        }

        .sidebar.collapsed .nav-item {
            justify-content: center;
            padding: 0.8rem 0;
        }

        .sidebar.collapsed .user-widget-sidebar {
            justify-content: center;
            padding: 0.4rem;
        }

        .main-content {
            flex: 1;
            padding: 2.5rem;
            overflow-x: hidden;
        }

        /* BOTÓN DE EMERGENCIA REDISEÑADO */
        .btn-emergencia {
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            background: linear-gradient(135deg, #ff4d6d 0%, #ff2a4b 100%);
            color: #ffffff;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 700;
            font-size: 0.95rem;
            padding: 0.75rem 1.4rem;
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 14px;
            cursor: pointer;
            box-shadow: 0 4px 18px rgba(255, 42, 75, 0.35);
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .btn-emergencia:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 22px rgba(255, 42, 75, 0.5);
            background: linear-gradient(135deg, #ff607d 0%, #ff3b59 100%);
        }

        .btn-emergencia:active {
            transform: translateY(0);
        }

        .btn-emergencia .material-icons-round {
            font-size: 1.2rem;
            font-weight: 800;
        }

        /* ESTILOS DEL MODAL DE EMERGENCIA */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
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
            border: 1px solid rgba(255, 77, 109, 0.3);
            border-radius: 24px;
            width: 100%;
            max-width: 520px;
            padding: 2rem;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.8);
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
            border-color: #ff4d6d;
        }
    </style>
</head>
<body>

    <div class="layout-container">
        
        <?php include __DIR__ . '/../components/sidebar.php'; ?>

        <main class="main-content">
            <header class="top-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
                <div>
                    <h1 class="welcome-title" style="font-size: 2rem; margin: 0; font-family: 'Syne', sans-serif;">Gestión de Emergencias</h1>
                    <p class="subtitle" style="margin-top: 0.4rem; color: #8b949e;">Registro y alerta de eventos críticos en las instalaciones.</p>
                </div>
                
                <button onclick="openModal()" class="btn-emergencia">
                    <span class="material-icons-round">add</span>
                    <span>Registrar Emergencia</span>
                </button>
            </header>

            <?php if (!empty($emergencias)): ?>
                <div class="table-container" style="background: rgba(255, 255, 255, 0.02); border-radius: 20px; padding: 1.5rem; border: 1px solid rgba(255,255,255,0.08);">
                    <table style="width: 100%; border-collapse: collapse; color: white; text-align: left;">
                        <thead>
                            <tr style="border-bottom: 1px solid rgba(255,255,255,0.1); color: #ff4d6d;">
                                <th style="padding: 1rem;"># ID</th>
                                <th style="padding: 1rem;">Tipo</th>
                                <th style="padding: 1rem;">Ubicación</th>
                                <th style="padding: 1rem;">Estado</th>
                                <th style="padding: 1rem; text-align: center;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($emergencias as $emergencia): ?>
                                <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                                    <td style="padding: 1rem;"><?= htmlspecialchars($emergencia['id']) ?></td>
                                    <td style="padding: 1rem; font-weight: 700; color: #ff4d6d;">
                                        <?= htmlspecialchars(!empty($emergencia['tipo_emergencia']) ? $emergencia['tipo_emergencia'] : ($emergencia['tipo'] ?? 'Asistencia Médica')) ?>
                                    </td>
                                    <td style="padding: 1rem;"><?= htmlspecialchars($emergencia['ubicacion']) ?></td>
                                    <td style="padding: 1rem;">
                                        <span style="background: rgba(244, 63, 94, 0.15); color: #f43f5e; padding: 0.3rem 0.8rem; border-radius: 10px; font-size: 0.85rem; border: 1px solid rgba(244, 63, 94, 0.3);">
                                            Activo
                                        </span>
                                    </td>
                                    <td style="padding: 1rem; text-align: center;">
                                        <a href="<?= BASE_URL ?>index.php?url=emergencias/eliminar&id=<?= $emergencia['id'] ?>" 
                                           style="display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; background: rgba(255, 75, 75, 0.15); color: #ff4b4b; border-radius: 10px; text-decoration: none; font-weight: bold; border: 1px solid rgba(255, 75, 75, 0.3); transition: all 0.2s;"
                                           title="Eliminar emergencia"
                                           onclick="return confirm('¿Estás seguro de eliminar este registro de emergencia?');"
                                           onmouseover="this.style.background='rgba(255, 75, 75, 0.3)'"
                                           onmouseout="this.style.background='rgba(255, 75, 75, 0.15)'">
                                            ✕
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="empty-state" style="background: rgba(255, 255, 255, 0.02); border: 1px dashed rgba(255, 255, 255, 0.15); border-radius: 24px; padding: 4rem 2rem; text-align: center; margin-top: 1rem;">
                    <div style="background: rgba(244, 63, 94, 0.1); width: 70px; height: 70px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem auto;">
                        <span class="material-icons-round" style="font-size: 2.2rem; color: #f43f5e;">near_me</span>
                    </div>
                    <h3 style="color: #fff; font-family: 'Syne', sans-serif; font-size: 1.4rem; margin-bottom: 0.5rem;">Tus emergencias estarán aquí</h3>
                    <p style="color: #8b949e; max-width: 400px; margin: 0 auto; font-size: 0.95rem;">No hay ningún reporte de emergencia activo en este momento.</p>
                </div>
            <?php endif; ?>
        </main>
    </div>

    <div class="modal-overlay" id="modalEmergencia">
        <div class="modal-card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                <div style="display: flex; align-items: center; gap: 0.6rem;">
                    <span class="material-icons-round" style="color: #ff4d6d;">warning</span>
                    <h3 style="margin: 0; font-family: 'Syne', sans-serif; font-size: 1.3rem; color: #fff;">Registrar Emergencia</h3>
                </div>
                <span onclick="closeModal()" class="material-icons-round" style="color: #8b949e; cursor: pointer; font-size: 1.5rem;">close</span>
            </div>

            <form action="<?= BASE_URL ?>index.php?url=emergencias/crear" method="POST">
                <div class="form-group">
                    <label>Tipo de Emergencia</label>
                    <select name="tipo_emergencia" class="form-control" required>
                        <option value="">Selecciona el evento</option>
                        <option value="Asistencia Médica">Asistencia Médica</option>
                        <option value="Incendio / Evacuación">Incendio / Evacuación</option>
                        <option value="Fallo Eléctrico Crítico">Fallo Eléctrico Crítico</option>
                        <option value="Fuga de Agua / Gas">Fuga de Agua / Gas</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Ubicación</label>
                    <input type="text" name="ubicacion" class="form-control" placeholder="Ej: Habitación 102 / Área de Cocina" required>
                </div>

                <div class="form-group">
                    <label>Detalles / Observaciones</label>
                    <textarea name="detalles" class="form-control" rows="3" placeholder="Describe brevemente lo sucedido..."></textarea>
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 1rem; margin-top: 1.5rem;">
                    <button type="button" onclick="closeModal()" style="background: transparent; border: 1px solid rgba(255,255,255,0.1); color: #fff; padding: 0.75rem 1.2rem; border-radius: 12px; cursor: pointer;">Cancelar</button>
                    <button type="submit" class="btn-emergencia">Emitir Alerta</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal() {
            document.getElementById('modalEmergencia').classList.add('active');
        }

        function closeModal() {
            document.getElementById('modalEmergencia').classList.remove('active');
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