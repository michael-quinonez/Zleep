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
    <title>Zleep - Gestionar Cuartos</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;700;800&family=Syne:wght@700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>style.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    
    <style>
        * { box-sizing: border-box; }
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
        .btn-action {
            background: #10b981;
            color: #000;
            padding: 0.7rem 1.2rem;
            border-radius: 12px;
            font-weight: 800;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.85rem;
            transition: background 0.2s ease;
        }
        .btn-action:hover {
            background: #059669;
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
            padding: 1.1rem 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }
        th {
            color: #8b949e;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 700;
        }
        td {
            color: #fff;
        }
        .badge-ocupada {
            background: rgba(244, 63, 94, 0.1);
            border: 1px solid #f43f5e;
            color: #f43f5e;
            padding: 0.3rem 0.8rem;
            border-radius: 99px;
            font-size: 0.75rem;
            font-weight: 700;
            display: inline-block;
        }
        .badge-disponible {
            background: rgba(16, 185, 129, 0.1);
            border: 1px solid #10b981;
            color: #10b981;
            padding: 0.3rem 0.8rem;
            border-radius: 99px;
            font-size: 0.75rem;
            font-weight: 700;
            display: inline-block;
        }
        /* Estilos del Modal */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(5, 7, 10, 0.8);
            justify-content: center;
            align-items: center;
            z-index: 1000;
            backdrop-filter: blur(5px);
        }
        .modal-content {
            background: #0d1117;
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 20px;
            padding: 2.5rem;
            width: 100%;
            max-width: 500px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.6);
        }
        .form-group {
            margin-bottom: 1.2rem;
        }
        .form-group label {
            display: block;
            font-size: 0.8rem;
            color: #8b949e;
            margin-bottom: 0.4rem;
            font-weight: 700;
            text-transform: uppercase;
        }
        .form-group input, .form-group select, .form-group textarea {
            width: 100%;
            padding: 0.8rem 1rem;
            background: #05070a;
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            color: #fff;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.95rem;
            outline: none;
        }
        .form-group input:focus, .form-group select:focus, .form-group textarea:focus {
            border-color: #10b981;
        }
    </style>
