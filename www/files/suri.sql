-- Adminer 4.2.5 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

CREATE DATABASE `suri` /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_czech_ci */;
USE `suri`;

CREATE TABLE `cars` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `car_types_id` int(11) unsigned NOT NULL,
  `engine_capacity` int(11) unsigned NOT NULL,
  `engine_power` int(11) unsigned NOT NULL,
  `weight` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `car_types_id` (`car_types_id`),
  CONSTRAINT `cars_ibfk_1` FOREIGN KEY (`car_types_id`) REFERENCES `car_types` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;


CREATE TABLE `car_types` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(256) COLLATE utf8_czech_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;


CREATE TABLE `clients` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `mail` varchar(256) COLLATE utf8_czech_ci NOT NULL,
  `name` varchar(256) COLLATE utf8_czech_ci NOT NULL,
  `zip_code` int(5) unsigned NOT NULL,
  `bday_date` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;


CREATE TABLE `insurance_contracts` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `clients_id` int(11) unsigned NOT NULL,
  `cars_id` int(11) unsigned NOT NULL,
  `total_price` int(11) NOT NULL,
  `calculation` varchar(4096) COLLATE utf8_czech_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `clients_id` (`clients_id`),
  KEY `cars_id` (`cars_id`),
  CONSTRAINT `insurance_contracts_ibfk_1` FOREIGN KEY (`clients_id`) REFERENCES `clients` (`id`),
  CONSTRAINT `insurance_contracts_ibfk_2` FOREIGN KEY (`cars_id`) REFERENCES `cars` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;


-- 2017-06-26 15:46:55
