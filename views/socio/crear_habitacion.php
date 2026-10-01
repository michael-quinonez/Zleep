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
    <title>Zleep - Publicar Habitación</title>
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
            max-width: 800px;
            margin: 0 auto;
            padding: 3rem 1.5rem;
        }
        .header-actions {
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
        }
        .btn-home {
            background: rgba(204, 164, 98, 0.1);
            border: 1px solid #cca462;
            color: #cca462;
            padding: 0.6rem 1.2rem;
            border-radius: 99px;
            font-weight: 700;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.9rem;
            transition: background 0.2s ease;
        }
        .btn-home:hover {
            background: rgba(204, 164, 98, 0.2);
        }
        .panel-box {
            background: #0d1117;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        }
        .form-group {
            margin-bottom: 1.5rem;
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
        .form-group input, .form-group select {
            width: 100%;
            padding: 0.9rem 1rem;
            background: #05070a;
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            color: #fff;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.95rem;
            outline: none;
            transition: border-color 0.2s ease;
        }
        .form-group input:focus, .form-group select:focus {
            border-color: #10b981;
        }
        .btn-action {
            background: #10b981;
            color: #000;
            padding: 0.85rem 1.8rem;
            border-radius: 12px;
            font-weight: 800;
            border: none;
            cursor: pointer;
            font-size: 1rem;
            transition: background 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
        }
        .btn-action:hover {
            background: #059669;
        }
        .btn-back {
            background: transparent;
            border: 1px solid rgba(255, 255, 255, 0.15);
            color: #8b949e;
            padding: 0.85rem 1.5rem;
            border-radius: 12px;
            font-weight: 700;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.95rem;
            transition: all 0.2s ease;
        }
        .btn-back:hover {
            border-color: #fff;
            color: #fff;
        }
        .buttons-footer {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-top: 2rem;
            flex-wrap: wrap;
        }
    </style>
</head>
<body>

    <div class="container">
        
        <!-- BOTONES SUPERIORES DE NAVEGACIÓN -->
        <div class="header-actions">
            <a href="<?= BASE_URL ?>index.php" class="btn-home">
                <span class="material-icons-round" style="font-size: 1rem;">home</span> Página Principal
            </a>
        </div>

        <div class="panel-box">
            <h2 style="font-family: 'Syne', sans-serif; color: #10b981; margin-top: 0; margin-bottom: 2rem; font-size: 1.6rem;">
                Publicar Nueva Habitación
            </h2>

            <form action="" method="POST">
                <div class="form-group">
                    <label for="numero_habitacion">Número o Nombre de Habitación</label>
                    <input type="text" id="numero_habitacion" name="numero_habitacion" placeholder="Ej. Suite 202" required>
                </div>

                <div class="form-group">
                    <label for="tipo_habitacion">Tipo de Habitación</label>
                    <select id="tipo_habitacion" name="tipo_habitacion">
                        <option value="individual">Individual</option>
                        <option value="doble">Doble</option>
                        <option value="suite">Suite Presidencial</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="precio">Precio por Noche ($)</label>
                    <input type="number" id="precio" name="precio" step="0.01" placeholder="0.00" required>
                </div>

                <div class="buttons-footer">
                    <button type="submit" class="btn-action">
                        <span class="material-icons-round">check</span> Guardar Habitación
                    </button>
                    <a href="<?= BASE_URL ?>index.php?url=socio/dashboard" class="btn-back">
                        <span class="material-icons-round" style="font-size: 1.1rem;">arrow_back</span> Volver al Dashboard
                    </a>
                </div>
            </form>
        </div>
    </div>

</body>
</html>