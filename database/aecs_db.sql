-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 16-05-2024 a las 22:06:44
-- Versión del servidor: 8.0.30
-- Versión de PHP: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `aecs_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id` bigint UNSIGNED NOT NULL,
  `razon_social` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dir` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fono` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `correo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nivel` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_registro` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `configuracions`
--

CREATE TABLE `configuracions` (
  `id` bigint UNSIGNED NOT NULL,
  `nombre_sistema` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alias` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `razon_social` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ciudad` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dir` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fono` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `correo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `web` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `actividad` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `configuracions`
--

INSERT INTO `configuracions` (`id`, `nombre_sistema`, `alias`, `razon_social`, `ciudad`, `dir`, `fono`, `correo`, `web`, `actividad`, `logo`, `created_at`, `updated_at`) VALUES
(1, 'AECS', 'AECS', 'EMPRESA AECS S.A.', 'LA PAZ', 'LOS OLIVOS', '2222222', 'ACES@GMAIL.COM', 'AECS.COM', 'ACTIVIDAD', 'logo.webp', '2024-05-13 18:36:25', '2024-05-13 18:36:25');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_accions`
--

CREATE TABLE `historial_accions` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `accion` varchar(155) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `datos_original` text COLLATE utf8mb4_unicode_ci,
  `datos_nuevo` text COLLATE utf8mb4_unicode_ci,
  `modulo` varchar(155) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `historial_accions`
--

INSERT INTO `historial_accions` (`id`, `user_id`, `accion`, `descripcion`, `datos_original`, `datos_nuevo`, `modulo`, `fecha`, `hora`, `created_at`, `updated_at`) VALUES
(1, 1, 'CREACIÓN', 'EL USUARIO admin REGISTRO UN PERSONAL TÉCNICO', 'id: 2<br/>nombre: JUAN<br/>paterno: PERES<br/>materno: MAMANI<br/>ci: 1111<br/>ci_exp: LP<br/>estado_civil: SOLTERO<br/>fecha_nac: 1999-01-01<br/>cel: 77777<br/>domicilio: LOS OLIVOS<br/>especialidad: ESPECIALIDAD #1<br/>record: RECORD<br/>hoja_vida: 1715896355_2.pdf<br/>foto: 1715896355_2.png<br/>fecha_registro: 2024-05-16<br/>created_at: 2024-05-16 17:52:35<br/>updated_at: 2024-05-16 17:52:35<br/>', NULL, 'PERSONAL TÉCNICO', '2024-05-16', '17:52:35', '2024-05-16 21:52:35', '2024-05-16 21:52:35'),
(2, 1, 'CREACIÓN', 'EL USUARIO admin REGISTRO UN PERSONAL TÉCNICO', 'id: 3<br/>nombre: ASD<br/>paterno: ASD<br/>materno: ASD<br/>ci: 123<br/>ci_exp: LP<br/>estado_civil: CASADO<br/>fecha_nac: 1999-01-01<br/>cel: 5434535<br/>domicilio: LOS OLIVOS<br/>especialidad: ESPECIALDIAD<br/>record: <br/>hoja_vida: <br/>foto: <br/>fecha_registro: 2024-05-16<br/>created_at: 2024-05-16 18:03:18<br/>updated_at: 2024-05-16 18:03:18<br/>', NULL, 'PERSONAL TÉCNICO', '2024-05-16', '18:03:18', '2024-05-16 22:03:18', '2024-05-16 22:03:18'),
(3, 1, 'MODIFICACIÓN', 'EL USUARIO admin MODIFICÓ UN PERSONAL TÉCNICO', 'id: 3<br/>nombre: ASD<br/>paterno: ASD<br/>materno: ASD<br/>ci: 123<br/>ci_exp: LP<br/>estado_civil: CASADO<br/>fecha_nac: 1999-01-01<br/>cel: 5434535<br/>domicilio: LOS OLIVOS<br/>especialidad: ESPECIALDIAD<br/>record: <br/>hoja_vida: <br/>foto: <br/>fecha_registro: 2024-05-16<br/>created_at: 2024-05-16 18:03:18<br/>updated_at: 2024-05-16 18:03:18<br/>', 'id: 3<br/>nombre: MARIO<br/>paterno: PAREDES<br/>materno: MARTINEZ<br/>ci: 2222<br/>ci_exp: CB<br/>estado_civil: DIVORCIADO<br/>fecha_nac: 2000-01-01<br/>cel: 6677777<br/>domicilio: LOS OLIVOS PEDREGALES<br/>especialidad: ESPECIALDIAD #2<br/>record: RECORD 2<br/>hoja_vida: 1715897073_3.pdf<br/>foto: 1715897073_3.jpg<br/>fecha_registro: 2024-05-16<br/>created_at: 2024-05-16 18:03:18<br/>updated_at: 2024-05-16 18:04:33<br/>', 'PERSONAL TÉCNICO', '2024-05-16', '18:04:33', '2024-05-16 22:04:33', '2024-05-16 22:04:33'),
(4, 1, 'ELIMINACIÓN', 'EL USUARIO admin ELIMINÓ UN PERSONAL TÉCNICO', 'id: 3<br/>nombre: MARIO<br/>paterno: PAREDES<br/>materno: MARTINEZ<br/>ci: 2222<br/>ci_exp: CB<br/>estado_civil: DIVORCIADO<br/>fecha_nac: 2000-01-01<br/>cel: 6677777<br/>domicilio: LOS OLIVOS PEDREGALES<br/>especialidad: ESPECIALDIAD #2<br/>record: RECORD 2<br/>hoja_vida: 1715897073_3.pdf<br/>foto: 1715897073_3.jpg<br/>fecha_registro: 2024-05-16<br/>created_at: 2024-05-16 18:03:18<br/>updated_at: 2024-05-16 18:04:33<br/>', NULL, 'PERSONAL TÉCNICO', '2024-05-16', '18:05:14', '2024-05-16 22:05:14', '2024-05-16 22:05:14'),
(5, 1, 'ELIMINACIÓN', 'EL USUARIO admin ELIMINÓ UN PERSONAL TÉCNICO', 'id: 2<br/>nombre: JUAN<br/>paterno: PERES<br/>materno: MAMANI<br/>ci: 1111<br/>ci_exp: LP<br/>estado_civil: SOLTERO<br/>fecha_nac: 1999-01-01<br/>cel: 77777<br/>domicilio: LOS OLIVOS<br/>especialidad: ESPECIALIDAD #1<br/>record: RECORD<br/>hoja_vida: 1715896355_2.pdf<br/>foto: 1715896355_2.png<br/>fecha_registro: 2024-05-16<br/>created_at: 2024-05-16 17:52:35<br/>updated_at: 2024-05-16 17:52:35<br/>', NULL, 'PERSONAL TÉCNICO', '2024-05-16', '18:05:21', '2024-05-16 22:05:21', '2024-05-16 22:05:21'),
(6, 1, 'CREACIÓN', 'EL USUARIO admin REGISTRO UN PERSONAL TÉCNICO', 'id: 1<br/>nombre: JUAN<br/>paterno: PERES<br/>materno: MAMANI<br/>ci: 1111<br/>ci_exp: LP<br/>estado_civil: SOLTERO<br/>fecha_nac: 1999-01-01<br/>cel: 7777777<br/>domicilio: LOS OLIVOS<br/>especialidad: ESPECIALIDAD #2<br/>record: RECORD<br/>hoja_vida: 1715897155_1.pdf<br/>foto: 1715897155_1.jpg<br/>fecha_registro: 2024-05-16<br/>created_at: 2024-05-16 18:05:55<br/>updated_at: 2024-05-16 18:05:55<br/>', NULL, 'PERSONAL TÉCNICO', '2024-05-16', '18:05:55', '2024-05-16 22:05:55', '2024-05-16 22:05:55');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_24_000000_create_personals_table', 1),
(2, '2014_10_24_000001_create_users_table', 1),
(3, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(4, '2024_01_31_165641_create_configuracions_table', 1),
(5, '2024_02_02_205431_create_historial_accions_table', 1),
(6, '2024_05_16_110657_create_clientes_table', 2),
(7, '2024_05_16_110754_create_solicitud_atencions_table', 2),
(8, '2024_05_16_110830_create_servicios_table', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personals`
--

