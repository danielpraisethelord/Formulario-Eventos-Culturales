-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3307
-- Tiempo de generación: 08-06-2024 a las 21:13:41
-- Versión del servidor: 11.2.2-MariaDB
-- Versión de PHP: 8.2.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `ordinarioingsoft`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `accesibilidad_comodidad`
--

DROP TABLE IF EXISTS `accesibilidad_comodidad`;
CREATE TABLE IF NOT EXISTS `accesibilidad_comodidad` (
  `idAccesibilidad_Comodidad` int(11) NOT NULL AUTO_INCREMENT,
  `Participante_idParticipante` int(11) DEFAULT NULL,
  `calificacion` enum('Excelente','Buena','Regular','Mala') DEFAULT NULL,
  `dificultades_acceso` text DEFAULT NULL,
  PRIMARY KEY (`idAccesibilidad_Comodidad`),
  KEY `Participante_idParticipante` (`Participante_idParticipante`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `aspectos_culturales`
--

DROP TABLE IF EXISTS `aspectos_culturales`;
CREATE TABLE IF NOT EXISTS `aspectos_culturales` (
  `idAspecto_Culturales` int(11) NOT NULL AUTO_INCREMENT,
  `Participante_idParticipante` int(11) DEFAULT NULL,
  `importancia` enum('Muy importante','Importante','Poco importante','No importante') DEFAULT NULL,
  `elementos_disfrutados` text DEFAULT NULL,
  PRIMARY KEY (`idAspecto_Culturales`),
  KEY `Participante_idParticipante` (`Participante_idParticipante`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `evento`
--

DROP TABLE IF EXISTS `evento`;
CREATE TABLE IF NOT EXISTS `evento` (
  `idEvento` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_evento` varchar(45) DEFAULT NULL,
  `otro_evento` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`idEvento`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `evento`
--

INSERT INTO `evento` (`idEvento`, `nombre_evento`, `otro_evento`) VALUES
(1, 'Guelaguetza', NULL),
(2, 'Día de los Muertos', NULL),
(3, 'Noche de Rábanos', NULL),
(4, 'Semana Santa', NULL),
(5, 'Festival Internacional de Cine de Oaxaca', NULL),
(6, 'Fiesta de la Virgen de la Soledad', NULL),
(7, 'Carnaval de Putla', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `experiencia`
--

DROP TABLE IF EXISTS `experiencia`;
CREATE TABLE IF NOT EXISTS `experiencia` (
  `idExperiencia` int(11) NOT NULL AUTO_INCREMENT,
  `Participante_idParticipante` int(11) DEFAULT NULL,
  `calificacion` enum('Excelente','Buena','Regular','Mala') DEFAULT NULL,
  `explicacion` text DEFAULT NULL,
  PRIMARY KEY (`idExperiencia`),
  KEY `Participante_idParticipante` (`Participante_idParticipante`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `impacto_economico_social`
--

DROP TABLE IF EXISTS `impacto_economico_social`;
CREATE TABLE IF NOT EXISTS `impacto_economico_social` (
  `idImpacto_Economico_Social` int(11) NOT NULL AUTO_INCREMENT,
  `Participante_idParticipante` int(11) DEFAULT NULL,
  `impacto_positivo` enum('Sí','No','No estoy seguro') DEFAULT NULL,
  `beneficios_comunidad` text DEFAULT NULL,
  PRIMARY KEY (`idImpacto_Economico_Social`),
  KEY `Participante_idParticipante` (`Participante_idParticipante`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `organizacion`
--

DROP TABLE IF EXISTS `organizacion`;
CREATE TABLE IF NOT EXISTS `organizacion` (
  `idOrganizacion` int(11) NOT NULL AUTO_INCREMENT,
  `Participante_idParticipante` int(11) DEFAULT NULL,
  `calificacion` enum('Excelente','Buena','Regular','Mala') DEFAULT NULL,
  `aspectos_destacables_problemas` text DEFAULT NULL,
  PRIMARY KEY (`idOrganizacion`),
  KEY `Participante_idParticipante` (`Participante_idParticipante`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `participacion_futuro`
--

DROP TABLE IF EXISTS `participacion_futuro`;
CREATE TABLE IF NOT EXISTS `participacion_futuro` (
  `idParticipacion_Futuro` int(11) NOT NULL AUTO_INCREMENT,
  `Participante_idParticipante` int(11) DEFAULT NULL,
  `asistir_futuro` enum('Sí','No','No estoy seguro') DEFAULT NULL,
  `evento_esperado` text DEFAULT NULL,
  PRIMARY KEY (`idParticipacion_Futuro`),
  KEY `Participante_idParticipante` (`Participante_idParticipante`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `participante`
--

DROP TABLE IF EXISTS `participante`;
CREATE TABLE IF NOT EXISTS `participante` (
  `idParticipante` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `genero` enum('masculino','femeninio') DEFAULT NULL,
  `lugar_residencia` varchar(45) DEFAULT NULL,
  `Usuarios_idUsuarios` int(11) DEFAULT NULL,
  PRIMARY KEY (`idParticipante`),
  KEY `Usuarios_idUsuarios` (`Usuarios_idUsuarios`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `participante_evento`
--

DROP TABLE IF EXISTS `participante_evento`;
CREATE TABLE IF NOT EXISTS `participante_evento` (
  `idParticipante_Evento` int(11) NOT NULL AUTO_INCREMENT,
  `Participante_idParticipante` int(11) DEFAULT NULL,
  `Evento_idEvento` int(11) DEFAULT NULL,
  PRIMARY KEY (`idParticipante_Evento`),
  KEY `Participante_idParticipante` (`Participante_idParticipante`),
  KEY `Evento_idEvento` (`Evento_idEvento`)
) ENGINE=MyISAM AUTO_INCREMENT=31 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recomendaciones_sugerencias`
--

DROP TABLE IF EXISTS `recomendaciones_sugerencias`;
CREATE TABLE IF NOT EXISTS `recomendaciones_sugerencias` (
  `idRecomendaciones_Sugerencias` int(11) NOT NULL AUTO_INCREMENT,
  `Participante_idParticipante` int(11) DEFAULT NULL,
  `recomendar_evento` text DEFAULT NULL,
  `sugerir_mejora` text DEFAULT NULL,
  PRIMARY KEY (`idRecomendaciones_Sugerencias`),
  KEY `Participante_idParticipante` (`Participante_idParticipante`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE IF NOT EXISTS `usuarios` (
  `idUsuarios` int(11) NOT NULL AUTO_INCREMENT,
  `matricula` varchar(9) DEFAULT NULL,
  `contrasena` varchar(255) DEFAULT NULL,
  `form_submitted` int(1) NOT NULL,
  PRIMARY KEY (`idUsuarios`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`idUsuarios`, `matricula`, `contrasena`, `form_submitted`) VALUES
(1, '014432983', '$2y$10$4RkqnRy20yBBnr5aj1rvOenNk5zA9E3g9svZjurb1SgFLcy9T7QIq', 0),
(2, '014432984', '$2y$10$tPWfNIOgg0I6dlxIjLWHVOCAmmoNI0Fnw0icbGSEFy3z6J5TgfMLC', 0),
(12, '014432980', '$2y$10$hSuZA.Xd0pEWnlCXAMn3i.5Ty.Ltc5GmE4R2CBvDo6qY6l/7MCQ2C', 0);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
