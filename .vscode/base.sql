DROP TABLE IF EXISTS incidencias;
DROP TABLE IF EXISTS protocolos_seguridad;
DROP TABLE IF EXISTS servicios_emergencia;
DROP TABLE IF EXISTS reservas;
DROP TABLE IF EXISTS clientes;
DROP TABLE IF EXISTS habitaciones;
DROP TABLE IF EXISTS hoteles;

CREATE TABLE protocolos_seguridad (
    id_protocolo INT AUTO_INCREMENT PRIMARY KEY,
    tipo_emergencia VARCHAR(50) NOT NULL,
    titulo VARCHAR(150) NOT NULL,
    pasos_accion TEXT NOT NULL
) ENGINE=InnoDB;

CREATE TABLE hoteles (
    id_hotel INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL,
    direccion VARCHAR(255) NOT NULL,
    ciudad VARCHAR(100) NOT NULL,
    latitud DECIMAL(10, 8) NOT NULL,
    longitud DECIMAL(11, 8) NOT NULL,
    telefono VARCHAR(20),
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE habitaciones (
    id_habitacion INT AUTO_INCREMENT PRIMARY KEY,
    id_hotel INT NOT NULL,
    numero VARCHAR(20) NOT NULL,
    tipo VARCHAR(50) NOT NULL,
    precio_noche DECIMAL(10, 2) NOT NULL,
    estado VARCHAR(20) DEFAULT 'Disponible',
    CONSTRAINT fk_habitaciones_hoteles FOREIGN KEY (id_hotel) REFERENCES hoteles(id_hotel) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE clientes (
    id_cliente INT AUTO_INCREMENT PRIMARY KEY,
    nombre_completo VARCHAR(150) NOT NULL,
    documento_identidad VARCHAR(50) UNIQUE NOT NULL,
    telefono VARCHAR(20),
    email VARCHAR(100),
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

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

CREATE TABLE servicios_emergencia (
    id_servicio INT AUTO_INCREMENT PRIMARY KEY,
    id_hotel INT NOT NULL,
    tipo_servicio VARCHAR(30) NOT NULL,
    nombre_unidad VARCHAR(150) NOT NULL,
    telefono_contacto VARCHAR(20) NOT NULL,
    distancia_estimada DECIMAL(4, 2) NOT NULL,
    CONSTRAINT fk_servicios_hoteles FOREIGN KEY (id_hotel) REFERENCES hoteles(id_hotel) ON DELETE CASCADE
) ENGINE=InnoDB;

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

CREATE INDEX idx_reservas_fechas ON reservas (fecha_checkin, fecha_checkout);
CREATE INDEX idx_habitaciones_hotel_estado ON habitaciones (id_hotel, estado);
CREATE INDEX idx_servicios_hotel_tipo ON servicios_emergencia (id_hotel, tipo_servicio);
CREATE INDEX idx_incidencias_hotel ON incidencias (id_hotel);

USE zleep_db;

-- Crear Hotel si no existe
INSERT IGNORE INTO hoteles (id, nombre, direccion) 
VALUES (1, 'Grand Royal Hotel', 'Calle Principal #123');

-- Insertar usuario con la contraseña encriptada "123456"
INSERT IGNORE INTO usuarios (hotel_id, nombre, email, password, rol) 
VALUES (1, 'Administrador Zleep', 'admin@zleep.com', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHe1152aX1Gf7E9mQo4/eB19Ym/Y46bYGy', 'administrador');

-- 1. Tabla de Usuarios
CREATE TABLE usuarios_h(
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    rol ENUM('cliente', 'hotel_admin', 'super_admin') DEFAULT 'cliente',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 2. Tabla de Hoteles (Vinculada al usuario creador)
CREATE TABLE hoteles_a(
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL, -- ID del dueño que lo registró
    nombre_hotel VARCHAR(150) NOT NULL,
    nit VARCHAR(50),
    direccion VARCHAR(255),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);