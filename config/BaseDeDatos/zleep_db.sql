-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 27-10-2026 a las 18:51:00
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `zleep_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id_cliente` int(11) NOT NULL,
  `nombre_completo` varchar(150) NOT NULL,
  `documento_identidad` varchar(50) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `habitaciones`
--

CREATE TABLE `habitaciones` (
  `id_habitacion` int(11) NOT NULL,
  `id_hotel` int(11) NOT NULL,
  `numero` varchar(20) NOT NULL,
  `tipo` varchar(50) NOT NULL,
  `precio_noche` decimal(10,2) NOT NULL,
  `estado` varchar(20) DEFAULT 'Disponible'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hoteles`
--

CREATE TABLE `hoteles` (
  `id_hotel` int(11) NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `direccion` varchar(255) NOT NULL,
  `ciudad` varchar(100) DEFAULT 'Bogotá',
  `latitud` decimal(10,8) DEFAULT 0.00000000,
  `longitud` decimal(11,8) DEFAULT 0.00000000,
  `telefono` varchar(20) DEFAULT NULL,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `hoteles`
--

INSERT INTO `hoteles` (`id_hotel`, `nombre`, `direccion`, `ciudad`, `latitud`, `longitud`, `telefono`, `creado_en`) VALUES
(1, 'Grand Royal Hotel', 'Calle Principal #123', 'Bogotá', 0.00000000, 0.00000000, NULL, '2026-10-22 18:00:33');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `incidencias`
--

CREATE TABLE `incidencias` (
  `id_incidencia` int(11) NOT NULL,
  `id_hotel` int(11) NOT NULL,
  `id_protocolo` int(11) DEFAULT NULL,
  `fecha_hora` timestamp NOT NULL DEFAULT current_timestamp(),
  `descripcion` text NOT NULL,
  `estado_resolucion` varchar(30) DEFAULT 'En curso'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `protocolos_seguridad`
--

CREATE TABLE `protocolos_seguridad` (
  `id_protocolo` int(11) NOT NULL,
  `tipo_emergencia` varchar(50) NOT NULL,
  `titulo` varchar(150) NOT NULL,
  `pasos_accion` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reservas`
--

CREATE TABLE `reservas` (
  `id_reserva` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `id_habitacion` int(11) NOT NULL,
  `fecha_checkin` date NOT NULL,
  `fecha_checkout` date NOT NULL,
  `estado_reserva` varchar(20) DEFAULT 'Pendiente',
  `total_pago` decimal(10,2) NOT NULL,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `reservas`
--

INSERT INTO `reservas` (`id_reserva`, `id_cliente`, `id_habitacion`, `fecha_checkin`, `fecha_checkout`, `estado_reserva`, `total_pago`, `creado_en`) VALUES
(4, 4, 1, '0000-00-00', '0000-00-00', 'Confirmada', 150.00, '2026-10-22 20:43:21'),
(5, 4, 2, '2026-10-27', '2026-11-03', 'Confirmada', 150.00, '2026-10-22 20:49:44'),
(6, 4, 1, '2026-11-05', '2026-11-06', 'Confirmada', 150.00, '2026-10-27 17:31:34');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicios_emergencia`
--

CREATE TABLE `servicios_emergencia` (
  `id_servicio` int(11) NOT NULL,
  `id_hotel` int(11) NOT NULL,
  `tipo_servicio` varchar(30) NOT NULL,
  `nombre_unidad` varchar(150) NOT NULL,
  `telefono_contacto` varchar(20) NOT NULL,
  `distancia_estimada` decimal(4,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol` varchar(50) DEFAULT 'cliente',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `rol_id` int(11) DEFAULT 1,
  `hotel_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `email`, `password`, `rol`, `created_at`, `rol_id`, `hotel_id`) VALUES
(1, 'Administrador Zleep', 'admin@zleep.com', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHe1152aX1Gf7E9mQo4/eB19Ym/Y46bYGy', 'administrador', '2026-10-22 18:00:33', 1, NULL),
(2, 'Daniel Q', 'daniel@gmail.com', '$2y$10$Hl0/LKYt22JdRpQ6Sw8JL.63SJXnzj9A74yraJKYe3u3UBMvCQmqe', 'cliente', '2026-10-22 18:16:29', 1, NULL),
(3, 'pepe', 'pepe@gmail.com', '$2y$10$2I/Fa/jJeKvn8aCC.zOIt.cI/m2L8LylGcFZOA4GXtpZ2gjMc.IgK', 'cliente', '2026-10-22 18:32:29', 1, NULL),
(4, 'Daniel Quiñonez', 'daniel1@gmail.com', '$2y$10$K8a142TYm.ZnP57rHshqNOQ3RLFAxKaDPAhfCtdGrEMGYp.AowAG2', 'cliente', '2026-10-22 18:55:36', 1, NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id_cliente`),
  ADD UNIQUE KEY `documento_identidad` (`documento_identidad`);

--
-- Indices de la tabla `habitaciones`
--
ALTER TABLE `habitaciones`
  ADD PRIMARY KEY (`id_habitacion`),
  ADD KEY `idx_habitaciones_hotel_estado` (`id_hotel`,`estado`);

--
-- Indices de la tabla `hoteles`
--
ALTER TABLE `hoteles`
  ADD PRIMARY KEY (`id_hotel`);

--
-- Indices de la tabla `incidencias`
--
ALTER TABLE `incidencias`
  ADD PRIMARY KEY (`id_incidencia`),
  ADD KEY `fk_inc_prot` (`id_protocolo`),
  ADD KEY `idx_incidencias_hotel` (`id_hotel`);

--
-- Indices de la tabla `protocolos_seguridad`
--
ALTER TABLE `protocolos_seguridad`
  ADD PRIMARY KEY (`id_protocolo`);

--
-- Indices de la tabla `reservas`
--
ALTER TABLE `reservas`
  ADD PRIMARY KEY (`id_reserva`),
  ADD KEY `fk_reservas_clientes` (`id_cliente`),
  ADD KEY `fk_reservas_habitaciones` (`id_habitacion`),
  ADD KEY `idx_reservas_fechas` (`fecha_checkin`,`fecha_checkout`);

--
-- Indices de la tabla `servicios_emergencia`
--
ALTER TABLE `servicios_emergencia`
  ADD PRIMARY KEY (`id_servicio`),
  ADD KEY `idx_servicios_hotel_tipo` (`id_hotel`,`tipo_servicio`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id_cliente` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `habitaciones`
--
ALTER TABLE `habitaciones`
  MODIFY `id_habitacion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `hoteles`
--
ALTER TABLE `hoteles`
  MODIFY `id_hotel` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `incidencias`
--
ALTER TABLE `incidencias`
  MODIFY `id_incidencia` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `protocolos_seguridad`
--
ALTER TABLE `protocolos_seguridad`
  MODIFY `id_protocolo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `reservas`
--
ALTER TABLE `reservas`
  MODIFY `id_reserva` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `servicios_emergencia`
--
ALTER TABLE `servicios_emergencia`
  MODIFY `id_servicio` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `habitaciones`
--
ALTER TABLE `habitaciones`
  ADD CONSTRAINT `fk_habitaciones_hoteles` FOREIGN KEY (`id_hotel`) REFERENCES `hoteles` (`id_hotel`) ON DELETE CASCADE;

--
-- Filtros para la tabla `incidencias`
--
ALTER TABLE `incidencias`
  ADD CONSTRAINT `fk_inc_hot` FOREIGN KEY (`id_hotel`) REFERENCES `hoteles` (`id_hotel`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_inc_prot` FOREIGN KEY (`id_protocolo`) REFERENCES `protocolos_seguridad` (`id_protocolo`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `reservas`
--
ALTER TABLE `reservas`
  ADD CONSTRAINT `fk_reservas_clientes` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_reservas_habitaciones` FOREIGN KEY (`id_habitacion`) REFERENCES `habitaciones` (`id_habitacion`) ON DELETE CASCADE;

--
-- Filtros para la tabla `servicios_emergencia`
--
ALTER TABLE `servicios_emergencia`
  ADD CONSTRAINT `fk_servicios_hoteles` FOREIGN KEY (`id_hotel`) REFERENCES `hoteles` (`id_hotel`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
