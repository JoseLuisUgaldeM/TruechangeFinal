-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         10.4.32-MariaDB - mariadb.org binary distribution
-- SO del servidor:              Win64
-- HeidiSQL Versión:             12.11.0.7065
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Volcando estructura de base de datos para bdtruechange


-- Volcando estructura para tabla bdtruechange.articulos
CREATE TABLE IF NOT EXISTS `articulos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) NOT NULL,
  `titulo` varchar(150) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `categoria` varchar(100) DEFAULT NULL,
  `estado` enum('nuevo','como nuevo','usado','deteriorado') DEFAULT 'usado',
  `fecha_publicacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `estadoArticulo` enum('disponible','reservado','vendido') DEFAULT 'disponible',
  `receptor_id` int(11) DEFAULT NULL,
  `cambio` varchar(100) DEFAULT 'Escucho posibles cambios',
  `fecha_venta` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `usuario_id` (`usuario_id`),
  KEY `fk_comprador` (`receptor_id`) USING BTREE,
  CONSTRAINT `articulos_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  CONSTRAINT `receptor` FOREIGN KEY (`receptor_id`) REFERENCES `usuarios` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=109 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla bdtruechange.articulos: ~4 rows (aproximadamente)
INSERT INTO `articulos` (`id`, `usuario_id`, `titulo`, `descripcion`, `categoria`, `estado`, `fecha_publicacion`, `estadoArticulo`, `receptor_id`, `cambio`, `fecha_venta`) VALUES
	(105, 36, 'Vehículo todoterreno', 'Vehículo todoterreno en buen estado. 112000kms. Color blanco.', 'Coches', 'usado', '2026-04-22 15:56:54', 'disponible', NULL, 'Coche mas pequeño', NULL),
	(106, 36, 'Bicicleta de montaña', 'Bicicleta de montaña en muy buen estado. Muy cuidada. Se cambia por no utilizar.', 'Bicicletas', 'como nuevo', '2026-04-22 16:02:04', 'disponible', NULL, 'Patinete eléctrico', NULL),
	(107, 39, 'Reloj omega', 'Reloj nuevo. Se cambia por no usar.', 'Coleccionismo', 'nuevo', '2026-04-22 16:13:22', 'reservado', NULL, 'Ordenador portatil', NULL),
	(108, 39, 'Ordenador portátil', 'Ordenador portátil con bastante uso. Tiene marcas y algún roce. ', 'Tecnología y electrónica', 'deteriorado', '2026-04-22 16:18:05', 'vendido', 36, 'Tablet', '2026-04-22');

-- Volcando estructura para tabla bdtruechange.articulos_fotos
CREATE TABLE IF NOT EXISTS `articulos_fotos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `articulo_id` int(11) NOT NULL,
  `ruta_foto` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `articulos_fotos_ibfk_1` (`articulo_id`),
  CONSTRAINT `articulos_fotos_ibfk_1` FOREIGN KEY (`articulo_id`) REFERENCES `articulos` (`id`) ON DELETE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=94 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla bdtruechange.articulos_fotos: ~4 rows (aproximadamente)
INSERT INTO `articulos_fotos` (`id`, `articulo_id`, `ruta_foto`) VALUES
	(90, 105, '../imagenes/uploads/69e8efc658e8c.jpg'),
	(91, 106, '../imagenes/uploads/69e8f0fc5a1b0.jpg'),
	(92, 107, '../imagenes/uploads/69e8f3a21ef2c.jpg'),
	(93, 108, '../imagenes/uploads/69e8f4bd0a5a7.jpg');

-- Volcando estructura para tabla bdtruechange.favoritos
CREATE TABLE IF NOT EXISTS `favoritos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) NOT NULL,
  `id_articulo` int(11) NOT NULL,
  `fecha_guardado` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_usuario` (`id_usuario`,`id_articulo`),
  KEY `id_articulo` (`id_articulo`),
  CONSTRAINT `favoritos_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  CONSTRAINT `favoritos_ibfk_2` FOREIGN KEY (`id_articulo`) REFERENCES `articulos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla bdtruechange.favoritos: ~0 rows (aproximadamente)

