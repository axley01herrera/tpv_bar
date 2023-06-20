-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 20-06-2023 a las 17:53:28
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
-- Estructura de tabla para la tabla `category`
--

DROP TABLE IF EXISTS `category`;
CREATE TABLE IF NOT EXISTS `category` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `category`
--

INSERT INTO `category` (`id`, `name`) VALUES
(1, 'Cervezas'),
(2, 'Refrescos'),
(3, 'Bocadillos'),
(4, 'Desayunos'),
(5, 'Vinos Tintos'),
(6, 'Sopas'),
(7, 'Vinos Blancos'),
(8, 'Cavas'),
(9, 'Pastas'),
(10, 'Pizzas'),
(11, 'Ensaladas'),
(12, 'Entrantes'),
(13, 'Tapas'),
(14, 'Postres'),
(15, 'Guarniciones'),
(16, 'Hamburguesas');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `employee`
--

DROP TABLE IF EXISTS `employee`;
CREATE TABLE IF NOT EXISTS `employee` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `lastName` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `user` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `clave` varchar(999) COLLATE utf8_spanish_ci NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`user`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `employee`
--

INSERT INTO `employee` (`id`, `name`, `lastName`, `user`, `clave`, `status`) VALUES
(1, 'Axley', 'Herrera', 'axley01herrera', '$2y$10$4MNy.8jmsWIpV9KQtwhau.26qEQgQCGWT1nupYsR.HVIz4JeW4hdy', 1),
(2, 'Miguel', 'Alfonso', 'migue01alfonso', '', 1),
(3, 'Carlos', 'Gil', 'carlos01gil', '', 1),
(4, 'Roberto', 'Ramose', 'robe01ram', '', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `product`
--

DROP TABLE IF EXISTS `product`;
CREATE TABLE IF NOT EXISTS `product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `price` float NOT NULL,
  `description` varchar(999) COLLATE utf8_spanish_ci DEFAULT '',
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '1= activo 0=inactivo',
  `fk_category` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `product`
--

INSERT INTO `product` (`id`, `name`, `price`, `description`, `status`, `fk_category`) VALUES
(1, 'Tropical Botella', 2, 'Cerveza Canaria', 1, 1),
(2, 'Tropical Jarra', 3, 'Cerveza Canaria', 1, 1),
(3, 'Tropical Caña', 1.25, 'Cerveza Canaria', 1, 1),
(4, 'Tropical Botellín', 1, 'Cerveza Canaria', 1, 1),
(5, 'Cocacola Grande', 2, '', 1, 2),
(6, 'Cocacola Pequeña', 1.5, '', 1, 2),
(7, 'Sprite Pequeño', 1.5, '', 1, 2),
(8, 'Sprite Grande', 2, '', 1, 2),
(9, 'Cocacola Botella', 1.5, '', 1, 2),
(10, 'Cocacola Botellín', 1, '', 1, 2),
(11, 'Fanta Naranja Botella', 1.5, '', 1, 2),
(12, 'Cocacola litro 1/2', 2.5, '', 1, 2),
(13, 'Papas Arrugadas', 5, '', 1, 13),
(14, 'Carne Mechada', 6, 'Carne de Ternera mechada con salsa de tomate.', 1, 13),
(15, 'Puntillas de Calamar', 7.5, 'Puntillas de calamar rebosadas', 1, 12),
(16, 'Gambas al Ajillo', 12.5, 'Gambas en aceite oliva con ajo y champiñones salteados', 1, 12),
(17, 'Duque de Medina Tinto Joven', 9.9, '', 1, 5),
(18, 'Duque de Medina Blanco Joven', 12.9, '', 1, 7),
(19, 'Wopper', 7, '', 1, 16),
(20, 'Arroz Blanco', 5, 'Arroz blanco cocido con sal al punto.', 1, 15),
(21, 'Tarta de Manzana', 4.5, 'Base de nata, con manzana confitada', 1, 14);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `product_view`
-- (Véase abajo para la vista actual)
--
DROP VIEW IF EXISTS `product_view`;
CREATE TABLE IF NOT EXISTS `product_view` (
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
-- Estructura de tabla para la tabla `user_admin`
--

DROP TABLE IF EXISTS `user_admin`;
CREATE TABLE IF NOT EXISTS `user_admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `password` varchar(999) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `user_admin`
--

INSERT INTO `user_admin` (`id`, `password`) VALUES
(1, '$2y$10$8QMJLv8/8OcaDqtUQviXweYESHxbky3igXBCjyUThAcKpZvkeBdvG');

-- --------------------------------------------------------

--
-- Estructura para la vista `product_view`
--
DROP TABLE IF EXISTS `product_view`;

DROP VIEW IF EXISTS `product_view`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `product_view`  AS SELECT `product`.`id` AS `productID`, `product`.`name` AS `productName`, `product`.`price` AS `productPrice`, `product`.`description` AS `productDescription`, `product`.`status` AS `statusID`, (case when (`product`.`status` = 0) then 'Inactivo' when (`product`.`status` = 1) then 'Activo' end) AS `productStatus`, `product`.`fk_category` AS `categoryID`, `category`.`name` AS `categoryName` FROM (`product` join `category` on((`category`.`id` = `product`.`fk_category`))) ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
