-- Database: crud_system
CREATE DATABASE IF NOT EXISTS `crud_system` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `crud_system`;

CREATE TABLE IF NOT EXISTS `students` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `dob` date DEFAULT NULL,
  `course` varchar(150) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
