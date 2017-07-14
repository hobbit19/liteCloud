-- phpMyAdmin SQL Dump
-- version 3.4.11.1deb2+deb7u8
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 11, 2017 at 02:42 PM
-- Server version: 5.5.55
-- PHP Version: 5.4.45-0+deb7u8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `xcloud_regedit`
--

-- --------------------------------------------------------

--
-- Table structure for table `apps`
--

CREATE TABLE IF NOT EXISTS `apps` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dir` text NOT NULL,
  `config` text NOT NULL,
  `alias` text NOT NULL,
  `version` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `apps`
--

INSERT INTO `apps` (`id`, `dir`, `config`, `alias`, `version`, `type`) VALUES
(1, 'apps/2e5d8aa3dfa8ef34ca5131d20f9dad51', '{"name":{"ru":"Настройки","en":"Settings"},"description":""}', 'settings', 0, 0),
(2, 'apps/3722cffd53dd74a2537b336bbd5720fd', '{"name":{"ru":"Файлы","en":"Files"},"description":""}', 'file_manager', 0, 0),
(5, 'apps/05b47614107eb2dd346f747a48936456', '{"name":{"ru":"Приложения","en":"Applications"},"description":""}', '', 0, 0),
(6, 'apps/bd1a5d7f1baf4a7c78dce7fb26e094d7', '{"name":{"ru":"Уведомления","en":"Notifications"},"description":""}', '', 0, 0),
(7, 'apps/a0b63292bf6b619396f71a4dd96b8ed2', '{"name":{"ru":"Аккаунт","en":"Account"},"description":""}', 'user_editor', 1, 1),
(8, 'apps/fc7afaaaf50a046f73b1000de5f0f6b8', '{"name":{"ru":"Музыка","en":"Music"},"description":""}', '', 0, 2),
(9, 'apps/9d12130a99776c8c6b9be4da1f73b9e1', '{"name":{"ru":"О файле","en":"About file"},"description":""}', 'fileinfo', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `association`
--

CREATE TABLE IF NOT EXISTS `association` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` text NOT NULL,
  `app_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `association`
--

INSERT INTO `association` (`id`, `type`, `app_id`) VALUES
(1, 'txt', 5),
(2, 'jpg', 6),
(3, 'png', 6),
(4, 'gif', 6),
(5, 'mp3', 8),
(6, 'jpeg', 6);

-- --------------------------------------------------------

--
-- Table structure for table `notices`
--

CREATE TABLE IF NOT EXISTS `notices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dir` text,
  `content` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `notices`
--

INSERT INTO `notices` (`id`, `dir`, `content`) VALUES
(2, NULL, 'Добрый день, это тестовое сообщение для проверки вывода уведомлений в xCloud. Вы мужете его удалить нажав на слово Удалить. Сука!');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `login` text NOT NULL,
  `name` text NOT NULL,
  `password` text NOT NULL,
  `root` int(1) NOT NULL,
  `avatar` text NOT NULL,
  `language` enum('ru','en') NOT NULL DEFAULT 'en',
  `rules` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `login`, `name`, `password`, `root`, `avatar`, `language`, `rules`) VALUES
(1, 'test', 'John Green', '890363a7b2f93f52b71c6d6410a2a9d3', 1, '/resources/assets/img/noavatar.png', 'ru', '');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
