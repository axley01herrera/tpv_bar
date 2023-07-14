-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 14-07-2023 a las 19:18:11
-- Versión del servidor: 5.7.36
-- Versión de PHP: 7.4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `tpv`
--
CREATE DATABASE IF NOT EXISTS `tpv` DEFAULT CHARACTER SET utf8 COLLATE utf8_spanish_ci;
USE `tpv`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tpv_bar_administrator`
--

DROP TABLE IF EXISTS `tpv_bar_administrator`;
CREATE TABLE IF NOT EXISTS `tpv_bar_administrator` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `password` varchar(999) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `tpv_bar_administrator`
--

INSERT INTO `tpv_bar_administrator` (`id`, `password`) VALUES
(1, '$2y$10$8QMJLv8/8OcaDqtUQviXweYESHxbky3igXBCjyUThAcKpZvkeBdvG');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tpv_bar_category`
--

DROP TABLE IF EXISTS `tpv_bar_category`;
CREATE TABLE IF NOT EXISTS `tpv_bar_category` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `tpv_bar_category`
--

INSERT INTO `tpv_bar_category` (`id`, `name`) VALUES
(1, 'Refrescos'),
(2, 'Tapas'),
(3, 'Cervezas'),
(4, 'Bocadillos'),
(5, 'Vinos Tintos'),
(6, 'Cafés');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tpv_bar_employees`
--

DROP TABLE IF EXISTS `tpv_bar_employees`;
CREATE TABLE IF NOT EXISTS `tpv_bar_employees` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `lastName` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `user` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `clave` varchar(999) COLLATE utf8_spanish_ci NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`user`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `tpv_bar_employees`
--

INSERT INTO `tpv_bar_employees` (`id`, `name`, `lastName`, `user`, `clave`, `status`) VALUES
(1, 'Nicole', 'Herrera', 'nicole', '$2y$10$DWv3CSGPkRTE.dl1UH7qye2KvFGAyzmqBb.9YvXo1XWCLGe95wXfS', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tpv_bar_product`
--

DROP TABLE IF EXISTS `tpv_bar_product`;
CREATE TABLE IF NOT EXISTS `tpv_bar_product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `price` float NOT NULL,
  `description` varchar(999) COLLATE utf8_spanish_ci DEFAULT '',
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '1= activo 0=inactivo',
  `fk_category` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `tpv_bar_product`
--

INSERT INTO `tpv_bar_product` (`id`, `name`, `price`, `description`, `status`, `fk_category`) VALUES
(1, 'Cocacola', 1.5, '', 1, 1),
(2, 'Fanta de Naranja', 1.5, '', 1, 1),
(3, 'Sprite', 1.5, '', 1, 1),
(4, 'Tropical Caña', 1, '', 1, 3),
(5, 'Tropical Botella', 1.5, '', 1, 3),
(6, 'Papas Arrugadas', 5, 'Papa canaria arrugada con mojo verde y mojo picón.', 1, 2),
(7, 'Pepito de Ternera', 4.5, 'Pan baguet, ternera, queso, jamón, huevo y salsa a elegir.', 1, 4),
(8, 'Duque de Medina Joven', 9.9, '', 1, 5),
(9, 'Expresso', 1, '', 1, 6),
(10, 'Americano', 1.5, '', 1, 6);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `tpv_bar_product_view`
-- (Véase abajo para la vista actual)
--
DROP VIEW IF EXISTS `tpv_bar_product_view`;
CREATE TABLE IF NOT EXISTS `tpv_bar_product_view` (
`productID` int(11)
,`productName` varchar(100)
,`productPrice` float
,`productDescription` varchar(999)
,`statusID` int(11)
,`productStatus` varchar(8)
,`categoryID` int(11)
,`categoryName` varchar(100)
);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tpv_bar_tables`
--

DROP TABLE IF EXISTS `tpv_bar_tables`;
CREATE TABLE IF NOT EXISTS `tpv_bar_tables` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tableID` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `dateOpen` timestamp NOT NULL,
  `dateClose` date DEFAULT NULL,
  `status` int(1) NOT NULL DEFAULT '1' COMMENT '0=closed\r\n1=open',
  `fkEmployee` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `tpv_bar_tables`
--

INSERT INTO `tpv_bar_tables` (`id`, `tableID`, `dateOpen`, `dateClose`, `status`, `fkEmployee`) VALUES
(1, 'S1', '2023-07-11 19:39:59', NULL, 1, 1),
(2, 'T5', '2023-07-11 19:42:19', NULL, 1, 1),
(3, 'T50', '2023-07-11 19:47:17', NULL, 1, 1);

--
-- Disparadores `tpv_bar_tables`
--
DROP TRIGGER IF EXISTS `tables_on_insert`;
DELIMITER $$
CREATE TRIGGER `tables_on_insert` BEFORE INSERT ON `tpv_bar_tables` FOR EACH ROW BEGIN
    SET NEW.dateOpen = CURRENT_TIMESTAMP();
END
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `tables_on_update`;
DELIMITER $$
CREATE TRIGGER `tables_on_update` BEFORE UPDATE ON `tpv_bar_tables` FOR EACH ROW BEGIN
    SET NEW.dateClose = CURRENT_TIMESTAMP();
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tpv_bar_ticket`
--

DROP TABLE IF EXISTS `tpv_bar_ticket`;
CREATE TABLE IF NOT EXISTS `tpv_bar_ticket` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fkTable` int(11) NOT NULL,
  `fkProduct` int(11) NOT NULL,
  `fkEmployee` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `tpv_bar_ticket`
--

INSERT INTO `tpv_bar_ticket` (`id`, `fkTable`, `fkProduct`, `fkEmployee`) VALUES
(1, 1, 8, 1),
(2, 1, 3, 1),
(3, 1, 1, 1),
(4, 2, 4, 1),
(5, 2, 7, 1),
(6, 3, 9, 1),
(7, 3, 10, 1),
(8, 3, 4, 1),
(9, 3, 4, 1),
(10, 3, 6, 1),
(11, 3, 6, 1);

-- --------------------------------------------------------

--
-- Estructura para la vista `tpv_bar_product_view`
--
DROP TABLE IF EXISTS `tpv_bar_product_view`;

DROP VIEW IF EXISTS `tpv_bar_product_view`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `tpv_bar_product_view`  AS SELECT `tpv_bar_product`.`id` AS `productID`, `tpv_bar_product`.`name` AS `productName`, `tpv_bar_product`.`price` AS `productPrice`, `tpv_bar_product`.`description` AS `productDescription`, `tpv_bar_product`.`status` AS `statusID`, (case when (`tpv_bar_product`.`status` = 0) then 'Inactivo' when (`tpv_bar_product`.`status` = 1) then 'Activo' end) AS `productStatus`, `tpv_bar_product`.`fk_category` AS `categoryID`, `tpv_bar_category`.`name` AS `categoryName` FROM (`tpv_bar_product` join `tpv_bar_category` on((`tpv_bar_category`.`id` = `tpv_bar_product`.`fk_category`))) ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
