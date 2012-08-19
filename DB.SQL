-- Adminer 3.4.0 MySQL dump

SET NAMES utf8;
SET foreign_key_checks = 0;
SET time_zone = 'SYSTEM';
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `books`;
CREATE TABLE `books` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `isbn` varchar(30) NOT NULL,
  `title` varchar(50) NOT NULL,
  `publisher` varchar(30) NOT NULL,
  `author` varchar(40) NOT NULL,
  `copyright` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `books` (`id`, `isbn`, `title`, `publisher`, `author`, `copyright`) VALUES
(1,	'ABC1234567890',	'Ibong Adarna',	'Publishers',	'Florante',	1800),
(2,	'XYZ27638276348767',	'Noli Me Tangere',	'Publishers',	'Jose Rizal',	1800),
(3,	'EFG23237462837468',	'El Filibusterismo',	'Publishers',	'Jose Rizal',	1800);

DROP TABLE IF EXISTS `borrowed_books`;
CREATE TABLE `borrowed_books` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `isbn` varchar(30) NOT NULL,
  `user_id` int(11) NOT NULL,
  `date_borrowed` date NOT NULL,
  `is_returned` int(11) NOT NULL,
  `date_returned` date NOT NULL,
  `days_penalty` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `borrowed_books_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `reserved_books`;
CREATE TABLE `reserved_books` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `isbn` varchar(30) NOT NULL,
  `user_id` int(11) NOT NULL,
  `date_reserved` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `reserved_books_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) DEFAULT NULL,
  `password` varchar(20) DEFAULT NULL,
  `expiry_date` date NOT NULL,
  `type` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `users` (`id`, `username`, `password`, `expiry_date`, `type`) VALUES
(1,	'superuser',	'secret123',	'0000-00-00',	'admin'),
(2,	'normaluser',	'secret123',	'0000-00-00',	'normal'),
(3,	'expireduser',	'secret123',	'0000-00-00',	'normal');

-- 2012-08-19 18:41:11