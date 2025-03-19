-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 13-03-2025 a las 18:28:09
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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `persona`
--

CREATE TABLE `persona` (
  `id_persona` int(11) NOT NULL,
  `nombre` varchar(45) NOT NULL,
  `apellido` varchar(45) NOT NULL,
  `cedula` int(12) NOT NULL,
  `correo` varchar(45) NOT NULL,
  `id_departamento` int(11) NOT NULL,
  `id_sexo` int(11) NOT NULL,
  `fecha_nac` date NOT NULL,
  `id_usuario` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `persona`
--

INSERT INTO `persona` (`id_persona`, `nombre`, `apellido`, `cedula`, `correo`, `id_departamento`, `id_sexo`, `fecha_nac`, `id_usuario`) VALUES
(8, 'Oscar', 'Bermúdez', 30870327, 'oscarx798@gmail.com', 1, 1, '2025-01-14', 2),
(12, 'Santiago', 'Barreto', 30508673, 'santigo@gmail.com', 1, 1, '2025-02-19', NULL),
(15, 'Oscary', 'Lozada', 15936662, 'oscary@gmail.com', 3, 2, '1983-03-15', NULL),
(17, 'Anyerli', 'Zurita', 30508566, 'anyerlidelosangeles@gmail.com', 1, 2, '2004-09-23', 7);

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
  `frecuencia` decimal(3,2) NOT NULL,
  `id_estado_pieza_procesador` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reporte_fallas`
--

CREATE TABLE `reporte_fallas` (
  `id_reporte_fallas` int(11) NOT NULL,
  `id_u_estandar` int(11) NOT NULL,
  `id_equipo_informatico` int(11) NOT NULL,
  `fecha` datetime NOT NULL,
  `contenido_reporte_fallas` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `id_rol` int(11) NOT NULL,
  `rol` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`id_rol`, `rol`) VALUES
(1, 'Administrador'),
(2, 'Estándar');

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
(2, 'Arcize', '$2y$10$o3qxye6ajOELmDkuiGn2qOh1t0dFwxN8nCkbuP4j1gQt.Xmcmzavy', 1),
(7, 'Anyiii', '$2y$10$qg5Q/Vd0c9JPnrzxtOiJHeKesDctCtGu.nnO6DapoW2FQrpnxMO7G', 2);

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
  ADD PRIMARY KEY (`id_reporte_fallas`);

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`id_rol`);

--
-- Indices de la tabla `sexo`
--
ALTER TABLE `sexo`
  ADD PRIMARY KEY (`id_sexo`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `almacenamiento`
--
ALTER TABLE `almacenamiento`
  MODIFY `id_almacenamiento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `departamento`
--
ALTER TABLE `departamento`
  MODIFY `id_departamento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `equipo_informatico`
--
ALTER TABLE `equipo_informatico`
  MODIFY `id_equipo_informatico` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

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
  MODIFY `id_estado_reporte_fallas` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `fuente_poder`
--
ALTER TABLE `fuente_poder`
  MODIFY `id_fuente_poder` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `motherboard`
--
ALTER TABLE `motherboard`
  MODIFY `id_motherboard` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de la tabla `persona`
--
ALTER TABLE `persona`
  MODIFY `id_persona` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `procesador`
--
ALTER TABLE `procesador`
  MODIFY `id_procesador` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT de la tabla `ram`
--
ALTER TABLE `ram`
  MODIFY `id_ram` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `reporte_fallas`
--
ALTER TABLE `reporte_fallas`
  MODIFY `id_reporte_fallas` int(11) NOT NULL AUTO_INCREMENT;

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
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
