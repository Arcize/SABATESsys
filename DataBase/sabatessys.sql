-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 30-05-2025 a las 22:21:15
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

DELIMITER $$
--
-- Funciones
--
CREATE DEFINER=`root`@`localhost` FUNCTION `dias_habiles_transcurridos` (`fecha_inicio` DATETIME) RETURNS INT(11) DETERMINISTIC BEGIN
    DECLARE dias INT DEFAULT 0;
    DECLARE fecha_actual DATETIME;
    SET fecha_actual = fecha_inicio;

    WHILE DATE_ADD(fecha_actual, INTERVAL 1 DAY) < NOW() DO
        SET fecha_actual = DATE_ADD(fecha_actual, INTERVAL 1 DAY);
        SET @dia_semana = DAYOFWEEK(fecha_actual); -- 1=Domingo, 7=Sábado
        IF @dia_semana NOT IN (1, 7) THEN -- Ignorar domingos y sábados
            SET dias = dias + 1;
        END IF;
    END WHILE;

    RETURN dias;
END$$

DELIMITER ;

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
(18, 17, 'Western Digital', 'SSD', 2000, 1),
(19, 18, 'Samsung', 'SSD', 500, 1),
(20, 19, 'Western Digital', 'SSD', 500, 1),
(21, 10, 'Samsung', 'SSD', 1000, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dashboard_config`
--

CREATE TABLE `dashboard_config` (
  `id_usuario_dashboard` int(11) NOT NULL,
  `dashboard_config` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `dashboard_config`
--

INSERT INTO `dashboard_config` (`id_usuario_dashboard`, `dashboard_config`) VALUES
(1, '[{\"x\":9,\"y\":2,\"w\":3,\"h\":4,\"panelId\":\"1\",\"minW\":2,\"minH\":3},{\"x\":0,\"y\":0,\"w\":4,\"h\":3,\"panelId\":\"2\",\"minW\":2,\"minH\":3},{\"x\":6,\"y\":0,\"w\":6,\"h\":2,\"panelId\":\"3\",\"minW\":4,\"minH\":2},{\"x\":4,\"y\":3,\"w\":5,\"h\":3,\"panelId\":\"4\",\"minW\":4,\"minH\":3},{\"x\":4,\"y\":0,\"w\":2,\"h\":1,\"panelId\":\"5\"},{\"x\":0,\"y\":3,\"w\":4,\"h\":1,\"panelId\":\"6\"},{\"x\":6,\"y\":2,\"w\":3,\"h\":1,\"panelId\":\"7\"},{\"x\":0,\"y\":4,\"w\":4,\"h\":2,\"panelId\":\"8\"},{\"x\":4,\"y\":1,\"w\":2,\"h\":2,\"panelId\":\"9\"}]'),
(12, '[{\"x\":0,\"y\":0,\"w\":3,\"h\":3,\"panelId\":\"1\",\"minW\":2,\"minH\":3},{\"x\":9,\"y\":2,\"w\":3,\"h\":4,\"panelId\":\"2\",\"minW\":2,\"minH\":3},{\"x\":7,\"y\":0,\"w\":5,\"h\":2,\"panelId\":\"3\",\"minW\":4,\"minH\":2},{\"x\":3,\"y\":0,\"w\":4,\"h\":3,\"panelId\":\"4\",\"minW\":4,\"minH\":3},{\"x\":1,\"y\":3,\"w\":1,\"h\":1,\"panelId\":\"5\"},{\"x\":0,\"y\":3,\"w\":1,\"h\":1,\"panelId\":\"6\"},{\"x\":0,\"y\":4,\"w\":3,\"h\":2,\"panelId\":\"7\"},{\"x\":2,\"y\":3,\"w\":1,\"h\":1,\"panelId\":\"8\"},{\"x\":3,\"y\":4,\"w\":4,\"h\":2,\"panelId\":\"9\"}]');

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
(10, 'Asus', 1, 1),
(17, 'Wasus', 1, 17),
(18, 'Dell', 1, 12),
(19, 'Wasus', 1, NULL);

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
(4, 'Retirado');

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
(3, 'Completado'),
(4, 'Duplicado'),
(5, 'Inconsistente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `evidencia_reporte_actividades`
--

CREATE TABLE `evidencia_reporte_actividades` (
  `id_evidencia` int(11) NOT NULL,
  `id_actividad` int(11) NOT NULL,
  `ruta_evidencia` varchar(255) NOT NULL,
  `fecha_subida` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `tipo_mime` varchar(50) NOT NULL,
  `tamano_bytes` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `evidencia_reporte_actividades`
--

INSERT INTO `evidencia_reporte_actividades` (`id_evidencia`, `id_actividad`, `ruta_evidencia`, `fecha_subida`, `tipo_mime`, `tamano_bytes`) VALUES
(58, 89, 'uploads/report_89/682fa48834c29.png', '2025-05-22 22:26:23', 'image/png', 862183);

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
(24, 17, 'Thermaltake', 650, 1),
(25, 18, 'Corsair', 700, 1),
(26, 19, 'Thermaltake', 700, 1);

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
(30, 17, 'Gigabyte', 'BZ790', 1),
(31, 18, 'Rog', 'H614', 1),
(32, 19, 'Gigabyte', 'BZ790', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notificaciones`
--

CREATE TABLE `notificaciones` (
  `id` int(11) NOT NULL,
  `mensaje` text NOT NULL,
  `tipo` varchar(20) NOT NULL,
  `id_destino` int(11) DEFAULT NULL,
  `id_reporte_asociado` int(11) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT current_timestamp(),
  `leida` tinyint(1) DEFAULT 0,
  `fecha_expiracion` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `notificaciones`
--

INSERT INTO `notificaciones` (`id`, `mensaje`, `tipo`, `id_destino`, `id_reporte_asociado`, `fecha_creacion`, `leida`, `fecha_expiracion`) VALUES
(9, 'Nuevo reporte de falla creado con el código: #838B8157', 'rol', 3, 66, '2025-05-27 09:49:37', 0, NULL),
(10, 'Nuevo reporte de falla creado con el código: #838B8157', 'rol', 1, 66, '2025-05-27 09:49:37', 0, NULL),
(11, 'Nuevo reporte de falla creado con el código: #0754C344', 'rol', 3, 67, '2025-05-30 11:25:51', 0, NULL),
(12, 'Nuevo reporte de falla creado con el código: #0754C344', 'rol', 1, 67, '2025-05-30 11:25:51', 0, NULL),
(13, 'Nuevo reporte de falla creado con el código: #7A20F906', 'rol', 3, 68, '2025-05-30 16:02:06', 0, NULL),
(14, 'Nuevo reporte de falla creado con el código: #7A20F906', 'rol', 1, 68, '2025-05-30 16:02:06', 0, NULL),
(15, 'Nuevo reporte de falla creado con el código: #1E907DE5', 'rol', 3, 69, '2025-05-30 16:03:00', 0, NULL),
(16, 'Nuevo reporte de falla creado con el código: #1E907DE5', 'rol', 1, 69, '2025-05-30 16:03:00', 0, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `participante_reporte_actividad`
--

CREATE TABLE `participante_reporte_actividad` (
  `id_reporte_actividad` int(11) NOT NULL,
  `cedula` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `participante_reporte_actividad`
--

INSERT INTO `participante_reporte_actividad` (`id_reporte_actividad`, `cedula`) VALUES
(89, 30385225),
(89, 30508566),
(89, 30508673),
(89, 30870327);

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
  `id_usuario` int(11) DEFAULT NULL,
  `estado_empleado` varchar(10) DEFAULT 'Activo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `persona`
--

INSERT INTO `persona` (`id_persona`, `nombre`, `apellido`, `cedula`, `correo`, `id_departamento`, `id_sexo`, `fecha_nac`, `id_usuario`, `estado_empleado`) VALUES
(1, 'Oscar', 'Bermúdez', 30870327, 'oscarx798@gmail.com', 1, 1, '2005-02-04', 1, 'Activo'),
(12, 'Santiago', 'Barreto', 30508673, 'santiago@gmail.com', 1, 1, '2007-05-10', 10, 'Activo'),
(17, 'Anyerli', 'Zurita', 30508566, 'anyerlidelosangeles@gmail.com', 1, 2, '2004-09-23', 12, 'Activo'),
(23, 'Luis', 'Marcano', 30385225, 'luismarcano@gmail.com', 1, 1, '2005-04-19', NULL, 'Activo'),
(76, 'Juan', 'Pérez', 10023456, 'juan.perez@example.com', 1, 1, '1990-01-15', NULL, 'Activo'),
(77, 'María', 'Gómez', 11034567, 'maria.gomez@example.com', 2, 2, '1985-05-22', NULL, 'Activo'),
(78, 'Carlos', 'Rodríguez', 12045678, 'carlos.rodriguez@example.com', 3, 1, '1992-03-14', 11, 'Activo'),
(79, 'Ana', 'Martínez', 13056789, 'ana.martinez@example.com', 1, 2, '1998-07-18', NULL, 'Activo'),
(80, 'Luis', 'Hernández', 14067890, 'luis.hernandez@example.com', 2, 1, '1987-09-25', NULL, 'Activo'),
(81, 'Lauren', 'López', 15078901, 'laura.lopez@example.com', 3, 2, '1993-11-10', NULL, 'Activo'),
(82, 'José', 'García', 16089012, 'jose.garcia@example.com', 1, 1, '1995-08-30', NULL, 'Activo'),
(269, 'Oscary', 'Lozada', 15936662, 'oscary@gmail.com', 2, 2, '2007-05-08', NULL, 'Activo'),
(270, 'Rebeca', 'Pereira', 30143120, 'rebepereira19@gmail.com', 1, 2, '2001-03-14', NULL, 'Activo'),
(275, 'Gabriel', 'Villalba', 30123456, 'gabooooo@gmail.com', 1, 1, '2007-05-16', NULL, 'Activo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `preguntas_seguridad`
--

CREATE TABLE `preguntas_seguridad` (
  `id_pregunta` int(11) NOT NULL,
  `texto_pregunta` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `preguntas_seguridad`
--

INSERT INTO `preguntas_seguridad` (`id_pregunta`, `texto_pregunta`) VALUES
(1, '¿Cuál es el nombre de tu primera mascota?'),
(2, '¿En qué ciudad naciste?'),
(3, '¿Cuál es el nombre de tu mejor amigo de la infancia?'),
(4, '¿Cuál es tu comida favorita?'),
(5, '¿Cuál es el nombre de tu escuela primaria?'),
(6, '¿Cuál es el segundo nombre de tu madre?'),
(7, '¿Cuál es el modelo de tu primer automóvil?'),
(8, '¿Cuál es el nombre de tu primer jefe?'),
(9, '¿Cuál es tu película favorita?'),
(10, '¿Cuál es el nombre de tu primer amor?');

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
(31, 17, 'Intel', 'i7 4790', 4, 3.60, 1),
(32, 18, 'Amd', 'Ryzen 5 5600G', 6, 4.00, 1),
(33, 19, 'Amd', 'Ryzen 5 5600G', 6, 4.00, 1);

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
(25, 17, 'Adata', 'DDR4', 8, 2600, 1),
(26, 17, 'Corsair', 'DDR4', 8, 2600, 1),
(27, 18, 'Samsung', 'DDR4', 8, 2800, 1),
(28, 19, 'Samsung', 'DDR3', 8, 2400, 1),
(29, 19, 'Adata', 'DDR3', 8, 2800, 1),
(30, 10, 'Samsung', 'DDR4', 16, 2600, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reporte_actividades`
--

CREATE TABLE `reporte_actividades` (
  `id_reporte_actividades` int(11) NOT NULL,
  `codigo_reporte_actividades` varchar(8) DEFAULT NULL,
  `id_usuario` int(11) NOT NULL,
  `fecha_actividad` date NOT NULL,
  `fecha_reporte` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `titulo_reporte` varchar(60) NOT NULL,
  `contenido_reporte` text NOT NULL,
  `id_tipo_reporte` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `reporte_actividades`
--

INSERT INTO `reporte_actividades` (`id_reporte_actividades`, `codigo_reporte_actividades`, `id_usuario`, `fecha_actividad`, `fecha_reporte`, `titulo_reporte`, `contenido_reporte`, `id_tipo_reporte`) VALUES
(89, 'CA621065', 1, '2025-05-22', '2025-05-30 16:18:34', 'Desarrollo de software', 'Ggggggggggggggggggggggggggg', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reporte_fallas`
--

CREATE TABLE `reporte_fallas` (
  `id_reporte_fallas` int(11) NOT NULL,
  `codigo_reporte_fallas` varchar(8) DEFAULT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_equipo_informatico` int(11) DEFAULT NULL,
  `fecha_hora_reporte_fallas` timestamp NOT NULL DEFAULT current_timestamp(),
  `contenido_reporte_fallas` text NOT NULL,
  `id_estado_reporte_fallas` int(11) NOT NULL,
  `prioridad` varchar(20) DEFAULT NULL,
  `fecha_falla` date NOT NULL,
  `tecnico_asignado` int(11) DEFAULT NULL,
  `id_tipo_falla` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `reporte_fallas`
--

INSERT INTO `reporte_fallas` (`id_reporte_fallas`, `codigo_reporte_fallas`, `id_usuario`, `id_equipo_informatico`, `fecha_hora_reporte_fallas`, `contenido_reporte_fallas`, `id_estado_reporte_fallas`, `prioridad`, `fecha_falla`, `tecnico_asignado`, `id_tipo_falla`) VALUES
(57, '91DA3639', 1, 10, '2025-05-20 18:49:07', 'awawa', 1, 'Alta', '2025-05-19', NULL, 1),
(66, '838B8157', 11, NULL, '2025-05-27 13:49:37', 'La red interna esta fallando y no permite subir archivos al servidor', 2, 'Alta', '2025-05-27', 12, 2),
(67, '0754C344', 11, NULL, '2025-05-30 15:25:51', 'La conexion se ha caido hoy en la manana durante la jornada laboral', 2, 'Baja', '2025-05-30', 12, 2),
(68, '7A20F906', 11, NULL, '2025-05-30 20:02:06', 'El sistema de archivos no los esta subiendo a la carpeta compartida', 1, 'Baja', '2025-05-30', NULL, 2),
(69, '1E907DE5', 11, NULL, '2025-05-30 20:03:00', 'La impresora no imprime', 2, 'Baja', '2025-05-30', 12, 3);

--
-- Disparadores `reporte_fallas`
--
DELIMITER $$
CREATE TRIGGER `registrar_cambio_prioridad_falla` AFTER UPDATE ON `reporte_fallas` FOR EACH ROW BEGIN
    -- Verificar si el campo prioridad ha cambiado
    IF OLD.prioridad <> NEW.prioridad THEN
        -- Insertar un nuevo registro en la tabla de seguimiento
        INSERT INTO seguimiento (id_reporte_fallas, accion, id_usuario_accion, id_tecnico, fecha_seguimiento, id_estado_reporte, prioridad)
        VALUES (NEW.id_reporte_fallas, 'Cambio de prioridad', NULL, NEW.tecnico_asignado, NOW(), NEW.id_estado_reporte_fallas, NEW.prioridad);
    END IF;
END
$$
DELIMITER ;

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
(2, 'Estándar'),
(3, 'Técnico'),
(4, 'Monitor de Actividades');

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
(2, 9),
(2, 10),
(3, 6),
(3, 10);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `seguimiento`
--

CREATE TABLE `seguimiento` (
  `id_seguimiento` int(11) NOT NULL,
  `id_reporte_fallas` int(11) NOT NULL,
  `accion` text NOT NULL,
  `id_usuario_accion` int(11) DEFAULT NULL,
  `id_tecnico` int(11) DEFAULT NULL,
  `fecha_seguimiento` timestamp NOT NULL DEFAULT current_timestamp(),
  `id_estado_reporte` int(11) NOT NULL,
  `prioridad` varchar(10) DEFAULT NULL,
  `descripcion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `seguimiento`
--

INSERT INTO `seguimiento` (`id_seguimiento`, `id_reporte_fallas`, `accion`, `id_usuario_accion`, `id_tecnico`, `fecha_seguimiento`, `id_estado_reporte`, `prioridad`, `descripcion`) VALUES
(55, 57, 'Creación de reporte', 1, NULL, '2025-05-20 18:49:07', 1, 'Baja', NULL),
(56, 57, 'Reporte aceptado', 12, 12, '2025-05-20 18:50:09', 2, 'Baja', NULL),
(57, 57, 'Reporte Rechazado', 12, NULL, '2025-05-20 19:15:54', 1, 'Baja', NULL),
(58, 57, 'Reporte aceptado', 12, 12, '2025-05-20 19:15:57', 2, 'Baja', NULL),
(59, 57, 'Reporte Rechazado', 12, NULL, '2025-05-20 19:37:25', 1, 'Baja', NULL),
(60, 57, 'Reporte aceptado', 12, 12, '2025-05-20 19:37:28', 2, 'Baja', NULL),
(61, 57, 'Reporte Rechazado', 12, NULL, '2025-05-20 19:37:32', 1, 'Baja', NULL),
(62, 57, 'Reporte aceptado', 12, 12, '2025-05-20 19:37:34', 2, 'Baja', NULL),
(63, 57, 'Cambio de prioridad', NULL, 12, '2025-05-21 18:49:41', 2, 'Media', NULL),
(64, 57, 'Cambio de prioridad', NULL, 12, '2025-05-22 18:49:29', 2, 'Alta', NULL),
(82, 66, 'Creación de reporte', 11, NULL, '2025-05-27 13:49:37', 1, 'Baja', NULL),
(83, 66, 'Reporte aceptado', 12, 12, '2025-05-27 13:53:22', 2, 'Baja', NULL),
(84, 66, 'Reporte rechazado', 12, NULL, '2025-05-27 15:17:35', 1, 'Baja', 'Pq si'),
(85, 57, 'Reporte rechazado', 12, NULL, '2025-05-27 15:26:24', 1, 'Alta', 'Awawa'),
(86, 66, 'Cambio de prioridad', NULL, NULL, '2025-05-28 16:00:41', 1, 'Media', NULL),
(87, 66, 'Cambio de prioridad', NULL, NULL, '2025-05-29 17:39:29', 1, 'Alta', NULL),
(88, 67, 'Creación de reporte', 11, NULL, '2025-05-30 15:25:51', 1, 'Baja', NULL),
(89, 66, 'Reporte aceptado', 12, 12, '2025-05-30 15:34:54', 2, 'Alta', NULL),
(90, 66, 'Reporte rechazado', 12, NULL, '2025-05-30 15:43:38', 1, 'Alta', 'Pq si'),
(91, 67, 'Reporte asignado por el administrador', 1, 12, '2025-05-30 15:43:54', 2, 'Baja', NULL),
(92, 66, 'Reporte aceptado por el técnico', 12, 12, '2025-05-30 15:48:43', 2, 'Alta', NULL),
(93, 68, 'Creación de reporte', 11, NULL, '2025-05-30 20:02:06', 1, 'Baja', NULL),
(94, 69, 'Creación de reporte', 11, NULL, '2025-05-30 20:03:00', 1, 'Baja', NULL),
(95, 69, 'Reporte asignado por el administrador', 1, 12, '2025-05-30 20:04:19', 2, 'Baja', NULL);

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
-- Estructura de tabla para la tabla `tipo_actividad`
--

CREATE TABLE `tipo_actividad` (
  `id_tipo_actividad` int(11) NOT NULL,
  `tipo_actividad` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipo_actividad`
--

INSERT INTO `tipo_actividad` (`id_tipo_actividad`, `tipo_actividad`) VALUES
(1, 'Mantenimiento'),
(2, 'Revisión'),
(3, 'Apoyo'),
(4, 'Configuración'),
(5, 'Actualización'),
(6, 'Instalación');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_falla`
--

CREATE TABLE `tipo_falla` (
  `id_tipo_falla` int(11) NOT NULL,
  `tipo_falla` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipo_falla`
--

INSERT INTO `tipo_falla` (`id_tipo_falla`, `tipo_falla`) VALUES
(1, 'Equipo'),
(2, 'Red'),
(3, 'Impresora');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id_usuario` int(11) NOT NULL,
  `password` varchar(60) NOT NULL,
  `id_rol` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id_usuario`, `password`, `id_rol`) VALUES
(1, '$2y$10$POIYCiDptQy45VWTAApJVeWkYthozSHlGBaJQ0aeaCCtW6jaXvdNe', 1),
(10, '$2y$10$wWOe1FwHzJCT2LV90C95ue57n1DvUDW6NKsXIf.QUi9DoSY/NtXwO', 4),
(11, '$2y$10$jccCwISlz5ognXzwaH.iI.Y6k9FvPneGjKKnIYbkIc3YpdO3p/GkC', 2),
(12, '$2y$10$quhDQN59uSvhKCvLdpFSP.6eig.FI1dRzdraopLRXBQMET5jK1QM.', 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario_pregunta`
--

CREATE TABLE `usuario_pregunta` (
  `id_usuario` int(11) NOT NULL,
  `id_pregunta` int(11) NOT NULL,
  `respuesta` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario_pregunta`
--

INSERT INTO `usuario_pregunta` (`id_usuario`, `id_pregunta`, `respuesta`) VALUES
(1, 2, '$2y$10$HzFqtP458A9mZS.p/7O.O.j04XT/Ga/d1uDIp4EkyxPo6J1UgwvqC'),
(1, 3, '$2y$10$wfQ0Pn.PVwbZofwhXNFPKuPLjUQtrXYmG5VqLHdn/v74h6FLFl9VG'),
(1, 7, '$2y$10$M.FkjBs0mKr.NM7NXUiOX.U6wyHQ9LVL5tQKlDfztAtmWgQn9.0Tq'),
(10, 2, '$2y$10$eHk4bYKntCtsrET5wOgEsOL5hO6PponKObBrm5xdnRVvcJ6GcrR7S'),
(10, 8, '$2y$10$1iLdkztdCkEndG8pj1Y2quYgXKX4Wb9ar/0m2Hw7gM6f20e.Vkt.G'),
(10, 10, '$2y$10$qPkOTHzffxJHi6a7c4NceOhFV8HrFg1MUU.kyyHxBlrJXauRkPx8u'),
(11, 1, '$2y$10$TXPcciMJF..WsXCJ9TgrHeuiP.Q.q/pPfJUONm42y1WyFGyx6.Jpe'),
(11, 2, '$2y$10$vga6rx6f0MxJTxxzwGlQT.hPwkjmK976dwY.mXDe39PutmfbjWOZG'),
(11, 9, '$2y$10$cr0FCbo2J5CbsEfHeOi.zeaTQ5YiKoRg.ymV9sD6Tq5sChRtSLUoW'),
(12, 2, '$2y$10$uh49V2SXH52fn8.WlW.f0O/INNVvgRfwLzyRquscZuTTpsyYcdw/S'),
(12, 4, '$2y$10$s7Qfqldwz3MM5CMe8.w8Vuft6a23R02aNOEovh6gCJTwRFd01/.0.'),
(12, 5, '$2y$10$Fzr2o1PaMn3ao/DZky8XnO134iVIz7aBOilYzbpKOcK8v8QcLEJQO');

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
-- Indices de la tabla `dashboard_config`
--
ALTER TABLE `dashboard_config`
  ADD PRIMARY KEY (`id_usuario_dashboard`) USING BTREE;

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
-- Indices de la tabla `evidencia_reporte_actividades`
--
ALTER TABLE `evidencia_reporte_actividades`
  ADD PRIMARY KEY (`id_evidencia`),
  ADD KEY `id_actividad` (`id_actividad`);

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
-- Indices de la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_notificaciones_tipo` (`tipo`),
  ADD KEY `idx_notificaciones_id_destino` (`id_destino`),
  ADD KEY `idx_notificaciones_id_reporte_asociado` (`id_reporte_asociado`);

--
-- Indices de la tabla `participante_reporte_actividad`
--
ALTER TABLE `participante_reporte_actividad`
  ADD PRIMARY KEY (`id_reporte_actividad`,`cedula`),
  ADD KEY `cedula` (`cedula`);

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
-- Indices de la tabla `preguntas_seguridad`
--
ALTER TABLE `preguntas_seguridad`
  ADD PRIMARY KEY (`id_pregunta`);

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
-- Indices de la tabla `reporte_actividades`
--
ALTER TABLE `reporte_actividades`
  ADD PRIMARY KEY (`id_reporte_actividades`),
  ADD KEY `id_usuario_actividades` (`id_usuario`),
  ADD KEY `id_tipo_reporte` (`id_tipo_reporte`);

--
-- Indices de la tabla `reporte_fallas`
--
ALTER TABLE `reporte_fallas`
  ADD PRIMARY KEY (`id_reporte_fallas`),
  ADD KEY `id_usuario_idx` (`id_usuario`),
  ADD KEY `id_equipo_informatico_idx` (`id_equipo_informatico`),
  ADD KEY `id_estado_reporte_fallas_idx` (`id_estado_reporte_fallas`),
  ADD KEY `id_tecnico_idx` (`tecnico_asignado`),
  ADD KEY `id_reporte_falla_idx` (`id_tipo_falla`),
  ADD KEY `prioridad` (`prioridad`);

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
-- Indices de la tabla `seguimiento`
--
ALTER TABLE `seguimiento`
  ADD PRIMARY KEY (`id_seguimiento`),
  ADD KEY `id_reporte_fallas` (`id_reporte_fallas`),
  ADD KEY `id_estado_reporte` (`id_estado_reporte`),
  ADD KEY `id_usuario_accion` (`id_usuario_accion`),
  ADD KEY `id_tecnico_seguimiento` (`id_tecnico`);

--
-- Indices de la tabla `sexo`
--
ALTER TABLE `sexo`
  ADD PRIMARY KEY (`id_sexo`);

--
-- Indices de la tabla `tipo_actividad`
--
ALTER TABLE `tipo_actividad`
  ADD PRIMARY KEY (`id_tipo_actividad`);

--
-- Indices de la tabla `tipo_falla`
--
ALTER TABLE `tipo_falla`
  ADD PRIMARY KEY (`id_tipo_falla`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_usuario`),
  ADD KEY `id_rol_idx3` (`id_rol`);

--
-- Indices de la tabla `usuario_pregunta`
--
ALTER TABLE `usuario_pregunta`
  ADD PRIMARY KEY (`id_usuario`,`id_pregunta`),
  ADD KEY `id_pregunta_2` (`id_pregunta`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `almacenamiento`
--
ALTER TABLE `almacenamiento`
  MODIFY `id_almacenamiento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de la tabla `departamento`
--
ALTER TABLE `departamento`
  MODIFY `id_departamento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `equipo_informatico`
--
ALTER TABLE `equipo_informatico`
  MODIFY `id_equipo_informatico` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

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
  MODIFY `id_estado_reporte_fallas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `evidencia_reporte_actividades`
--
ALTER TABLE `evidencia_reporte_actividades`
  MODIFY `id_evidencia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT de la tabla `fuente_poder`
--
ALTER TABLE `fuente_poder`
  MODIFY `id_fuente_poder` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT de la tabla `motherboard`
--
ALTER TABLE `motherboard`
  MODIFY `id_motherboard` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT de la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `permisos`
--
ALTER TABLE `permisos`
  MODIFY `id_permisos` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT de la tabla `persona`
--
ALTER TABLE `persona`
  MODIFY `id_persona` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=276;

--
-- AUTO_INCREMENT de la tabla `preguntas_seguridad`
--
ALTER TABLE `preguntas_seguridad`
  MODIFY `id_pregunta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `procesador`
--
ALTER TABLE `procesador`
  MODIFY `id_procesador` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT de la tabla `ram`
--
ALTER TABLE `ram`
  MODIFY `id_ram` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de la tabla `reporte_actividades`
--
ALTER TABLE `reporte_actividades`
  MODIFY `id_reporte_actividades` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=90;

--
-- AUTO_INCREMENT de la tabla `reporte_fallas`
--
ALTER TABLE `reporte_fallas`
  MODIFY `id_reporte_fallas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `seguimiento`
--
ALTER TABLE `seguimiento`
  MODIFY `id_seguimiento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=96;

--
-- AUTO_INCREMENT de la tabla `sexo`
--
ALTER TABLE `sexo`
  MODIFY `id_sexo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `tipo_actividad`
--
ALTER TABLE `tipo_actividad`
  MODIFY `id_tipo_actividad` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `tipo_falla`
--
ALTER TABLE `tipo_falla`
  MODIFY `id_tipo_falla` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

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
-- Filtros para la tabla `dashboard_config`
--
ALTER TABLE `dashboard_config`
  ADD CONSTRAINT `id_usuario_dashboard` FOREIGN KEY (`id_usuario_dashboard`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `equipo_informatico`
--
ALTER TABLE `equipo_informatico`
  ADD CONSTRAINT `id_estado_equipo` FOREIGN KEY (`id_estado_equipo`) REFERENCES `estado_equipo_informatico` (`id_estado_equipo_informatico`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `id_persona` FOREIGN KEY (`id_persona`) REFERENCES `persona` (`id_persona`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `evidencia_reporte_actividades`
--
ALTER TABLE `evidencia_reporte_actividades`
  ADD CONSTRAINT `id_actividad` FOREIGN KEY (`id_actividad`) REFERENCES `reporte_actividades` (`id_reporte_actividades`) ON DELETE CASCADE ON UPDATE CASCADE;

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
-- Filtros para la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  ADD CONSTRAINT `id_reporte_notificaciones` FOREIGN KEY (`id_reporte_asociado`) REFERENCES `reporte_fallas` (`id_reporte_fallas`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `participante_reporte_actividad`
--
ALTER TABLE `participante_reporte_actividad`
  ADD CONSTRAINT `cedula` FOREIGN KEY (`cedula`) REFERENCES `persona` (`cedula`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `id_reporte_actividades` FOREIGN KEY (`id_reporte_actividad`) REFERENCES `reporte_actividades` (`id_reporte_actividades`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `persona`
--
ALTER TABLE `persona`
  ADD CONSTRAINT `fk_departamento` FOREIGN KEY (`id_departamento`) REFERENCES `departamento` (`id_departamento`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_sexo` FOREIGN KEY (`id_sexo`) REFERENCES `sexo` (`id_sexo`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

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
-- Filtros para la tabla `reporte_actividades`
--
ALTER TABLE `reporte_actividades`
  ADD CONSTRAINT `id_tipo_reporte` FOREIGN KEY (`id_tipo_reporte`) REFERENCES `tipo_actividad` (`id_tipo_actividad`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `id_usuario_actividades` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `reporte_fallas`
--
ALTER TABLE `reporte_fallas`
  ADD CONSTRAINT `id_equipo_informatico2` FOREIGN KEY (`id_equipo_informatico`) REFERENCES `equipo_informatico` (`id_equipo_informatico`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `id_estado_reporte_fallas` FOREIGN KEY (`id_estado_reporte_fallas`) REFERENCES `estado_reporte_fallas` (`id_estado_reporte_fallas`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `id_reporte_falla` FOREIGN KEY (`id_tipo_falla`) REFERENCES `tipo_falla` (`id_tipo_falla`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `id_tecnico` FOREIGN KEY (`tecnico_asignado`) REFERENCES `usuario` (`id_usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `id_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `roles_permisos`
--
ALTER TABLE `roles_permisos`
  ADD CONSTRAINT `id_permiso` FOREIGN KEY (`id_permiso`) REFERENCES `permisos` (`id_permisos`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `id_rol` FOREIGN KEY (`id_rol`) REFERENCES `rol` (`id_rol`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `seguimiento`
--
ALTER TABLE `seguimiento`
  ADD CONSTRAINT `id_estado_reporte` FOREIGN KEY (`id_estado_reporte`) REFERENCES `estado_reporte_fallas` (`id_estado_reporte_fallas`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `id_reporte_fallas` FOREIGN KEY (`id_reporte_fallas`) REFERENCES `reporte_fallas` (`id_reporte_fallas`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `id_tecnico_seguimiento` FOREIGN KEY (`id_tecnico`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `id_usuario_accion` FOREIGN KEY (`id_usuario_accion`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `id_rol3` FOREIGN KEY (`id_rol`) REFERENCES `rol` (`id_rol`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `usuario_pregunta`
--
ALTER TABLE `usuario_pregunta`
  ADD CONSTRAINT `id_pregunta_2` FOREIGN KEY (`id_pregunta`) REFERENCES `preguntas_seguridad` (`id_pregunta`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `id_usuario_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

DELIMITER $$
--
-- Eventos
--
CREATE DEFINER=`root`@`localhost` EVENT `actualizar_prioridad_media_habiles` ON SCHEDULE EVERY 1 MINUTE STARTS '2025-05-19 14:46:41' ON COMPLETION NOT PRESERVE ENABLE DO BEGIN
    -- Definir la prioridad media
    SET @prioridad_media = 'Media';

    -- Actualizar los reportes a prioridad Media si han pasado 1 día hábil
    -- y el estado no es '3' (Completado)
    UPDATE reporte_fallas
    SET prioridad = @prioridad_media
    WHERE dias_habiles_transcurridos(fecha_hora_reporte_fallas) >= 1
      AND prioridad NOT IN ('Alta', 'Media')
      AND id_estado_reporte_fallas <> 3; -- ¡Esta es la clave!
END$$

CREATE DEFINER=`root`@`localhost` EVENT `actualizar_prioridad_alta_habiles` ON SCHEDULE EVERY 1 MINUTE STARTS '2025-05-19 14:47:29' ON COMPLETION NOT PRESERVE ENABLE DO BEGIN
    -- Definir la prioridad alta
    SET @prioridad_alta = 'Alta';

    -- Actualizar los reportes a prioridad Alta si han pasado 2 días hábiles
    -- y el estado no es '3' (Completado)
    UPDATE reporte_fallas
    SET prioridad = @prioridad_alta
    WHERE dias_habiles_transcurridos(fecha_hora_reporte_fallas) >= 2
      AND prioridad <> 'Alta'
      AND id_estado_reporte_fallas <> 3; -- ¡Esta es la clave!
END$$

DELIMITER ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
