-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 15-06-2025 a las 03:13:01
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
-- Base de datos: `aplicacioninventario`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria`
--

CREATE TABLE `categoria` (
  `categoria_id` int(10) NOT NULL,
  `categoria_nombre` varchar(50) NOT NULL,
  `categoria_ubicacion` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categoria`
--

INSERT INTO `categoria` (`categoria_id`, `categoria_nombre`, `categoria_ubicacion`) VALUES
(4, 'juegos de mesa Hasbro', 'piso 12'),
(5, 'Accesorios PC', 'Piso 22');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `log`
--

CREATE TABLE `log` (
  `log_id` int(200) NOT NULL,
  `log_mensaje` varchar(1000) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `log`
--

INSERT INTO `log` (`log_id`, `log_mensaje`) VALUES
(1, 'Actualizacion de productos, nuevo productoPrueba1fue agregado'),
(2, 'Actualizacion de productos, nuevo productoGaming2fue agregado'),
(3, 'Actualizacion de productos, nuevo productojuegos Familia3fue agregado'),
(4, 'Actualizacion de productos, productoGaming2fue eliminado'),
(5, 'Actualizacion de productos, productoPrueba1fue eliminado'),
(6, 'Actualizacion de productos, productojuegos Familia3fue eliminado'),
(7, 'Actualizacion de productos, nuevo productoJuego de mesa4fue agregado'),
(8, 'Actualizacion de productos, productoJuego de mesa4fue eliminado'),
(9, 'Actualizacion de productos, nuevo producto2345fue agregado'),
(10, 'Actualizacion de productos, producto2345fue eliminado'),
(11, 'Actualizacion de productos, nuevo productoJuegos de mesa6fue agregado'),
(12, 'Actualizacion de productos, productoJuegos de mesa6fue eliminado'),
(13, 'Actualizacion de productos, nuevo productoaudiculares7fue agregado'),
(14, 'Actualizacion de productos, nuevo productoPrueba8fue agregado'),
(15, 'Actualizacion de productos, productoaudiculares7fue eliminado'),
(16, 'Actualizacion de productos, productoPrueba8fue eliminado'),
(17, 'Actualizacion de productos, nuevo productoPrueba19fue agregado'),
(18, 'Actualizacion de productos, nuevo productoPrueba10fue agregado'),
(19, 'Actualizacion de productos, productoPrueba10fue eliminado'),
(20, 'Actualizacion de productos, productoPrueba19fue eliminado'),
(21, 'Actualizacion de productos, nuevo producto123423111fue agregado'),
(22, 'Actualizacion de productos, nuevo productoJuego de mesa1fue agregado'),
(23, 'Actualizacion de productos, productoJuego de mesa1fue actualizado aJuego de mesa1fue agregado'),
(24, 'Actualizacion de productos, productoJuego de mesa1fue actualizado aJuego de mesa1fue agregado'),
(25, 'Actualizacion de productos, productoJuego de mesa1fue actualizado aJuego de mesa1fue agregado'),
(26, 'Actualizacion de productos, productoJuego de mesa1fue actualizado aJuego de mesa (Hasbro)1fue agregado'),
(27, 'Actualizacion de productos, productoJuego de mesa (Hasbro)1fue actualizado aJuego de mesa (Hasbro)1fue agregado'),
(28, 'Actualizacion de productos, productoJuego de mesa (Hasbro)1fue actualizado aJuego de mesa (Hasbro)1fue agregado'),
(29, 'Actualizacion de productos, productoJuego de mesa (Hasbro)1fue actualizado aJuego de mesa (Hasbro)1fue agregado'),
(30, 'Actualizacion de productos, productoJuego de mesa (Hasbro)1fue actualizado aJuego de mesa (Hasbro)1fue agregado'),
(31, 'Actualizacion de productos, productoJuego de mesa (Hasbro)1fue actualizado aJuego de mesa (Hasbro)1fue agregado'),
(32, 'Actualizacion de productos, productoJuego de mesa (Hasbro)1fue actualizado aJuego de mesa (Hasbro)1fue agregado'),
(33, 'Actualizacion de productos, productoJuego de mesa (Hasbro)1fue actualizado aJuego de mesa1fue agregado'),
(34, 'Actualizacion de productos, nuevo productoAudiculares2fue agregado'),
(35, 'Actualizacion de productos, nuevo productomouse Lenovo gaming3fue agregado'),
(36, 'Actualizacion de productos, productomouse Lenovo gaming3fue actualizado amouse Lenovo gaming3fue agregado'),
(37, 'Actualizacion de productos, productomouse Lenovo gaming3fue actualizado amouse Lenovo gaming3fue agregado'),
(38, 'Actualizacion de productos, productoAudiculares2fue actualizado aAudiculares2fue agregado'),
(39, 'Actualizacion de productos, productoAudiculares2fue actualizado aAudiculares2fue agregado'),
(40, 'Actualizacion de productos, productoAudiculares2fue actualizado aAudiculares2fue agregado'),
(41, 'Actualizacion de productos, productoAudiculares2fue actualizado aAudiculares2fue agregado'),
(42, 'Actualizacion de productos, productoJuego de mesa1fue actualizado aJuego de mesa1fue agregado'),
(43, 'Actualizacion de productos, productoJuego de mesa1fue actualizado aJuego de mesa1fue agregado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE `producto` (
  `producto_id` int(20) NOT NULL,
  `producto_codigo` varchar(70) NOT NULL,
  `producto_nombre` varchar(70) NOT NULL,
  `producto_precio` decimal(30,2) NOT NULL,
  `producto_stock` int(25) NOT NULL,
  `producto_foto` varchar(500) NOT NULL,
  `categoria_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`producto_id`, `producto_codigo`, `producto_nombre`, `producto_precio`, `producto_stock`, `producto_foto`, `categoria_id`, `usuario_id`) VALUES
(1, '123123', 'Juego de mesa', 123123123.00, 1223, 'Juego_de_mesa_568.png', 4, 1),
(2, '12312', 'Audiculares', 123.00, 123, 'Audiculares_201.png', 5, 1),
(3, '1232132', 'mouse Lenovo gaming', 12222222.00, 12, 'mouse_Lenovo_gaming_210.png', 5, 1);

--
-- Disparadores `producto`
--
DELIMITER $$
CREATE TRIGGER `tr_producto_Update` AFTER UPDATE ON `producto` FOR EACH ROW BEGIN 
    	INSERT INTO log (log_mensaje)
        VALUES (CONCAT('Actualizacion de productos, producto', OLD.producto_nombre, new.producto_id, 'fue actualizado a', new.producto_nombre, new.producto_id, 'fue agregado'));
    END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `tr_producto_delete` AFTER DELETE ON `producto` FOR EACH ROW BEGIN 
    	INSERT INTO log (log_mensaje)
        VALUES (CONCAT('Actualizacion de productos, producto', OLD.producto_nombre, OLD.producto_id, 'fue eliminado'));
    END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `tr_producto_insert` AFTER INSERT ON `producto` FOR EACH ROW BEGIN 
    	INSERT INTO log (log_mensaje)
        VALUES (CONCAT('Actualizacion de productos, nuevo producto', new.producto_nombre, new.producto_id, 'fue agregado'));
    END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `usuario_id` int(10) NOT NULL,
  `usuario_nombre` varchar(40) NOT NULL,
  `usuario_apellido` varchar(40) NOT NULL,
  `usuario_usuario` varchar(20) NOT NULL,
  `usuario_clave` varchar(100) NOT NULL,
  `usuario_email` varchar(70) NOT NULL,
  `Clave_cript` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`usuario_id`, `usuario_nombre`, `usuario_apellido`, `usuario_usuario`, `usuario_clave`, `usuario_email`, `Clave_cript`) VALUES
(1, 'Nixon', 'Suarez', 'nixsua', '1234567', 'nixonsuarez@gmail.com', '$2y$10$ljD.cRlMzsMzrDjMWXSZuuFJ5uovTBE/PRxEVKTzOKypg2witLkIe'),
(4, 'Nicole', 'Suar', 'NicoleSua', '1234567', 'NicoleSua@gmail.com', '$2y$10$zMyotvD84b4dDJCulo81PO2pAQzoHRZgg7uc6vhjSh7bjmCHmGzQ6'),
(7, 'Gigo', 'gigo', 'Gigo', '1234567', 'Gigo@gmail.com', '$2y$10$tE4WHtBbfFyRB1fzPbh4M.N51W2Y9B0OTad31n5oG0GKlJleyPf2.'),
(8, 'Patricia', 'Mejía', 'PaMej', '1234567', 'PaMej@gmail.com', '$2y$10$.Y6uHh6hNYb/3cigm2y6gO0Jgta/8awRN5JSHmEGoVuawlY3lGQgS');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`categoria_id`);

--
-- Indices de la tabla `log`
--
ALTER TABLE `log`
  ADD PRIMARY KEY (`log_id`);

--
-- Indices de la tabla `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`producto_id`),
  ADD KEY `categoria_id` (`categoria_id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`usuario_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categoria`
--
ALTER TABLE `categoria`
  MODIFY `categoria_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `log`
--
ALTER TABLE `log`
  MODIFY `log_id` int(200) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
  MODIFY `producto_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `usuario_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `producto`
--
ALTER TABLE `producto`
  ADD CONSTRAINT `producto_ibfk_1` FOREIGN KEY (`categoria_id`) REFERENCES `categoria` (`categoria_id`),
  ADD CONSTRAINT `producto_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`usuario_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