</head>
<body>

    <div class="full-page-container">
        
        <!-- ENCABEZADO SUPERIOR -->
        <div class="top-bar">
            <div>
                <h1 style="font-family: 'Syne', sans-serif; font-size: 2.2rem; margin: 0 0 0.3rem 0; color: #fff;">
                    Gestión de Cuartos y Ocupación
                </h1>
                <p style="color: #8b949e; font-size: 0.95rem; margin: 0;">
                    Supervisa habitaciones, huéspedes actuales y necesidades de <span style="color: #10b981; font-weight: 700;"><?= htmlspecialchars($nombreHotel) ?></span>
                </p>
            </div>
            <div style="display: flex; gap: 1rem; align-items: center; flex-wrap: wrap;">
                <a href="<?= BASE_URL ?>index.php?url=socio/crear" class="btn-action">
                    <span class="material-icons-round" style="font-size: 1.1rem;">add_circle</span> Publicar Nueva Habitación
                </a>
                <a href="<?= BASE_URL ?>index.php?url=socio/dashboard" style="color: #8b949e; text-decoration: none; font-weight: 700; font-size: 0.9rem;">← Volver al Panel</a>
                <a href="<?= BASE_URL ?>index.php" class="btn-home">
                    <span class="material-icons-round" style="font-size: 1.1rem;">home</span> Página Principal
                </a>
            </div>
        </div>

        <!-- TABLA DE GESTIÓN DE CUARTOS -->
        <div class="card-box">
            <h3 style="font-family: 'Syne', sans-serif; color: #10b981; margin-top: 0; margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.5rem; font-size: 1.25rem;">
                <span class="material-icons-round">king_bed</span> Listado General de Habitaciones y Huéspedes
            </h3>
            <p style="color: #8b949e; font-size: 0.9rem; margin-bottom: 1.5rem;">Control en tiempo real del estado de cada cuarto, detalles de quien reservó y requerimientos especiales.</p>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Habitación</th>
                            <th>Tipo / Categoría</th>
                            <th>Estado</th>
                            <th>Huésped (Quién Reservó)</th>
                            <th>Necesidades / Requerimientos</th>
                            <th style="text-align: right;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($habitaciones)): ?>
                            <?php foreach ($habitaciones as $hab): ?>
                                <?php 
                                    $tieneReserva = !empty($hab['id_reserva']);
                                    $estadoTexto = $tieneReserva ? 'Ocupada' : 'Disponible';
                                    $claseEstado = $tieneReserva ? 'badge-ocupada hab-estado' : 'badge-disponible hab-estado';
                                ?>
                                <tr>
                                    <td style="font-weight: 700; color: #cca462;" class="hab-nombre">Habitación <?= htmlspecialchars($hab['numero']) ?></td>
                                    <td class="hab-tipo"><?= htmlspecialchars($hab['categoria'] ?? 'Estándar') ?></td>
                                    <td><span class="<?= $claseEstado ?>"><?= $estadoTexto ?></span></td>
                                    <td class="hab-huesped">
                                        <?php if ($tieneReserva): ?>
                                            <?= htmlspecialchars($hab['nombre_huesp'] ?? 'Huésped') ?> (Tel: <?= htmlspecialchars($hab['telefono_huesp'] ?? 'No registrado') ?>)
                                        <?php else: ?>
                                            Sin reserva actual
                                        <?php endif; ?>
                                    </td>
                                    <td class="hab-req" style="color: #8b949e; font-size: 0.9rem;"><?= htmlspecialchars($hab['requerimientos'] ?? 'Ninguna') ?></td>
                                    <td style="text-align: right;">
                                        <button onclick="abrirModal(this)" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: #fff; padding: 0.4rem 0.8rem; border-radius: 8px; cursor: pointer; font-size: 0.85rem; font-weight: 700;">Editar</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" style="text-align: center; color: #8b949e; padding: 2rem;">No hay habitaciones registradas en el sistema.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- VENTANA MODAL PARA EDITAR HABITACIÓN -->
    <div id="modalEditar" class="modal-overlay">
        <div class="modal-content">
            <h3 style="font-family: 'Syne', sans-serif; color: #10b981; margin-top: 0; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
                <span class="material-icons-round">edit</span> Editar Habitación
            </h3>
            <form id="formEditar" onsubmit="guardarEdicion(event)">
                <div class="form-group">
                    <label>Número o Nombre del Cuarto</label>
                    <input type="text" id="editNombre" required>
                </div>
                <div class="form-group">
                    <label>Tipo / Categoría</label>
                    <input type="text" id="editTipo" required>
                </div>
                <div class="form-group">
                    <label>Estado Actual</label>
                    <select id="editEstado">
                        <option value="Disponible">Disponible</option>
                        <option value="Ocupada">Ocupada</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Huésped / Requerimientos</label>
                    <textarea id="editReq" rows="3"></textarea>
                </div>
                <div style="display: flex; gap: 1rem; margin-top: 1.8rem;">
                    <button type="submit" style="flex: 1; background: #10b981; color: #000; padding: 0.8rem; border-radius: 10px; font-weight: 800; border: none; cursor: pointer;">Guardar Cambios</button>
                    <button type="button" onclick="cerrarModal()" style="background: transparent; border: 1px solid rgba(255,255,255,0.2); color: #fff; padding: 0.8rem 1.2rem; border-radius: 10px; font-weight: 700; cursor: pointer;">Cancelar</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let filaSeleccionada = null;

        function abrirModal(button) {
            filaSeleccionada = button.closest('tr');
            
            // Extraer valores de la fila actual
            let nombre = filaSeleccionada.querySelector('.hab-nombre').innerText;
            let tipo = filaSeleccionada.querySelector('.hab-tipo').innerText;
            let estado = filaSeleccionada.querySelector('.hab-estado').innerText;
            let req = filaSeleccionada.querySelector('.hab-req').innerText;

            // Rellenar el modal
            document.getElementById('editNombre').value = nombre;
            document.getElementById('editTipo').value = tipo;
            document.getElementById('editEstado').value = estado;
            document.getElementById('editReq').value = req;

            // Mostrar modal
            document.getElementById('modalEditar').style.display = 'flex';
        }

        function cerrarModal() {
            document.getElementById('modalEditar').style.display = 'none';
            filaSeleccionada = null;
        }

        function guardarEdicion(event) {
            event.preventDefault();
            if (filaSeleccionada) {
                // Actualizar los datos visualmente en la tabla
                filaSeleccionada.querySelector('.hab-nombre').innerText = document.getElementById('editNombre').value;
                filaSeleccionada.querySelector('.hab-tipo').innerText = document.getElementById('editTipo').value;
                
                let nuevoEstado = document.getElementById('editEstado').value;
                let spanEstado = filaSeleccionada.querySelector('.hab-estado');
                spanEstado.innerText = nuevoEstado;
                spanEstado.className = nuevoEstado === 'Ocupada' ? 'badge-ocupada hab-estado' : 'badge-disponible hab-estado';
                
                filaSeleccionada.querySelector('.hab-req').innerText = document.getElementById('editReq').value;
            }
            cerrarModal();
        }
    </script>
</body>
</html>