<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!defined('BASE_URL')) {
    define('BASE_URL', '/zleep/');
}

$exito = isset($_GET['success']) && $_GET['success'] == '1';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zleep - Registrar Cuenta</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;700;800&family=Syne:wght@700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/zleep/style.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
</head>
<body>

    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <div class="logo">
                    <span class="material-icons-round" style="font-size: 2rem; color: #cca462;">auto_awesome</span>
                    <h2>Zleep</h2>
                </div>
                <p>Crea tu cuenta para ingresar a la plataforma</p>
            </div>

            <?php if ($exito): ?>
                <div class="alert-success" style="background: rgba(34, 197, 94, 0.15); border: 1px solid #22c55e; color: #4ade80; padding: 0.8rem 1rem; border-radius: 12px; font-size: 0.85rem; margin-bottom: 1.2rem; text-align: center;">
                    ¡Registro exitoso! Redirigiendo al inicio de sesión...
                </div>
                <script>
                    setTimeout(function() {
                        window.location.href = "<?= BASE_URL ?>index.php?url=auth/login";
                    }, 2000);
                </script>
            <?php endif; ?>

            <?php if (!empty($error)): ?>
                <div class="alert-error" style="background: rgba(244, 63, 94, 0.1); border: 1px solid #f43f5e; color: #f43f5e; padding: 0.8rem; border-radius: 12px; margin-bottom: 1rem; font-size: 0.85rem; text-align: center;">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form action="<?= BASE_URL ?>index.php?url=auth/register" method="POST" autocomplete="off">
                <div class="form-group">
                    <label for="nombre">Nombre Completo</label>
                    <input type="text" id="nombre" name="nombre" placeholder="Tu nombre y apellido" required value="<?= htmlspecialchars($_POST['nombre'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label for="email">Correo Electrónico</label>
                    <input type="email" id="email" name="email" placeholder="correo@ejemplo.com" required autocomplete="off" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input type="password" id="password" name="password" placeholder="••••••••" required autocomplete="new-password">
                </div>

                <button type="submit" class="btn-submit" <?= $exito ? 'disabled' : '' ?>>Registrar Cuenta</button>
            </form>

            <div class="auth-footer" style="margin-top: 1.5rem; text-align: center; font-size: 0.85rem; color: #8b949e;">
                <p>¿Tienes un establecimiento? <a href="<?= BASE_URL ?>index.php?url=auth/registerHotel" style="color: #10b981; font-weight: 700;">Regístrate como Hotelero</a></p>
                <p style="margin-top: 0.5rem;">¿Ya tienes una cuenta? <a href="<?= BASE_URL ?>index.php?url=auth/login" style="color: #cca462;">Inicia sesión aquí</a></p>
            </div>
        </div>
    </div>

</body>
</html>