CREATE TABLE `personals` (
  `id` bigint UNSIGNED NOT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `paterno` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `materno` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ci` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ci_exp` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `estado_civil` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha_nac` date NOT NULL,
  `cel` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `domicilio` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `especialidad` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `record` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hoja_vida` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `foto` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_registro` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `personals`
--

INSERT INTO `personals` (`id`, `nombre`, `paterno`, `materno`, `ci`, `ci_exp`, `estado_civil`, `fecha_nac`, `cel`, `domicilio`, `especialidad`, `record`, `hoja_vida`, `foto`, `fecha_registro`, `created_at`, `updated_at`) VALUES
(1, 'JUAN', 'PERES', 'MAMANI', '1111', 'LP', 'SOLTERO', '1999-01-01', '7777777', 'LOS OLIVOS', 'ESPECIALIDAD #2', 'RECORD', '1715897155_1.pdf', '1715897155_1.jpg', '2024-05-16', '2024-05-16 22:05:55', '2024-05-16 22:05:55');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicios`
--

CREATE TABLE `servicios` (
  `id` bigint UNSIGNED NOT NULL,
  `cod` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cliente_id` bigint UNSIGNED NOT NULL,
  `personal_id` bigint UNSIGNED NOT NULL,
  `fecha` date NOT NULL,
  `hora_ini` time NOT NULL,
  `hora_fin` time NOT NULL,
  `total_horas` double NOT NULL,
  `ubicacion` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `marca` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `modelo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nro_serie` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nro_parte` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nro_activo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tipo_servicio` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `foto` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `problema` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `trabajo_realizado` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `partes` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo_trabajo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha_registro` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `solicitud_atencions`
--

CREATE TABLE `solicitud_atencions` (
  `id` bigint UNSIGNED NOT NULL,
  `cliente_id` bigint UNSIGNED NOT NULL,
  `personal_id` bigint UNSIGNED NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `estado` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha_registro` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `personal_id` bigint UNSIGNED DEFAULT NULL,
  `usuario` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `acceso` int NOT NULL DEFAULT '1',
  `foto` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_registro` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `personal_id`, `usuario`, `password`, `tipo`, `acceso`, `foto`, `fecha_registro`, `created_at`, `updated_at`) VALUES
(1, NULL, 'admin', '$2y$12$9I.ZrE/7q.PdqX5QlNKXDOLOUaiaPdkttXTIm7Z2KWajmoaEc4ahe', 'GERENTE TÉCNICO', 1, NULL, '2024-05-13', '2024-05-13 18:36:25', '2024-05-13 18:36:25');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `configuracions`
--
ALTER TABLE `configuracions`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `historial_accions`
--
ALTER TABLE `historial_accions`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `personals`
--
ALTER TABLE `personals`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indices de la tabla `servicios`
--
ALTER TABLE `servicios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `servicios_cod_unique` (`cod`),
  ADD KEY `servicios_cliente_id_foreign` (`cliente_id`),
  ADD KEY `servicios_personal_id_foreign` (`personal_id`);

--
-- Indices de la tabla `solicitud_atencions`
--
ALTER TABLE `solicitud_atencions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `solicitud_atencions_cliente_id_foreign` (`cliente_id`),
  ADD KEY `solicitud_atencions_personal_id_foreign` (`personal_id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_usuario_unique` (`usuario`),
  ADD KEY `users_personal_id_foreign` (`personal_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `configuracions`
--
ALTER TABLE `configuracions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `historial_accions`
--
ALTER TABLE `historial_accions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `personals`
--
ALTER TABLE `personals`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `servicios`
--
ALTER TABLE `servicios`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `solicitud_atencions`
--
ALTER TABLE `solicitud_atencions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `servicios`
--
ALTER TABLE `servicios`
  ADD CONSTRAINT `servicios_cliente_id_foreign` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`),
  ADD CONSTRAINT `servicios_personal_id_foreign` FOREIGN KEY (`personal_id`) REFERENCES `personals` (`id`);

--
-- Filtros para la tabla `solicitud_atencions`
--
ALTER TABLE `solicitud_atencions`
  ADD CONSTRAINT `solicitud_atencions_cliente_id_foreign` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`),
  ADD CONSTRAINT `solicitud_atencions_personal_id_foreign` FOREIGN KEY (`personal_id`) REFERENCES `personals` (`id`);

--
-- Filtros para la tabla `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_personal_id_foreign` FOREIGN KEY (`personal_id`) REFERENCES `personals` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