-- Volcando estructura para tabla bdtruechange.intercambios
CREATE TABLE IF NOT EXISTS `intercambios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `solicitante_id` int(11) NOT NULL,
  `receptor_id` int(11) NOT NULL,
  `articulo_solicitante` int(11) NOT NULL,
  `articulo_receptor` int(11) NOT NULL,
  `estado` enum('pendiente','aceptado','rechazado','cancelado','completado') DEFAULT 'pendiente',
  `fecha` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `solicitante_id` (`solicitante_id`),
  KEY `receptor_id` (`receptor_id`),
  KEY `articulo_solicitante` (`articulo_solicitante`),
  KEY `articulo_receptor` (`articulo_receptor`),
  CONSTRAINT `intercambios_ibfk_1` FOREIGN KEY (`solicitante_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  CONSTRAINT `intercambios_ibfk_2` FOREIGN KEY (`receptor_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  CONSTRAINT `intercambios_ibfk_3` FOREIGN KEY (`articulo_solicitante`) REFERENCES `articulos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `intercambios_ibfk_4` FOREIGN KEY (`articulo_receptor`) REFERENCES `articulos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla bdtruechange.intercambios: ~0 rows (aproximadamente)

-- Volcando estructura para tabla bdtruechange.messages
CREATE TABLE IF NOT EXISTS `messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `message_text` text NOT NULL,
  `timestamp` datetime DEFAULT current_timestamp(),
  `is_read` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `sender_id` (`sender_id`),
  KEY `receiver_id` (`receiver_id`)
) ENGINE=InnoDB AUTO_INCREMENT=263 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla bdtruechange.messages: ~1 rows (aproximadamente)
INSERT INTO `messages` (`id`, `sender_id`, `receiver_id`, `message_text`, `timestamp`, `is_read`) VALUES
	(262, 35, 33, 'Hola que tal?', '2026-04-12 20:34:05', 1);

-- Volcando estructura para tabla bdtruechange.notificaciones
CREATE TABLE IF NOT EXISTS `notificaciones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) NOT NULL,
  `tipo` varchar(50) NOT NULL,
  `contenido` text NOT NULL,
  `leido` tinyint(1) DEFAULT 0,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `usuario_id` (`usuario_id`),
  CONSTRAINT `notificaciones_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla bdtruechange.notificaciones: ~0 rows (aproximadamente)

-- Volcando estructura para tabla bdtruechange.reseñas
CREATE TABLE IF NOT EXISTS `reseñas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `articulo_id` int(11) DEFAULT NULL,
  `emisor_id` int(11) DEFAULT NULL,
  `receptor_id` int(11) DEFAULT NULL,
  `puntuacion` int(11) DEFAULT NULL CHECK (`puntuacion` >= 1 and `puntuacion` <= 5),
  `comentario` text DEFAULT NULL,
  `fecha` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `articulo_id` (`articulo_id`),
  KEY `emisor_id` (`emisor_id`),
  KEY `receptor_id` (`receptor_id`),
  CONSTRAINT `reseñas_ibfk_1` FOREIGN KEY (`articulo_id`) REFERENCES `articulos` (`id`),
  CONSTRAINT `reseñas_ibfk_2` FOREIGN KEY (`emisor_id`) REFERENCES `usuarios` (`id`),
  CONSTRAINT `reseñas_ibfk_3` FOREIGN KEY (`receptor_id`) REFERENCES `usuarios` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla bdtruechange.reseñas: ~1 rows (aproximadamente)
INSERT INTO `reseñas` (`id`, `articulo_id`, `emisor_id`, `receptor_id`, `puntuacion`, `comentario`, `fecha`) VALUES
	(7, 108, 39, 36, 5, 'Todo perfecto.', '2026-04-22 18:18:36');

-- Volcando estructura para tabla bdtruechange.usuarios
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `apellido1` varchar(150) NOT NULL,
  `apellido2` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `avatar` varchar(255) NOT NULL DEFAULT '../imagenes/uploads/default.png',
  `ciudad` varchar(255) DEFAULT NULL,
  `usuarioNombre` varchar(255) NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Volcando datos para la tabla bdtruechange.usuarios: ~2 rows (aproximadamente)
INSERT INTO `usuarios` (`id`, `nombre`, `apellido1`, `apellido2`, `email`, `password`, `avatar`, `ciudad`, `usuarioNombre`, `fecha_registro`) VALUES
	(36, 'Jose Luis', 'Ugalde', 'Mora', 'jose@gmail.com', '$2y$10$6vEqDm2DEhs3I84/q/bxJ.GqMNPMyKfHIz6A2CRD1jX4GsGTkiQJm', '../imagenes/uploads/default.png', 'Logroño', 'Jose1234', '2026-04-22 15:53:11'),
	(39, 'Antonio', 'Martinez', 'Ruiz', 'antonio@gmail.com', '$2y$10$b2FL3R8oSu4ReTLIFuA.2uqLr1VuQ.Fo8Wu2ijSRTaeSW9MYLyOKi', '../imagenes/uploads/uploadspexels-man-1281562_1920.jpg', 'Vitoria', 'Antonio1234', '2026-04-22 16:11:08');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
