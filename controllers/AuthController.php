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

                    // Determinar el rol_id desde la base de datos
                    $rolId = $usuario['rol_id'] ?? 1;
                    $usuario_id = $usuario['id'] ?? $usuario['id_usuario'];

                    $_SESSION['usuario_id']     = $usuario_id;
                    $_SESSION['usuario_nombre'] = $usuario['nombre'];
                    $_SESSION['usuario_email']  = $usuario['email'];
                    $_SESSION['usuario_rol_id'] = $rolId;

                    // Asignación dinámica de rol y redirección al Dashboard principal
                    if ($rolId == 4) {
                        $_SESSION['usuario_rol'] = 'hotel_admin';
                        
                        // Buscar y guardar el id_hotel asociado a este administrador
                        try {
                            $pdo = $userModel->getConexion();
                            $stmtHotel = $pdo->prepare("SELECT id_hotel, nombre FROM hoteles WHERE usuario_id = ?");
                            $stmtHotel->execute([$usuario_id]);
                            $hotelData = $stmtHotel->fetch(PDO::FETCH_ASSOC);

                            if ($hotelData) {
                                $_SESSION['id_hotel'] = $hotelData['id_hotel'];
                                $_SESSION['hotel_nombre'] = $hotelData['nombre'];
                            }
                        } catch (Exception $e) {
                            // Manejo silencioso o registro de error si fuera necesario
                        }

                        header("Location: " . BASE_URL . "index.php?url=dashboard");
                        exit();
                    } else {
                        $_SESSION['usuario_rol'] = 'cliente';
                        header("Location: " . BASE_URL . "index.php?url=dashboard");
                        exit();
                    }
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

            // Patrón estricto de los 5 requisitos
            $patronPassword = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*(),.?":{}|<>\-_]).{8,}$/';

            if (empty($nombre) || empty($email) || empty($password)) {
                $error = "Por favor completa todos los campos.";
            } elseif (!preg_match($patronPassword, $password)) {
                $error = ""; 
            } else {
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
            }
        }

        require_once __DIR__ . '/../views/auth/register.php';
    }

    public function __call($name, $arguments) {
        if ($name === 'register-hotel' || $name === 'register_hotel') {
            return $this->registerHotel();
        }
    }

    // Método principal de registro de hotel
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

            $patronPassword = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*(),.?":{}|<>\-_]).{8,}$/';

            // VALIDACIONES DE SEGURIDAD
            if (empty($nombre) || empty($email) || empty($password) || empty($nombre_hotel) || empty($nit) || empty($direccion)) {
                $error = "Por favor completa todos los campos obligatorios.";
            } elseif (!preg_match($patronPassword, $password)) {
                $error = ""; 
            } elseif (strlen($nombre_hotel) < 4 || strlen($direccion) < 6) {
                $error = "El nombre del hotel (mínimo 4 caracteres) y la dirección deben ser reales.";
            } elseif (!preg_match('/^[0-9\-]+$/', $nit) || strlen($nit) < 6) {
                $error = "Por favor ingresa un NIT o documento fiscal válido (solo números y guiones).";
            } else {
                try {
                    $userModel = new Usuario();
                    $pdo       = $userModel->getConexion();

                    // Verificar si el correo ya existe
                    if ($userModel->buscarPorEmail($email)) {
                        $error = "El correo electrónico ya está registrado.";
                    } else {
                        // Verificar si el NIT ya está registrado
                        $stmtNit = $pdo->prepare("SELECT id_hotel FROM hoteles WHERE nit = ?");
                        $stmtNit->execute([$nit]);
                        if ($stmtNit->rowCount() > 0) {
                            $error = "El NIT ingresado ya se encuentra registrado en el sistema.";
                        } else {
                            $pdo->beginTransaction();

                            // 1. Inserción del usuario con contraseña encriptada y rol_id
                            $stmtUser = $pdo->prepare("INSERT INTO usuarios (nombre, email, password, rol_id) VALUES (?, ?, ?, ?)");
                            $stmtUser->execute([$nombre, $email, password_hash($password, PASSWORD_DEFAULT), $rol_id]);
                            $usuario_id = $pdo->lastInsertId();

                            // 2. Inserción de los datos del hotel
                            $stmtHotel = $pdo->prepare("INSERT INTO hoteles (usuario_id, nombre, nit, direccion, ciudad) VALUES (?, ?, ?, ?, ?)");
                            $stmtHotel->execute([$usuario_id, $nombre_hotel, $nit, $direccion, 'Bogotá']);
                            $id_hotel = $pdo->lastInsertId();

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
                            $_SESSION['id_hotel']       = $id_hotel;
                            $_SESSION['hotel_nombre']   = $nombre_hotel;

                            header("Location: " . BASE_URL . "index.php?url=dashboard");
                            exit();
                        }
                    }
                } catch (Exception $e) {
                    if (isset($pdo) && $pdo->inTransaction()) {
                        $pdo->rollBack();
                    }
                    $error = "Error al registrar el hotel: " . $e->getMessage();
                }
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