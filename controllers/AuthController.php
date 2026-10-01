<?php
require_once __DIR__ . '/../models/Usuario.php';

class AuthController {

    public function index() {
        $this->login();
    }

    public function login() {
        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email    = trim($_POST['email'] ?? '');
            $password = trim($_POST['password'] ?? '');

            if (!empty($email) && !empty($password)) {
                $userModel = new Usuario();
                $usuario   = $userModel->buscarPorEmail($email);

                if (!$usuario) {
                    $error = "No se encontró ningún usuario con ese correo.";
                } elseif (!password_verify($password, $usuario['password'])) {
                    $error = "Contraseña incorrecta.";
                } else {
                    if (session_status() === PHP_SESSION_NONE) {
                        session_start();
                    }

                    $_SESSION['usuario_id']     = $usuario['id'] ?? $usuario['id_usuario'];
                    $_SESSION['usuario_nombre'] = $usuario['nombre'];
                    $_SESSION['usuario_email']  = $usuario['email'];
                    $_SESSION['usuario_rol_id'] = $usuario['rol_id'];
                    $_SESSION['usuario_rol']    = 'hotel_admin';

                    header("Location: " . BASE_URL . "index.php");
                    exit();
                }
            } else {
                $error = "Por favor completa todos los campos.";
            }
        }

        require_once __DIR__ . '/../views/auth/login.php';
    }
        
    public function register() {
        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre   = trim($_POST['nombre'] ?? '');
            $email    = trim($_POST['email'] ?? '');
            $password = trim($_POST['password'] ?? '');

            if (!empty($nombre) && !empty($email) && !empty($password)) {
                $userModel = new Usuario();
                
                if ($userModel->buscarPorEmail($email)) {
                    $error = "El correo electrónico ya está registrado.";
                } else {
                    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                    $registrado   = $userModel->registrar($nombre, $email, $passwordHash);

                    if ($registrado) {
                        header("Location: " . BASE_URL . "index.php?url=auth/register&success=1");
                        exit();
                    } else {
                        $error = "Ocurrió un error al crear la cuenta. Intenta de nuevo.";
                    }
                }
            } else {
                $error = "Por favor completa todos los campos.";
            }
        }

        require_once __DIR__ . '/../views/auth/register.php';
    }

    public function __call($name, $arguments) {
        if ($name === 'register-hotel' || $name === 'register_hotel') {
            return $this->registerHotel();
        }
    }

    // Método principal de registro de hotel (sin guiones)
    public function registerHotel() {
        $error = '';
        
        $nombre       = '';
        $email        = '';
        $password     = '';
        $nombre_hotel = '';
        $nit          = '';
        $direccion    = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre       = trim($_POST['nombre'] ?? '');
            $email        = trim($_POST['email'] ?? '');
            $password     = trim($_POST['password'] ?? '');
            $nombre_hotel = trim($_POST['nombre_hotel'] ?? '');
            $nit          = trim($_POST['nit'] ?? '');
            $direccion    = trim($_POST['direccion'] ?? '');
            
            $rol_id       = 4; // Rol de Hotelero / Administrador de Hotel

            if (!empty($nombre) && !empty($email) && !empty($password) && !empty($nombre_hotel)) {
                try {
                    $userModel = new Usuario();
                    $pdo       = $userModel->getConexion();

                    $pdo->beginTransaction();

                    // 1. Inserción del usuario con contraseña encriptada y rol_id
                    $stmtUser = $pdo->prepare("INSERT INTO usuarios (nombre, email, password, rol_id) VALUES (?, ?, ?, ?)");
                    $stmtUser->execute([$nombre, $email, password_hash($password, PASSWORD_DEFAULT), $rol_id]);
                    $usuario_id = $pdo->lastInsertId();

                    // 2. Inserción de los datos del hotel
                    $stmtHotel = $pdo->prepare("INSERT INTO hoteles (usuario_id, nombre_hotel, nit, direccion) VALUES (?, ?, ?, ?)");
                    $stmtHotel->execute([$usuario_id, $nombre_hotel, $nit, $direccion]);

                    $pdo->commit();

                    // 3. Inicio de sesión automático tras el registro exitoso
                    if (session_status() === PHP_SESSION_NONE) {
                        session_start();
                    }

                    $_SESSION['usuario_id']     = $usuario_id;
                    $_SESSION['usuario_nombre'] = $nombre;
                    $_SESSION['usuario_email']  = $email;
                    $_SESSION['usuario_rol_id'] = $rol_id;
                    $_SESSION['usuario_rol']    = 'hotel_admin';
                    $_SESSION['hotel_nombre']   = $nombre_hotel;

                    header("Location: " . BASE_URL . "index.php");
                    exit();

                } catch (Exception $e) {
                    if (isset($pdo) && $pdo->inTransaction()) {
                        $pdo->rollBack();
                    }
                    $error = "Error al registrar el hotel: " . $e->getMessage();
                }
            } else {
                $error = "Por favor completa todos los campos obligatorios.";
            }
        }

        require_once __DIR__ . '/../views/auth/register-hotel.php';
    }

    public function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_destroy();
        header("Location: " . BASE_URL . "index.php?url=auth/login");
        exit();
    }
}