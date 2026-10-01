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
    <title>Zleep - Iniciar Sesión</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;700;800&family=Syne:wght@700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>style.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    
    <style>
        /* Corrección para quitar el fondo blanco que ponen los navegadores en inputs autocompletados */
        input:-webkit-autofill,
        input:-webkit-autofill:hover, 
        input:-webkit-autofill:focus, 
        input:-webkit-autofill:active {
            -webkit-box-shadow: 0 0 0 30px #161b22 inset !important; /* Reemplaza #161b22 por el color de fondo exacto de tus inputs si difiere */
            -webkit-text-fill-color: #ffffff !important; /* Color del texto dentro del campo */
            transition: background-color 5000s ease-in-out 0s;
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <div class="logo">
                    <span class="material-icons-round" style="font-size: 2rem; color: #cca462;">auto_awesome</span>
                    <h2>Zleep</h2>
                </div>
                <p>Gestión Operativa & Seguridad Activa</p>
            </div>

            <?php if (!empty($error)): ?>
                <div class="alert-error" style="background: rgba(244, 63, 94, 0.1); border: 1px solid #f43f5e; color: #f43f5e; padding: 0.8rem; border-radius: 12px; margin-bottom: 1rem; font-size: 0.85rem; text-align: center;">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form action="<?= BASE_URL ?>index.php?url=auth/login" method="POST" autocomplete="off">
                <div class="form-group">
                    <label for="email">Correo Electrónico</label>
                    <input type="email" id="email" name="email" placeholder="ejemplo@hotel.com" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" autocomplete="off">
                </div>

                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input type="password" id="password" name="password" placeholder="••••••••" required autocomplete="current-password">
                </div>

                <button type="submit" class="btn-submit">Ingresar al Panel</button>
            </form>

            <div class="auth-footer" style="margin-top: 1.5rem; text-align: center; font-size: 0.85rem; color: #8b949e;">
                <p style="margin-bottom: 0.6rem;">¿No tienes una cuenta? Regístrate como:</p>
                <div style="display: flex; justify-content: center; gap: 1rem; align-items: center;">
                    <a href="<?= BASE_URL ?>index.php?url=auth/register" style="color: #cca462; font-weight: 700; text-decoration: none;">Cliente</a>
                    <span style="color: #4b5563;">•</span>
                <a href="<?= BASE_URL ?>index.php?url=auth/registerHotel" style="color: #10b981; font-weight: 700; text-decoration: none;">Socio (Hotel)</a>            </div>
        </div>
    </div>
</body>
</html>