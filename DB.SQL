-- phpMyAdmin SQL Dump
-- version 3.5.4
-- http://www.phpmyadmin.net
--
-- Host: localhost:3306
-- Generation Time: Feb 07, 2013 at 05:01 PM
-- Server version: 5.5.28
-- PHP Version: 5.4.9

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `tesda_libsys`
--

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE IF NOT EXISTS `books` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `isbn` varchar(30) NOT NULL,
  `title` varchar(50) NOT NULL,
  `publisher` varchar(30) NOT NULL,
  `author` varchar(40) NOT NULL,
  `copyright` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`id`, `isbn`, `title`, `publisher`, `author`, `copyright`) VALUES
(1, 'ABC1234567890', 'Ibong Adarna', 'Publishers', 'Florante', 1800),
(2, 'XYZ27638276348767', 'Noli Me Tangere', 'Publishers', 'Jose Rizal', 1800),
(3, 'EFG23237462837468', 'El Filibusterismo', 'Publishers', 'Jose Rizal', 1800);

-- --------------------------------------------------------

--
-- Table structure for table `borrowed_books`
--

CREATE TABLE IF NOT EXISTS `borrowed_books` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `isbn` varchar(30) NOT NULL,
  `user_id` int(11) NOT NULL,
  `date_borrowed` date NOT NULL,
  `is_returned` int(11) NOT NULL,
  `date_returned` date NOT NULL,
  `days_penalty` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `reserved_books`
--

CREATE TABLE IF NOT EXISTS `reserved_books` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `isbn` varchar(30) NOT NULL,
  `user_id` int(11) NOT NULL,
  `date_reserved` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `reserved_books`
--

INSERT INTO `reserved_books` (`id`, `isbn`, `user_id`, `date_reserved`) VALUES
(1, 'ABC1234567890', 2, '2013-02-07'),
(2, 'XYZ27638276348767', 2, '2013-02-07');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) DEFAULT NULL,
  `first_name` varchar(30) NOT NULL,
  `last_name` varchar(30) NOT NULL,
  `password` varchar(20) DEFAULT NULL,
  `expiry_date` date NOT NULL,
  `type` enum('normal','admin') NOT NULL,
  `is_disabled` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `first_name`, `last_name`, `password`, `expiry_date`, `type`, `is_disabled`) VALUES
(1, 'superuser', 'Pedro', 'Penduko', 'secret123', '2013-04-04', 'admin', 0),
(2, 'normaluser', 'Juan', 'Dela Cruz', 'secret123', '2013-04-04', 'normal', 0),
(3, 'expireduser', 'Berting', 'Labra', 'secret123', '2012-01-01', 'normal', 0),
(4, 'disableduser', 'Rene', 'Rekestas', 'secret123', '2013-02-28', 'normal', 1);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `borrowed_books`
--
ALTER TABLE `borrowed_books`
  ADD CONSTRAINT `borrowed_books_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reserved_books`
--
ALTER TABLE `reserved_books`
  ADD CONSTRAINT `reserved_books_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
