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
    <title>Zleep - Registro de Hotelero</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;700;800&family=Syne:wght@700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>style.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
</head>
<body>
    <div class="auth-container" style="padding: 2rem 0;">
        <div class="auth-card" style="max-width: 520px;">
            <div class="auth-header">
                <div class="logo">
                    <span class="material-icons-round" style="font-size: 2rem; color: #10b981;">domain</span>
                    <h2>Zleep Partner</h2>
                </div>
                <p>Crea tu cuenta administrativa para gestionar tu establecimiento</p>
            </div>

            <?php if (!empty($error)): ?>
                <div class="alert-error" style="background: rgba(244, 63, 94, 0.1); border: 1px solid #f43f5e; color: #f43f5e; padding: 0.8rem; border-radius: 12px; margin-bottom: 1rem; font-size: 0.85rem; text-align: center;">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form action="<?= BASE_URL ?>index.php?url=auth/registerHotel" method="POST" autocomplete="off">
                <h4 style="color: #8b949e; margin: 0 0 0.8rem 0; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 1px;">Datos del Administrador</h4>
                
                <div class="form-group">
                    <label for="nombre">Nombre Completo *</label>
                    <input type="text" id="nombre" name="nombre" required placeholder="Ej. Carlos Mendoza" value="<?= htmlspecialchars($nombre ?? '') ?>">
                </div>

                <div class="form-group">
                    <label for="email">Correo Electrónico *</label>
                    <input type="email" id="email" name="email" required placeholder="admin@hotel.com" value="<?= htmlspecialchars($email ?? '') ?>">
                </div>

                <!-- CAMPO DE CONTRASEÑA INTERACTIVO -->
                <div class="form-group">
                    <label for="password">Contraseña *</label>
                    <input type="password" id="passwordInput" name="password" required placeholder="••••••••">
                    
                    <!-- Caja de requisitos visuales -->
                    <div class="password-requirements" style="margin-top: 0.8rem; background: #05070a; padding: 1rem; border-radius: 12px; border: 1px solid rgba(255,255,255,0.1);">
                        <p style="margin: 0 0 0.5rem 0; font-size: 0.85rem; color: #8b949e;">Requisitos de la contraseña:</p>
                        <ul style="list-style: none; padding: 0; margin: 0; font-size: 0.82rem;">
                            <li id="req-length" style="color: #f43f5e; margin-bottom: 0.3rem; display: flex; align-items: center; gap: 0.4rem; transition: color 0.2s;">
                                <span class="icon">✕</span> Mínimo 8 caracteres
                            </li>
                            <li id="req-upper" style="color: #f43f5e; margin-bottom: 0.3rem; display: flex; align-items: center; gap: 0.4rem; transition: color 0.2s;">
                                <span class="icon">✕</span> Al menos una letra mayúscula
                            </li>
                            <li id="req-lower" style="color: #f43f5e; margin-bottom: 0.3rem; display: flex; align-items: center; gap: 0.4rem; transition: color 0.2s;">
                                <span class="icon">✕</span> Al menos una letra minúscula
                            </li>
                            <li id="req-number" style="color: #f43f5e; margin-bottom: 0.3rem; display: flex; align-items: center; gap: 0.4rem; transition: color 0.2s;">
                                <span class="icon">✕</span> Al menos un número
                            </li>
                            <li id="req-special" style="color: #f43f5e; margin: 0; display: flex; align-items: center; gap: 0.4rem; transition: color 0.2s;">
                                <span class="icon">✕</span> Al menos un símbolo especial (! @ # $ % & * _)
                            </li>
                        </ul>
                    </div>
                </div>

                <h4 style="color: #8b949e; margin: 1.5rem 0 0.8rem 0; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 1px;">Información del Establecimiento</h4>

                <div class="form-group">
                    <label for="nombre_hotel">Nombre del Hotel *</label>
                    <input type="text" id="nombre_hotel" name="nombre_hotel" required placeholder="Ej. Hotel Plaza Central" value="<?= htmlspecialchars($nombre_hotel ?? '') ?>">
                </div>

                <div class="form-group">
                    <label for="nit">NIT / Identificación Tributaria</label>
                    <input type="text" id="nit" name="nit" placeholder="Ej. 900123456-1" value="<?= htmlspecialchars($nit ?? '') ?>">
                </div>

                <div class="form-group">
                    <label for="direccion">Dirección</label>
                    <input type="text" id="direccion" name="direccion" placeholder="Calle / Avenida..." value="<?= htmlspecialchars($direccion ?? '') ?>">
                </div>

                <button type="submit" class="btn-submit" style="background: #10b981; color: #000;">
                    Registrar Hotel y Administrador
                </button>
            </form>

            <div class="auth-footer" style="margin-top: 1.5rem; text-align: center; font-size: 0.85rem; color: #8b949e;">
                <p>¿Buscas reservar hospedaje? <a href="<?= BASE_URL ?>index.php?url=auth/register" style="color: #cca462; font-weight: 700;">Registro de Cliente</a></p>
                <p style="margin-top: 0.5rem;">¿Ya tienes una cuenta? <a href="<?= BASE_URL ?>index.php?url=auth/login" style="color: #10b981;">Inicia sesión aquí</a></p>
            </div>
        </div>
    </div>

    <!-- SCRIPT DE VALIDACIÓN EN TIEMPO REAL -->
    <script>
        const passwordInput = document.getElementById('passwordInput');
        const reqLength = document.getElementById('req-length');
        const reqUpper = document.getElementById('req-upper');
        const reqLower = document.getElementById('req-lower');
        const reqNumber = document.getElementById('req-number');
        const reqSpecial = document.getElementById('req-special');

        if (passwordInput) {
            passwordInput.addEventListener('input', function() {
                const val = passwordInput.value;

                // 1. Longitud mínima (8 caracteres)
                validateRequirement(val.length >= 8, reqLength);

                // 2. Letra mayúscula
                validateRequirement(/[A-Z]/.test(val), reqUpper);

                // 3. Letra minúscula
                validateRequirement(/[a-z]/.test(val), reqLower);

                // 4. Número
                validateRequirement(/[0-9]/.test(val), reqNumber);

                // 5. Carácter especial
                validateRequirement(/[!@#$%&*_\-_]/.test(val), reqSpecial);
            });
        }

        function validateRequirement(isValid, element) {
            const icon = element.querySelector('.icon');
            if (isValid) {
                element.style.color = '#10b981'; // Color verde de éxito
                icon.textContent = '✓';
            } else {
                element.style.color = '#f43f5e'; // Color rojo de error
                icon.textContent = '✕';
            }
        }
    </script>
</body>
</html>