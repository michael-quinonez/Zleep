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
    <title>Zleep - Incidencias</title>
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
                <h1 class="welcome-title" style="font-size: 2rem; margin: 0; font-family: 'Syne', sans-serif;">Reporte de Incidencias</h1>
                <p class="subtitle" style="margin-top: 0.4rem; color: #8b949e;">Si tienes un problema con tu habitación o la plataforma, envíanos tu reporte y llegará de inmediato a nuestro equipo de soporte.</p>
            </header>

            <div style="display: grid; grid-template-columns: 1.2fr 0.8fr; gap: 2rem; align-items: start;">
                
                <div style="background: rgba(255, 255, 255, 0.02); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 20px; padding: 2rem;">
                    <h3 style="color: #fff; font-family: 'Syne', sans-serif; font-size: 1.3rem; margin-top: 0; margin-bottom: 1.2rem; display: flex; align-items: center; gap: 0.6rem;">
                        <span class="material-icons-round" style="color: #cca462;">mail</span> Formulario de Reporte Directo
                    </h3>

                    <form action="mailto:soporte@zleep.com" method="POST" enctype="text/plain">
                        <div style="margin-bottom: 1.2rem;">
                            <label style="display: block; color: #8b949e; font-size: 0.85rem; margin-bottom: 0.4rem;">Tu Nombre Completo</label>
                            <input type="text" name="Nombre" placeholder="Ej: Camila Torres" required style="width: 100%; padding: 0.75rem; border-radius: 12px; background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.1); color: #fff; outline: none; box-sizing: border-box;">
                        </div>

                        <div style="margin-bottom: 1.2rem;">
                            <label style="display: block; color: #8b949e; font-size: 0.85rem; margin-bottom: 0.4rem;">Tipo de Inconveniente</label>
                            <select name="Tipo_Incidencia" style="width: 100%; padding: 0.75rem; border-radius: 12px; background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.1); color: #fff; outline: none; box-sizing: border-box;">
                                <option value="Habitacion">Falla en Habitación (Aire, TV, Baño)</option>
                                <option value="Limpieza">Solicitud de Aseo / Limpieza</option>
                                <option value="Plataforma">Falla en la Página / Reserva</option>
                                <option value="Otro">Otro asunto</option>
                            </select>
                        </div>

                        <div style="margin-bottom: 1.2rem;">
                            <label style="display: block; color: #8b949e; font-size: 0.85rem; margin-bottom: 0.4rem;">Número de Habitación / Sede</label>
                            <input type="text" name="Ubicacion" placeholder="Ej: Habitación 204" style="width: 100%; padding: 0.75rem; border-radius: 12px; background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.1); color: #fff; outline: none; box-sizing: border-box;">
                        </div>

                        <div style="margin-bottom: 1.5rem;">
                            <label style="display: block; color: #8b949e; font-size: 0.85rem; margin-bottom: 0.4rem;">Cuéntanos qué sucedió</label>
                            <textarea name="Detalle_Incidencia" rows="4" placeholder="Describe brevemente el inconveniente..." required style="width: 100%; padding: 0.75rem; border-radius: 12px; background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.1); color: #fff; outline: none; resize: none; box-sizing: border-box;"></textarea>
                        </div>

                        <button type="submit" class="btn-submit" style="width: 100%; padding: 0.8rem; display: flex; align-items: center; justify-content: center; gap: 0.5rem; border-radius: 12px; border: none; cursor: pointer; font-weight: 700;">
                            <span class="material-icons-round" style="font-size: 1.1rem;">send</span> Enviar Reporte al Soporte
                        </button>
                    </form>
                </div>

                <div style="background: rgba(255, 255, 255, 0.02); border: 1px dashed rgba(255, 255, 255, 0.15); border-radius: 20px; padding: 2rem; text-align: center;">
                    <div style="background: rgba(204, 164, 98, 0.1); width: 64px; height: 64px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.2rem auto;">
                        <span class="material-icons-round" style="font-size: 2rem; color: #cca462;">alternate_email</span>
                    </div>
                    
                    <h3 style="color: #fff; font-family: 'Syne', sans-serif; font-size: 1.2rem; margin-bottom: 0.5rem;">Correo de Asistencia Directa</h3>
                    <p style="color: #8b949e; font-size: 0.9rem; margin-bottom: 1.5rem; line-height: 1.5;">
                        También puedes escribirnos directamente desde tu correo personal para atención prioritaria:
                    </p>

                    <div style="background: rgba(0,0,0,0.4); border: 1px solid rgba(204, 164, 98, 0.3); padding: 0.8rem; border-radius: 12px; margin-bottom: 1rem;">
                        <a href="mailto:soporte@zleep.com" style="color: #cca462; font-weight: 700; text-decoration: none; font-size: 1rem; display: flex; align-items: center; justify-content: center; gap: 0.4rem;">
                            <span class="material-icons-round" style="font-size: 1.2rem;">mark_email_unread</span>
                            soporte@zleep.com
                        </a>
                    </div>

                    <span style="color: #8b949e; font-size: 0.8rem; display: block;">Respuesta promedio: menos de 15 minutos.</span>
                </div>

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