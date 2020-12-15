-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 15-12-2020 a las 12:12:38
-- Versión del servidor: 10.4.14-MariaDB
-- Versión de PHP: 7.4.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `practicacomanda`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `menues`
--

CREATE TABLE `menues` (
  `id` int(11) NOT NULL,
  `descripcion` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `categoria` varchar(30) COLLATE utf8_spanish2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `menues`
--

INSERT INTO `menues` (`id`, `descripcion`, `categoria`) VALUES
(1, 'rubia', 'cerveza'),
(2, 'scottish', 'cerveza'),
(3, 'ipa', 'cerveza'),
(4, 'cinar julep', 'tragos'),
(5, 'pinot noir', 'tragos'),
(6, 'malbec', 'tragos'),
(7, 'champagne', 'tragos'),
(8, 'fernet', 'tragos'),
(9, 'milanesa', 'cocina'),
(10, 'asado', 'cocina'),
(11, 'matambre', 'cocina'),
(12, 'flan', 'postres'),
(13, 'isla flotante', 'postres'),
(14, 'vigilante', 'postres'),
(15, 'helado', 'postres');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mesas`
--

CREATE TABLE `mesas` (
  `id_mesa` varchar(5) COLLATE utf8_spanish2_ci NOT NULL,
  `estado` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `encuesta` varchar(150) COLLATE utf8_spanish2_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `mesas`
--

INSERT INTO `mesas` (`id_mesa`, `estado`, `encuesta`, `created_at`, `updated_at`) VALUES
('BDPEK', 'cerrada', 'Puntaje Mesa: 6 Puntaje Restaurant: 10 Puntaje Mozo: 10 Puntaje Cocina: 8', '2020-12-15 14:00:55', '2020-12-15 14:08:25'),
('R2H89', 'Clientes esperando el pedido', '', '2020-12-15 14:27:25', '2020-12-15 14:27:25'),
('RCQP0', 'cerrada', 'Puntaje Mesa: 5 Puntaje Restaurant: 3 Puntaje Mozo: 5 Puntaje Cocina: 9', '2020-12-15 05:25:52', '2020-12-15 13:47:34');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mesas_pedidos`
--

CREATE TABLE `mesas_pedidos` (
  `id` int(11) NOT NULL,
  `id_mesa` varchar(5) COLLATE utf8_spanish2_ci NOT NULL,
  `id_pedido` varchar(5) COLLATE utf8_spanish2_ci NOT NULL,
  `estado` varchar(30) COLLATE utf8_spanish2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `mesas_pedidos`
--

INSERT INTO `mesas_pedidos` (`id`, `id_mesa`, `id_pedido`, `estado`) VALUES
(13, 'RCQP0', '2DAS6', 'listo para servir'),
(14, 'RCQP0', 'CQ7DB', 'listo para servir'),
(17, 'BDPEK', 'AT42R', 'listo para servir'),
(18, 'BDPEK', 'DH5XZ', 'listo para servir'),
(19, 'R2H89', '1BFA9', 'pendiente'),
(20, 'R2H89', 'OUK9X', 'pendiente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `id_pedido` varchar(5) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre_cliente` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `menu_pedido` varchar(50) COLLATE utf8_spanish2_ci NOT NULL,
  `estado` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `id_mesa` varchar(5) COLLATE utf8_spanish2_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`id_pedido`, `nombre_cliente`, `menu_pedido`, `estado`, `id_mesa`, `created_at`, `updated_at`) VALUES
('1BFA9', 'abel', 'asado,ipa,fernet', 'pendiente', 'R2H89', '2020-12-15 14:27:25', '2020-12-15 14:27:25'),
('2DAS6', 'brian', 'milanesa,flan', 'listo para servir', 'RCQP0', '2020-12-15 05:25:52', '2020-12-15 13:01:45'),
('AT42R', 'dai', 'matambre,flan,scottish', 'listo para servir', 'BDPEK', '2020-12-15 14:00:55', '2020-12-15 14:07:31'),
('CQ7DB', 'fer', 'asado,champagne,flan', 'listo para servir', 'RCQP0', '2020-12-15 05:25:52', '2020-12-15 13:01:45'),
('DH5XZ', 'gabi', 'asado,champagne,flan,ipa', 'listo para servir', 'BDPEK', '2020-12-15 14:00:55', '2020-12-15 14:07:31'),
('OUK9X', 'jose', 'asado,champagne,flan,ipa', 'pendiente', 'R2H89', '2020-12-15 14:27:25', '2020-12-15 14:27:25');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sectores`
--

CREATE TABLE `sectores` (
  `id_preparacion` int(11) NOT NULL,
  `id_encargado` int(11) NOT NULL,
  `id_pedido` varchar(5) COLLATE utf8_spanish2_ci NOT NULL,
  `item_sector` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `estado` varchar(30) COLLATE utf8_spanish2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `sectores`
--

INSERT INTO `sectores` (`id_preparacion`, `id_encargado`, `id_pedido`, `item_sector`, `estado`) VALUES
(21, 4, '2DAS6', 'cocina', 'listo para servir'),
(22, 4, '2DAS6', 'postres', 'listo para servir'),
(23, 4, 'CQ7DB', 'cocina', 'listo para servir'),
(24, 5, 'CQ7DB', 'tragos', 'listo para servir'),
(25, 4, 'CQ7DB', 'postres', 'listo para servir'),
(26, 4, 'AT42R', 'cocina', 'listo para servir'),
(27, 4, 'AT42R', 'postres', 'listo para servir'),
(28, 6, 'AT42R', 'cerveza', 'listo para servir'),
(29, 4, 'DH5XZ', 'cocina', 'listo para servir'),
(30, 5, 'DH5XZ', 'tragos', 'listo para servir'),
(31, 4, 'DH5XZ', 'postres', 'listo para servir'),
(32, 6, 'DH5XZ', 'cerveza', 'listo para servir'),
(33, 4, '1BFA9', 'cocina', 'pendiente'),
(34, 6, '1BFA9', 'cerveza', 'pendiente'),
(35, 5, '1BFA9', 'tragos', 'pendiente'),
(36, 4, 'OUK9X', 'cocina', 'pendiente'),
(37, 5, 'OUK9X', 'tragos', 'pendiente'),
(38, 4, 'OUK9X', 'postres', 'pendiente'),
(39, 6, 'OUK9X', 'cerveza', 'pendiente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `nombre` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `clave` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `tipo_usuario` varchar(30) COLLATE utf8_spanish2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nombre`, `clave`, `tipo_usuario`) VALUES
(1, 'socio1', 'socio1admin', 'socio'),
(2, 'socio2', 'socio2admin', 'socio'),
(3, 'socio3', 'socio3admin', 'socio'),
(4, 'cocinero', 'cocineropiola', 'cocinero'),
(5, 'bartender', 'barman10', 'bartender'),
(6, 'cervecero', 'duffman', 'cervecero'),
(7, 'alfred', 'mozomaestro', 'mozo');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `menues`
--
ALTER TABLE `menues`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `mesas`
--
ALTER TABLE `mesas`
  ADD PRIMARY KEY (`id_mesa`);

--
-- Indices de la tabla `mesas_pedidos`
--
ALTER TABLE `mesas_pedidos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id_pedido`);

--
-- Indices de la tabla `sectores`
--
ALTER TABLE `sectores`
  ADD PRIMARY KEY (`id_preparacion`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `menues`
--
ALTER TABLE `menues`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `mesas_pedidos`
--
ALTER TABLE `mesas_pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `sectores`
--
ALTER TABLE `sectores`
  MODIFY `id_preparacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
