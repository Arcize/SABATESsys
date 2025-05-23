-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 27-04-2025 a las 22:47:38
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
-- Base de datos: `sabatessys`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `almacenamiento`
--

CREATE TABLE `almacenamiento` (
  `id_almacenamiento` int(11) NOT NULL,
  `id_equipo_informatico_almacenamiento` int(11) DEFAULT NULL,
  `fabricante_almacenamiento` varchar(25) NOT NULL,
  `tipo_almacenamiento` varchar(10) NOT NULL,
  `capacidad_almacenamiento` int(11) NOT NULL,
  `id_estado_pieza_almacenamiento` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `almacenamiento`
--

INSERT INTO `almacenamiento` (`id_almacenamiento`, `id_equipo_informatico_almacenamiento`, `fabricante_almacenamiento`, `tipo_almacenamiento`, `capacidad_almacenamiento`, `id_estado_pieza_almacenamiento`) VALUES
(6, 10, 'Western Digital', 'SSD', 1000, 1),
(11, 15, 'Samsung', 'SSD', 2000, 1),
(12, 16, 'Adata', 'SSD', 1000, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `departamento`
--

CREATE TABLE `departamento` (
  `id_departamento` int(11) NOT NULL,
  `nombre_departamento` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `departamento`
--

INSERT INTO `departamento` (`id_departamento`, `nombre_departamento`) VALUES
(1, 'Informática'),
(2, 'Recursos Humanos'),
(3, 'Contabilidad');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `equipo_informatico`
--

CREATE TABLE `equipo_informatico` (
  `id_equipo_informatico` int(11) NOT NULL,
  `fabricante_equipo_informatico` varchar(25) NOT NULL,
  `id_estado_equipo` int(11) NOT NULL,
  `id_persona` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `equipo_informatico`
--

INSERT INTO `equipo_informatico` (`id_equipo_informatico`, `fabricante_equipo_informatico`, `id_estado_equipo`, `id_persona`) VALUES
(10, 'Asus', 1, 12),
(15, 'Dell', 1, NULL),
(16, 'Dell', 1, 80);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado_equipo_informatico`
--

CREATE TABLE `estado_equipo_informatico` (
  `id_estado_equipo_informatico` int(11) NOT NULL,
  `estado_equipo_informatico` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estado_equipo_informatico`
--

INSERT INTO `estado_equipo_informatico` (`id_estado_equipo_informatico`, `estado_equipo_informatico`) VALUES
(1, 'Operativo'),
(2, 'Averiado'),
(3, 'En reparación'),
(4, 'En espera de piezas'),
(5, 'Retirado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado_pieza`
--

CREATE TABLE `estado_pieza` (
  `id_estado_pieza` int(11) NOT NULL,
  `estado_pieza` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estado_pieza`
--

INSERT INTO `estado_pieza` (`id_estado_pieza`, `estado_pieza`) VALUES
(1, 'Operativo'),
(2, 'Averiado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado_reporte_fallas`
--

CREATE TABLE `estado_reporte_fallas` (
  `id_estado_reporte_fallas` int(11) NOT NULL,
  `estado_reporte_fallas` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estado_reporte_fallas`
--

INSERT INTO `estado_reporte_fallas` (`id_estado_reporte_fallas`, `estado_reporte_fallas`) VALUES
(1, 'Pendiente'),
(2, 'En proceso'),
(3, 'Completado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fuente_poder`
--

CREATE TABLE `fuente_poder` (
  `id_fuente_poder` int(11) NOT NULL,
  `id_equipo_informatico_fuente` int(11) DEFAULT NULL,
  `fabricante_fuente_poder` varchar(20) NOT NULL,
  `wattage` int(11) NOT NULL,
  `id_estado_pieza_fuente` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `fuente_poder`
--

INSERT INTO `fuente_poder` (`id_fuente_poder`, `id_equipo_informatico_fuente`, `fabricante_fuente_poder`, `wattage`, `id_estado_pieza_fuente`) VALUES
(17, 10, 'Thermaltake', 650, 1),
(22, 15, 'Corsair', 650, 1),
(23, 16, 'Dell', 600, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `motherboard`
--

CREATE TABLE `motherboard` (
  `id_motherboard` int(11) NOT NULL,
  `id_equipo_informatico_motherboard` int(11) DEFAULT NULL,
  `fabricante_motherboard` varchar(25) NOT NULL,
  `modelo_motherboard` varchar(25) NOT NULL,
  `id_estado_pieza_motherboard` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `motherboard`
--

INSERT INTO `motherboard` (`id_motherboard`, `id_equipo_informatico_motherboard`, `fabricante_motherboard`, `modelo_motherboard`, `id_estado_pieza_motherboard`) VALUES
(23, 10, 'Gigabyte', 'BZ790', 1),
(28, 15, 'Dell', 'A870', 1),
(29, 16, 'Asus', 'B680', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permisos`
--

CREATE TABLE `permisos` (
  `id_permisos` int(11) NOT NULL,
  `nombre_permiso` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `permisos`
--

INSERT INTO `permisos` (`id_permisos`, `nombre_permiso`) VALUES
(2, 'ver_dashboard'),
(3, 'ver_empleados'),
(4, 'editar_empleados'),
(5, 'registrar_empleados'),
(6, 'ver_equipos'),
(7, 'editar_equipos'),
(8, 'registrar_equipos'),
(9, 'reportar_falla'),
(10, 'ver_falla'),
(11, 'editar_falla'),
(12, 'reportar_actividad'),
(13, 'ver_actividad'),
(14, 'editar_actividad'),
(15, 'ver_usuarios'),
(16, 'editar_usuarios'),
(17, 'gestionar_roles'),
(18, 'ver_inventario'),
(19, 'hacer_regis_inventario'),
(20, 'editar_regis_inventario'),
(21, 'ver_notificaciones'),
(22, 'cargar_regis_empleados'),
(23, 'recup_contraseña'),
(24, 'ver_todos_repor_falla'),
(25, 'ver_todos_repor_actividades'),
(26, 'ver_configuracion');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `persona`
--

CREATE TABLE `persona` (
  `id_persona` int(11) NOT NULL,
  `nombre` varchar(30) NOT NULL,
  `apellido` varchar(30) NOT NULL,
  `cedula` int(8) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `id_departamento` int(11) NOT NULL,
  `id_sexo` int(11) NOT NULL,
  `fecha_nac` date NOT NULL,
  `id_usuario` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `persona`
--

INSERT INTO `persona` (`id_persona`, `nombre`, `apellido`, `cedula`, `correo`, `id_departamento`, `id_sexo`, `fecha_nac`, `id_usuario`) VALUES
(12, 'Santiago', 'Barreto', 30508673, 'santiago@gmail.com', 1, 1, '2025-02-19', NULL),
(17, 'Anyerli', 'Zurita', 30508566, 'anyerlidelosangeles@gmail.com', 1, 2, '2004-09-23', 9),
(18, 'Oscar', 'Bermúdez', 30870327, 'oscarx798@gmail.com', 1, 1, '2005-02-04', 8),
(23, 'Luis', 'Marcano', 30385225, 'luismarcano@gmail.com', 1, 1, '2005-04-19', NULL),
(76, 'Juan', 'Pérez', 10023456, 'juan.perez@example.com', 1, 1, '1990-01-15', NULL),
(77, 'María', 'Gómez', 11034567, 'maria.gomez@example.com', 2, 2, '1985-05-22', NULL),
(78, 'Carlos', 'Rodríguez', 12045678, 'carlos.rodriguez@example.com', 3, 1, '1992-03-14', NULL),
(79, 'Ana', 'Martínez', 13056789, 'ana.martinez@example.com', 1, 2, '1998-07-18', NULL),
(80, 'Luis', 'Hernández', 14067890, 'luis.hernandez@example.com', 2, 1, '1987-09-25', NULL),
(81, 'Lauren', 'López', 15078901, 'laura.lopez@example.com', 3, 2, '1993-11-10', NULL),
(82, 'José', 'García', 16089012, 'jose.garcia@example.com', 1, 1, '1995-08-30', NULL),
(84, 'Andrés', 'González', 18012345, 'andres.gonzalez@example.com', 3, 1, '1989-06-15', NULL),
(195, 'Carlos', 'Ramírez', 12345678, 'carlos.ramirez@example.com', 1, 1, '0000-00-00', NULL),
(196, 'María', 'Fernández', 87654321, 'maria.fernandez@example.com', 2, 2, '0000-00-00', NULL),
(197, 'Juan', 'Pérez', 45678912, 'juan.perez@example.com', 3, 1, '0000-00-00', NULL),
(198, 'Ana', 'Gómez', 78912345, 'ana.gomez@example.com', 2, 2, '2035-03-05', NULL),
(199, 'Luis', 'Torres', 32165487, 'luis.torres@example.com', 1, 1, '0000-00-00', NULL),
(200, 'Sofía', 'Martínez', 25687432, 'sofia.martinez@example.com', 3, 2, '0000-00-00', NULL),
(201, 'Pedro', 'González', 19876543, 'pedro.gonzalez@example.com', 2, 1, '0000-00-00', NULL),
(202, 'Gabriela', 'Hernández', 27896541, 'gabriela.hernandez@example.com', 1, 2, '0000-00-00', NULL),
(203, 'Miguel', 'Rojas', 31256789, 'miguel.rojas@example.com', 3, 1, '0000-00-00', NULL),
(204, 'Valentina', 'Díaz', 26987431, 'valentina.diaz@example.com', 1, 2, '0000-00-00', NULL),
(205, 'Ricardo', 'Flores', 17234567, 'ricardo.flores@example.com', 2, 1, '2037-05-06', NULL),
(206, 'Camila', 'Morales', 29456783, 'camila.morales@example.com', 3, 2, '2035-07-01', NULL),
(207, 'Esteban', 'Castillo', 23167895, 'esteban.castillo@example.com', 2, 1, '0000-00-00', NULL),
(208, 'Patricia', 'Méndez', 31956784, 'patricia.mendez@example.com', 1, 2, '0000-00-00', NULL),
(209, 'Felipe', 'Soto', 18675432, 'felipe.soto@example.com', 3, 1, '0000-00-00', NULL),
(210, 'Lucía', 'Vega', 21567894, 'lucia.vega@example.com', 1, 2, '0000-00-00', NULL),
(211, 'Daniel', 'Fuentes', 30456789, 'daniel.fuentes@example.com', 2, 1, '0000-00-00', NULL),
(212, 'Carolina', 'Gutiérrez', 28765412, 'carolina.gutierrez@example.com', 3, 2, '0000-00-00', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `procesador`
--

CREATE TABLE `procesador` (
  `id_procesador` int(11) NOT NULL,
  `id_equipo_informatico_procesador` int(11) DEFAULT NULL,
  `fabricante_procesador` varchar(15) NOT NULL,
  `nombre_procesador` varchar(30) NOT NULL,
  `nucleos` int(11) NOT NULL,
  `frecuencia` decimal(4,2) NOT NULL,
  `id_estado_pieza_procesador` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `procesador`
--

INSERT INTO `procesador` (`id_procesador`, `id_equipo_informatico_procesador`, `fabricante_procesador`, `nombre_procesador`, `nucleos`, `frecuencia`, `id_estado_pieza_procesador`) VALUES
(24, 10, 'Intel', 'i7 4790', 4, 3.60, 1),
(29, 15, 'Intel', 'i7 12700KF', 12, 4.00, 1),
(30, 16, 'Intel', 'i7 4770', 8, 3.60, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ram`
--

CREATE TABLE `ram` (
  `id_ram` int(11) NOT NULL,
  `id_equipo_informatico_ram` int(11) DEFAULT NULL,
  `fabricante_ram` varchar(25) NOT NULL,
  `tipo_ram` varchar(10) NOT NULL,
  `capacidad_ram` int(11) NOT NULL,
  `frecuencia_ram` int(11) NOT NULL,
  `id_estado_pieza_ram` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ram`
--

INSERT INTO `ram` (`id_ram`, `id_equipo_informatico_ram`, `fabricante_ram`, `tipo_ram`, `capacidad_ram`, `frecuencia_ram`, `id_estado_pieza_ram`) VALUES
(9, 10, 'Adata', 'DDR3', 16, 1333, 1),
(14, 15, 'Adata', 'DDR3', 16, 1600, 1),
(15, 16, 'Adata', 'DDR4', 16, 2333, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reporte_fallas`
--

CREATE TABLE `reporte_fallas` (
  `id_reporte_fallas` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_equipo_informatico` int(11) NOT NULL,
  `fecha_hora_reporte_fallas` timestamp NOT NULL DEFAULT current_timestamp(),
  `contenido_reporte_fallas` text NOT NULL,
  `id_estado_reporte_fallas` int(11) NOT NULL,
  `fecha_falla` date NOT NULL,
  `tecnico_asignado` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `reporte_fallas`
--

INSERT INTO `reporte_fallas` (`id_reporte_fallas`, `id_usuario`, `id_equipo_informatico`, `fecha_hora_reporte_fallas`, `contenido_reporte_fallas`, `id_estado_reporte_fallas`, `fecha_falla`, `tecnico_asignado`) VALUES
(25, 8, 10, '2025-04-04 00:07:34', 'bbbbbbbbbbbbbaaaaa', 1, '2025-04-03', 8),
(26, 8, 16, '2025-04-19 04:17:39', 'awqawaswawddawwaw', 1, '2025-04-18', 9);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `id_rol` int(11) NOT NULL,
  `rol` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`id_rol`, `rol`) VALUES
(1, 'Administrador'),
(2, 'Estándar');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles_permisos`
--

CREATE TABLE `roles_permisos` (
  `id_rol` int(11) NOT NULL,
  `id_permiso` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `roles_permisos`
--

INSERT INTO `roles_permisos` (`id_rol`, `id_permiso`) VALUES
(1, 2),
(1, 3),
(1, 4),
(1, 5),
(1, 6),
(1, 7),
(1, 8),
(1, 9),
(1, 10),
(1, 11),
(1, 12),
(1, 13),
(1, 14),
(1, 15),
(1, 16),
(1, 17),
(1, 18),
(1, 19),
(1, 20),
(1, 21),
(1, 22),
(1, 23),
(1, 24),
(1, 25),
(1, 26),
(2, 10);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sexo`
--

CREATE TABLE `sexo` (
  `id_sexo` int(11) NOT NULL,
  `sexo` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `sexo`
--

INSERT INTO `sexo` (`id_sexo`, `sexo`) VALUES
(1, 'Masculino'),
(2, 'Femenino');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id_usuario` int(11) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(60) NOT NULL,
  `id_rol` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id_usuario`, `username`, `password`, `id_rol`) VALUES
(8, 'Arcize', '$2y$10$enuzQIB0ikeS1lvGhHBrm.R7zKKJiAbGFZ1/Hgu677NW0wNzkspMC', 1),
(9, 'AnyerliZ', '$2y$10$quhDQN59uSvhKCvLdpFSP.6eig.FI1dRzdraopLRXBQMET5jK1QM.', 2);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `almacenamiento`
--
ALTER TABLE `almacenamiento`
  ADD PRIMARY KEY (`id_almacenamiento`),
  ADD KEY `id_equipo_informatico__almacenamiento_idx` (`id_equipo_informatico_almacenamiento`),
  ADD KEY `id_estado_pieza_almacenamiento_idx` (`id_estado_pieza_almacenamiento`);

--
-- Indices de la tabla `departamento`
--
ALTER TABLE `departamento`
  ADD PRIMARY KEY (`id_departamento`);

--
-- Indices de la tabla `equipo_informatico`
--
ALTER TABLE `equipo_informatico`
  ADD PRIMARY KEY (`id_equipo_informatico`),
  ADD KEY `id_estado_equipo_idx` (`id_estado_equipo`),
  ADD KEY `id_persona_idx` (`id_persona`);

--
-- Indices de la tabla `estado_equipo_informatico`
--
ALTER TABLE `estado_equipo_informatico`
  ADD PRIMARY KEY (`id_estado_equipo_informatico`);

--
-- Indices de la tabla `estado_pieza`
--
ALTER TABLE `estado_pieza`
  ADD PRIMARY KEY (`id_estado_pieza`);

--
-- Indices de la tabla `estado_reporte_fallas`
--
ALTER TABLE `estado_reporte_fallas`
  ADD PRIMARY KEY (`id_estado_reporte_fallas`);

--
-- Indices de la tabla `fuente_poder`
--
ALTER TABLE `fuente_poder`
  ADD PRIMARY KEY (`id_fuente_poder`),
  ADD KEY `id_estado_pieza_idx` (`id_estado_pieza_fuente`),
  ADD KEY `id_equipo_informatico_fuente_idx` (`id_equipo_informatico_fuente`);

--
-- Indices de la tabla `motherboard`
--
ALTER TABLE `motherboard`
  ADD PRIMARY KEY (`id_motherboard`),
  ADD KEY `id_estado_pieza_idx2` (`id_estado_pieza_motherboard`),
  ADD KEY `id_equipo_informatico_motherboard_idx` (`id_equipo_informatico_motherboard`);

--
-- Indices de la tabla `permisos`
--
ALTER TABLE `permisos`
  ADD PRIMARY KEY (`id_permisos`);

--
-- Indices de la tabla `persona`
--
ALTER TABLE `persona`
  ADD PRIMARY KEY (`id_persona`),
  ADD UNIQUE KEY `cedula` (`cedula`),
  ADD KEY `fk_departamento_idx` (`id_departamento`),
  ADD KEY `fk_sexo_idx` (`id_sexo`),
  ADD KEY `cedula_2` (`cedula`),
  ADD KEY `fk_usuario_idx` (`id_usuario`);

--
-- Indices de la tabla `procesador`
--
ALTER TABLE `procesador`
  ADD PRIMARY KEY (`id_procesador`),
  ADD KEY `id_estado_pieza_procesador_idx` (`id_estado_pieza_procesador`),
  ADD KEY `id_equipo_informatico_procesador_idx` (`id_equipo_informatico_procesador`);

--
-- Indices de la tabla `ram`
--
ALTER TABLE `ram`
  ADD PRIMARY KEY (`id_ram`),
  ADD KEY `id_equipo_informatico_idx` (`id_equipo_informatico_ram`),
  ADD KEY `id_estado_pieza_ram_idx` (`id_estado_pieza_ram`);

--
-- Indices de la tabla `reporte_fallas`
--
ALTER TABLE `reporte_fallas`
  ADD PRIMARY KEY (`id_reporte_fallas`),
  ADD KEY `id_usuario_idx` (`id_usuario`),
  ADD KEY `id_equipo_informatico_idx` (`id_equipo_informatico`),
  ADD KEY `id_estado_reporte_fallas_idx` (`id_estado_reporte_fallas`),
  ADD KEY `id_tecnico_idx` (`tecnico_asignado`);

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`id_rol`);

--
-- Indices de la tabla `roles_permisos`
--
ALTER TABLE `roles_permisos`
  ADD PRIMARY KEY (`id_rol`,`id_permiso`),
  ADD KEY `id_permiso_idx` (`id_permiso`);

--
-- Indices de la tabla `sexo`
--
ALTER TABLE `sexo`
  ADD PRIMARY KEY (`id_sexo`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_usuario`),
  ADD KEY `id_rol_idx3` (`id_rol`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `almacenamiento`
--
ALTER TABLE `almacenamiento`
  MODIFY `id_almacenamiento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `departamento`
--
ALTER TABLE `departamento`
  MODIFY `id_departamento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `equipo_informatico`
--
ALTER TABLE `equipo_informatico`
  MODIFY `id_equipo_informatico` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `estado_equipo_informatico`
--
ALTER TABLE `estado_equipo_informatico`
  MODIFY `id_estado_equipo_informatico` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `estado_pieza`
--
ALTER TABLE `estado_pieza`
  MODIFY `id_estado_pieza` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `estado_reporte_fallas`
--
ALTER TABLE `estado_reporte_fallas`
  MODIFY `id_estado_reporte_fallas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `fuente_poder`
--
ALTER TABLE `fuente_poder`
  MODIFY `id_fuente_poder` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT de la tabla `motherboard`
--
ALTER TABLE `motherboard`
  MODIFY `id_motherboard` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT de la tabla `permisos`
--
ALTER TABLE `permisos`
  MODIFY `id_permisos` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT de la tabla `persona`
--
ALTER TABLE `persona`
  MODIFY `id_persona` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=235;

--
-- AUTO_INCREMENT de la tabla `procesador`
--
ALTER TABLE `procesador`
  MODIFY `id_procesador` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de la tabla `ram`
--
ALTER TABLE `ram`
  MODIFY `id_ram` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `reporte_fallas`
--
ALTER TABLE `reporte_fallas`
  MODIFY `id_reporte_fallas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `sexo`
--
ALTER TABLE `sexo`
  MODIFY `id_sexo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `almacenamiento`
--
ALTER TABLE `almacenamiento`
  ADD CONSTRAINT `id_equipo_informatico__almacenamiento` FOREIGN KEY (`id_equipo_informatico_almacenamiento`) REFERENCES `equipo_informatico` (`id_equipo_informatico`) ON DELETE CASCADE ON UPDATE SET NULL,
  ADD CONSTRAINT `id_estado_pieza_almacenamiento` FOREIGN KEY (`id_estado_pieza_almacenamiento`) REFERENCES `estado_pieza` (`id_estado_pieza`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `equipo_informatico`
--
ALTER TABLE `equipo_informatico`
  ADD CONSTRAINT `id_estado_equipo` FOREIGN KEY (`id_estado_equipo`) REFERENCES `estado_equipo_informatico` (`id_estado_equipo_informatico`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `id_persona` FOREIGN KEY (`id_persona`) REFERENCES `persona` (`id_persona`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `fuente_poder`
--
ALTER TABLE `fuente_poder`
  ADD CONSTRAINT `id_equipo_informatico_fuente` FOREIGN KEY (`id_equipo_informatico_fuente`) REFERENCES `equipo_informatico` (`id_equipo_informatico`) ON DELETE CASCADE ON UPDATE SET NULL,
  ADD CONSTRAINT `id_estado_pieza` FOREIGN KEY (`id_estado_pieza_fuente`) REFERENCES `estado_pieza` (`id_estado_pieza`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `motherboard`
--
ALTER TABLE `motherboard`
  ADD CONSTRAINT `id_equipo_informatico_motherboard` FOREIGN KEY (`id_equipo_informatico_motherboard`) REFERENCES `equipo_informatico` (`id_equipo_informatico`) ON DELETE CASCADE ON UPDATE SET NULL,
  ADD CONSTRAINT `id_estado_pieza_fuente` FOREIGN KEY (`id_estado_pieza_motherboard`) REFERENCES `estado_pieza` (`id_estado_pieza`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `persona`
--
ALTER TABLE `persona`
  ADD CONSTRAINT `fk_departamento` FOREIGN KEY (`id_departamento`) REFERENCES `departamento` (`id_departamento`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_sexo` FOREIGN KEY (`id_sexo`) REFERENCES `sexo` (`id_sexo`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE SET NULL;

--
-- Filtros para la tabla `procesador`
--
ALTER TABLE `procesador`
  ADD CONSTRAINT `id_equipo_informatico_procesador` FOREIGN KEY (`id_equipo_informatico_procesador`) REFERENCES `equipo_informatico` (`id_equipo_informatico`) ON DELETE CASCADE ON UPDATE SET NULL,
  ADD CONSTRAINT `id_estado_pieza_procesador` FOREIGN KEY (`id_estado_pieza_procesador`) REFERENCES `estado_pieza` (`id_estado_pieza`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `ram`
--
ALTER TABLE `ram`
  ADD CONSTRAINT `id_equipo_informatico` FOREIGN KEY (`id_equipo_informatico_ram`) REFERENCES `equipo_informatico` (`id_equipo_informatico`) ON DELETE CASCADE ON UPDATE SET NULL,
  ADD CONSTRAINT `id_estado_pieza_ram` FOREIGN KEY (`id_estado_pieza_ram`) REFERENCES `estado_pieza` (`id_estado_pieza`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `reporte_fallas`
--
ALTER TABLE `reporte_fallas`
  ADD CONSTRAINT `id_equipo_informatico2` FOREIGN KEY (`id_equipo_informatico`) REFERENCES `equipo_informatico` (`id_equipo_informatico`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `id_estado_reporte_fallas` FOREIGN KEY (`id_estado_reporte_fallas`) REFERENCES `estado_reporte_fallas` (`id_estado_reporte_fallas`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `id_tecnico` FOREIGN KEY (`tecnico_asignado`) REFERENCES `usuario` (`id_usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `id_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `roles_permisos`
--
ALTER TABLE `roles_permisos`
  ADD CONSTRAINT `id_permiso` FOREIGN KEY (`id_permiso`) REFERENCES `permisos` (`id_permisos`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `id_rol` FOREIGN KEY (`id_rol`) REFERENCES `rol` (`id_rol`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `id_rol3` FOREIGN KEY (`id_rol`) REFERENCES `rol` (`id_rol`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
