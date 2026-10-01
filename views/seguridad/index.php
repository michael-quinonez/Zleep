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
    <title>Zleep - Seguridad</title>
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
    </style>
</head>
<body>

    <div class="layout-container">
        
        <?php include __DIR__ . '/../components/sidebar.php'; ?>

        <main class="main-content">
            <header class="top-header" style="margin-bottom: 2rem;">
                <h1 class="welcome-title" style="font-size: 2rem; margin: 0; font-family: 'Syne', sans-serif;">Directorio de Seguridad</h1>
                <p class="subtitle" style="margin-top: 0.4rem; color: #8b949e;">Números de contacto y asistencia directa según tu ubicación y hospedaje.</p>
            </header>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem;">
                <?php if (!empty($contactos)): ?>
                    <?php foreach ($contactos as $contacto): ?>
                        <div style="background: rgba(255, 255, 255, 0.02); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 20px; padding: 1.5rem; display: flex; align-items: center; gap: 1.2rem;">
                            <div style="background: rgba(204, 164, 98, 0.1); width: 56px; height: 56px; border-radius: 16px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <span class="material-icons-round" style="font-size: 1.8rem; color: #cca462;"><?= htmlspecialchars($contacto['icono']) ?></span>
                            </div>
                            <div>
                                <h4 style="color: #fff; margin: 0 0 0.3rem 0; font-family: 'Syne', sans-serif; font-size: 1.1rem;"><?= htmlspecialchars($contacto['entidad']) ?></h4>
                                <p style="color: #cca462; margin: 0; font-weight: 700; font-size: 1.05rem;"><?= htmlspecialchars($contacto['numero']) ?></p>
                                <span style="color: #8b949e; font-size: 0.8rem;">Atención: <?= htmlspecialchars($contacto['horario']) ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p style="color: #8b949e;">No hay contactos de seguridad disponibles actualmente.</p>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('mainSidebar');
            if (sidebar) {
                sidebar.classList.toggle('collapsed');
            }
        }
    </script>
</body>
</html>