-- 1. Crear y seleccionar la base de datos primero
CREATE DATABASE IF NOT EXISTS zleep_db;
USE zleep_db;

-- 2. Eliminar tablas en orden inverso para evitar conflictos
DROP TABLE IF EXISTS incidencias;
DROP TABLE IF EXISTS servicios_emergencia;
DROP TABLE IF EXISTS reservas;
DROP TABLE IF EXISTS habitaciones;
DROP TABLE IF EXISTS clientes;
DROP TABLE IF EXISTS hoteles_a;
DROP TABLE IF EXISTS hoteles;
DROP TABLE IF EXISTS protocolos_seguridad;
DROP TABLE IF EXISTS usuarios;

-- 3. Tabla principal de Usuarios (Incluyendo rol_id que exige tu modelo PHP)
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    rol VARCHAR(50) DEFAULT 'cliente',
    rol_id INT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 4. Tabla de Hoteles
CREATE TABLE hoteles (
    id_hotel INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL,
    direccion VARCHAR(255) NOT NULL,
    ciudad VARCHAR(100) DEFAULT 'Bogotá',
    latitud DECIMAL(10, 8) DEFAULT 0.00000000,
    longitud DECIMAL(11, 8) DEFAULT 0.00000000,
    telefono VARCHAR(20),
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 5. Tabla de Habitaciones
CREATE TABLE habitaciones (
    id_habitacion INT AUTO_INCREMENT PRIMARY KEY,
    id_hotel INT NOT NULL,
    numero VARCHAR(20) NOT NULL,
    tipo VARCHAR(50) NOT NULL,
    precio_noche DECIMAL(10, 2) NOT NULL,
    estado VARCHAR(20) DEFAULT 'Disponible',
    CONSTRAINT fk_habitaciones_hoteles FOREIGN KEY (id_hotel) REFERENCES hoteles(id_hotel) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 6. Tabla de Clientes
CREATE TABLE clientes (
    id_cliente INT AUTO_INCREMENT PRIMARY KEY,
    nombre_completo VARCHAR(150) NOT NULL,
    documento_identidad VARCHAR(50) UNIQUE NOT NULL,
    telefono VARCHAR(20),
    email VARCHAR(100),
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 7. Tabla de Reservas
CREATE TABLE reservas (
    id_reserva INT AUTO_INCREMENT PRIMARY KEY,
    id_cliente INT NOT NULL,
    id_habitacion INT NOT NULL,
    fecha_checkin DATE NOT NULL,
    fecha_checkout DATE NOT NULL,
    estado_reserva VARCHAR(20) DEFAULT 'Pendiente',
    total_pago DECIMAL(10, 2) NOT NULL,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_reservas_clientes FOREIGN KEY (id_cliente) REFERENCES clientes(id_cliente) ON DELETE CASCADE,
    CONSTRAINT fk_reservas_habitaciones FOREIGN KEY (id_habitacion) REFERENCES habitaciones(id_habitacion) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 8. Tabla de Protocolos de Seguridad
CREATE TABLE protocolos_seguridad (
    id_protocolo INT AUTO_INCREMENT PRIMARY KEY,
    tipo_emergencia VARCHAR(50) NOT NULL,
    titulo VARCHAR(150) NOT NULL,
    pasos_accion TEXT NOT NULL
) ENGINE=InnoDB;

-- 9. Tabla de Servicios de Emergencia
CREATE TABLE servicios_emergencia (
    id_servicio INT AUTO_INCREMENT PRIMARY KEY,
    id_hotel INT NOT NULL,
    tipo_servicio VARCHAR(30) NOT NULL,
    nombre_unidad VARCHAR(150) NOT NULL,
    telefono_contacto VARCHAR(20) NOT NULL,
    distancia_estimada DECIMAL(4, 2) NOT NULL,
    CONSTRAINT fk_servicios_hoteles FOREIGN KEY (id_hotel) REFERENCES hoteles(id_hotel) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 10. Tabla de Incidencias
CREATE TABLE incidencias (
    id_incidencia INT AUTO_INCREMENT PRIMARY KEY,
    id_hotel INT NOT NULL,
    id_protocolo INT NULL,
    fecha_hora TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    descripcion TEXT NOT NULL,
    estado_resolucion VARCHAR(30) DEFAULT 'En curso',
    CONSTRAINT fk_inc_hot FOREIGN KEY (id_hotel) REFERENCES hoteles(id_hotel) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_inc_prot FOREIGN KEY (id_protocolo) REFERENCES protocolos_seguridad(id_protocolo) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB;

-- 11. Índices
CREATE INDEX idx_reservas_fechas ON reservas (fecha_checkin, fecha_checkout);
CREATE INDEX idx_habitaciones_hotel_estado ON habitaciones (id_hotel, estado);
CREATE INDEX idx_servicios_hotel_tipo ON servicios_emergencia (id_hotel, tipo_servicio);
CREATE INDEX idx_incidencias_hotel ON incidencias (id_hotel);

-- 12. Datos Iniciales
INSERT IGNORE INTO hoteles (id_hotel, nombre, direccion, ciudad) 
VALUES (1, 'Grand Royal Hotel', 'Calle Principal #123', 'Bogotá');

INSERT IGNORE INTO usuarios (id, nombre, email, password, rol, rol_id) 
VALUES (1, 'Administrador Zleep', 'admin@zleep.com', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHe1152aX1Gf7E9mQo4/eB19Ym/Y46bYGy', 'administrador', 1);