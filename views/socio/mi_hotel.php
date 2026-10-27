<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!defined('BASE_URL')) {
    define('BASE_URL', '/zleep/');
}

$nombreUsuario = $_SESSION['usuario_nombre'] ?? 'Hotelero';
$nombreHotel   = $_SESSION['hotel_nombre'] ?? 'Mi Hotel';
$mensajeExito  = '';
$mensajeError  = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nuevoNombreHotel = trim($_POST['nombre_hotel'] ?? '');
    $nuevoNit         = trim($_POST['nit'] ?? '');
    $nuevaDireccion   = trim($_POST['direccion'] ?? '');
    $nuevaPassword    = trim($_POST['password'] ?? '');
    
    if (!empty($nuevoNombreHotel)) {
        $_SESSION['hotel_nombre'] = $nuevoNombreHotel;
        $nombreHotel = $nuevoNombreHotel;
        $mensajeExito = "¡Los datos de la propiedad se han actualizado correctamente!";
    } else {
        $mensajeError = "El nombre del hotel no puede estar vacío.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zleep - Gestión de Mi Propiedad</title>
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
        .content-grid {
            display: grid;
            grid-template-columns: 320px 1fr;
            gap: 2rem;
        }
        @media (max-width: 900px) {
            .content-grid { grid-template-columns: 1fr; }
            .full-page-container { padding: 1.5rem; }
        }
        .card-box {
            background: #0d1117;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 20px;
            padding: 2.2rem;
            box-shadow: 0 8px 24px rgba(0,0,0,0.4);
        }
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }
        @media (max-width: 768px) {
            .form-grid { grid-template-columns: 1fr; }
        }
        .form-group {
            margin-bottom: 1.2rem;
        }
        .form-group.full-width {
            grid-column: span 2;
        }
        @media (max-width: 768px) {
            .form-group.full-width { grid-column: span 1; }
        }
        .form-group label {
            display: block;
            font-size: 0.8rem;
            color: #8b949e;
            margin-bottom: 0.5rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .form-group input, .form-group textarea {
            width: 100%;
            padding: 0.9rem 1rem;
            background: #05070a;
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            color: #fff;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.95rem;
            outline: none;
            box-sizing: border-box;
            transition: border-color 0.2s ease;
        }
        .form-group input:focus, .form-group textarea:focus {
            border-color: #10b981;
        }
        .btn-save {
            background: #10b981;
            color: #000;
            padding: 0.9rem 2rem;
            border-radius: 12px;
            font-weight: 800;
            border: none;
            cursor: pointer;
            font-size: 1rem;
            transition: background 0.2s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }
        .btn-save:hover {
            background: #059669;
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
        .alert-success {
            background: rgba(16, 185, 129, 0.1);
            border: 1px solid #10b981;
            color: #10b981;
            padding: 1rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            font-size: 0.95rem;
        }
        .alert-error {
            background: rgba(244, 63, 94, 0.1);
            border: 1px solid #f43f5e;
            color: #f43f5e;
            padding: 1rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            font-size: 0.95rem;
        }
    </style>
</head>
<body>

    <div class="full-page-container">
        
        <!-- ENCABEZADO SUPERIOR -->
        <div class="top-bar">
            <div>
                <h1 style="font-family: 'Syne', sans-serif; font-size: 2.2rem; margin: 0 0 0.3rem 0; color: #fff;">
                    Gestión de Mi Propiedad
                </h1>
                <p style="color: #8b949e; font-size: 0.95rem; margin: 0;">
                    Configuración de <span style="color: #10b981; font-weight: 700;"><?= htmlspecialchars($nombreHotel) ?></span>
                </p>
            </div>
            <div>
                <a href="<?= BASE_URL ?>index.php" class="btn-home">
                    <span class="material-icons-round" style="font-size: 1.1rem;">home</span> Página Principal
                </a>
            </div>
        </div>

        <?php if (!empty($mensajeExito)): ?>
            <div class="alert-success"><?= htmlspecialchars($mensajeExito) ?></div>
        <?php endif; ?>

        <?php if (!empty($mensajeError)): ?>
            <div class="alert-error"><?= htmlspecialchars($mensajeError) ?></div>
        <?php endif; ?>

        <!-- DISTRIBUCIÓN -->
        <div class="content-grid">
            
            <!-- PANEL IZQUIERDO: ESTADO -->
            <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                <div class="card-box" style="background: linear-gradient(135deg, #0d1117 0%, #161b22 100%);">
                    <h3 style="font-family: 'Syne', sans-serif; color: #cca462; margin-top: 0; margin-bottom: 0.8rem; display: flex; align-items: center; gap: 0.5rem; font-size: 1.1rem;">
                        <span class="material-icons-round">verified</span> Estado de Socio
                    </h3>
                    <p style="color: #8b949e; font-size: 0.9rem; line-height: 1.6; margin-bottom: 1.5rem;">
                        Tu establecimiento cuenta con permisos activos para operar en Zleep y gestionar tus espacios.
                    </p>
                    <a href="<?= BASE_URL ?>index.php?url=socio/crear" style="background: rgba(16, 185, 129, 0.1); border: 1px solid #10b981; color: #10b981; padding: 0.8rem 1rem; border-radius: 10px; text-decoration: none; font-weight: 700; font-size: 0.9rem; display: flex; align-items: center; justify-content: space-between;">
                        <span style="display: flex; align-items: center; gap: 0.4rem;"><span class="material-icons-round" style="font-size: 1.1rem;">add_circle</span> Añadir Habitación</span>
                        <span class="material-icons-round" style="font-size: 1rem;">arrow_forward</span>
                    </a>
                </div>
            </div>

            <!-- PANEL DERECHO: FORMULARIO -->
            <div class="card-box">
                <h3 style="font-family: 'Syne', sans-serif; color: #10b981; margin-top: 0; margin-bottom: 1.8rem; display: flex; align-items: center; gap: 0.5rem; font-size: 1.25rem;">
                    <span class="material-icons-round">domain</span> Detalles del Establecimiento
                </h3>

                <form action="<?= BASE_URL ?>index.php?url=socio/miHotel" method="POST" autocomplete="off">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="nombre_hotel">Nombre del Hotel *</label>
                            <input type="text" id="nombre_hotel" name="nombre_hotel" required value="<?= htmlspecialchars($nombreHotel) ?>" placeholder="Ej. Hotel Plaza Central">
                        </div>

                        <div class="form-group">
                            <label for="nit">NIT / Identificación Tributaria</label>
                            <input type="text" id="nit" name="nit" value="900123456-1" placeholder="Ej. 900123456-1">
                        </div>

                        <div class="form-group full-width">
                            <label for="direccion">Dirección Física</label>
                            <input type="text" id="direccion" name="direccion" value="Calle 100 # 15-20, Bogotá" placeholder="Ej. Av. Principal #45-12">
                        </div>

                        <!-- CAMPO DE CONTRASEÑA INTERACTIVO PARA EL SOCIO -->
                        <div class="form-group full-width">
                            <label for="password">Contraseña de Acceso *</label>
                            <input type="password" id="passwordInput" name="password" class="form-control" placeholder="••••••••">
                            
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

                        <div class="form-group full-width">
                            <label for="descripcion">Descripción Comercial</label>
                            <textarea id="descripcion" name="descripcion" rows="4" placeholder="Describe los atractivos de tu hospedaje...">Hotel enfocado en la comodidad corporativa y turística, ofreciendo estancias seguras, conectividad de alta velocidad y un servicio personalizado.</textarea>
                        </div>
                    </div>

                    <div style="margin-top: 1.5rem; display: flex; justify-content: flex-end;">
                        <button type="submit" class="btn-save">
                            <span class="material-icons-round">save</span> Guardar Cambios
                        </button>
                    </div>
                </form>
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