-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 02-04-2017 a las 02:49:08
-- Versión del servidor: 10.1.19-MariaDB
-- Versión de PHP: 7.0.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `test_inv`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `invento_categories`
--

CREATE TABLE `invento_categories` (
  `id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `place` varchar(200) NOT NULL,
  `descrp` varchar(400) NOT NULL,
  `date_added` date DEFAULT '0000-00-00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `invento_items`
--

CREATE TABLE `invento_items` (
  `id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `descrp` varchar(400) NOT NULL,
  `category` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `price` decimal(15,2) NOT NULL,
  `date_added` date DEFAULT '0000-00-00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `invento_logs`
--

CREATE TABLE `invento_logs` (
  `id` int(11) NOT NULL,
  `type` int(1) NOT NULL,
  `item` int(11) NOT NULL,
  `fromqty` int(11) NOT NULL,
  `toqty` int(11) NOT NULL,
  `fromprice` decimal(15,2) NOT NULL,
  `toprice` decimal(15,2) NOT NULL,
  `date_added` date DEFAULT '0000-00-00',
  `user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `invento_settings`
--

CREATE TABLE `invento_settings` (
  `id` int(11) NOT NULL,
  `name` varchar(80) NOT NULL,
  `val` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `invento_settings`
--

INSERT INTO `invento_settings` (`id`, `name`, `val`) VALUES
(1, 'site_title', 'Invento - %page%'),
(2, 'site_logo', 'media/img/logo3x.png'),
(3, 'allow_userchange', 'y'),
(4, 'allow_namechange', 'y'),
(5, 'allow_emailchange', 'y'),
(6, 'installed', 'n');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `invento_users`
--

CREATE TABLE `invento_users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` char(32) NOT NULL,
  `name` varchar(300) NOT NULL,
  `email` varchar(255) NOT NULL,
  `role` int(1) NOT NULL,
  `date_added` date DEFAULT '0000-00-00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `invento_users`
--

INSERT INTO `invento_users` (`id`, `username`, `password`, `name`, `email`, `role`, `date_added`) VALUES
(1, 'admin', 'e10adc3949ba59abbe56e057f20f883e', 'Obed Alvarado', 'admin@admin.com', 1, '2017-04-01');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `invento_categories`
--
ALTER TABLE `invento_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `invento_items`
--
ALTER TABLE `invento_items`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `invento_logs`
--
ALTER TABLE `invento_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `invento_settings`
--
ALTER TABLE `invento_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `invento_users`
--
ALTER TABLE `invento_users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `invento_categories`
--
ALTER TABLE `invento_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `invento_items`
--
ALTER TABLE `invento_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `invento_logs`
--
ALTER TABLE `invento_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `invento_settings`
--
ALTER TABLE `invento_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT de la tabla `invento_users`
--
ALTER TABLE `invento_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
