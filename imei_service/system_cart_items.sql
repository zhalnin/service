-- phpMyAdmin SQL Dump
-- version 4.1.11
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Июл 16 2014 г., 02:00
-- Версия сервера: 5.1.58
-- Версия PHP: 5.3.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `zhalnin_service`
--

-- --------------------------------------------------------

--
-- Структура таблицы `system_cart_items`
--

CREATE TABLE IF NOT EXISTS `system_cart_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` tinytext NOT NULL,
  `order_id` int(11) NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL DEFAULT '',
  `price` decimal(8,2) NOT NULL DEFAULT '0.00',
  `qty` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=22 ;

--
-- Дамп данных таблицы `system_cart_items`
--

INSERT INTO `system_cart_items` (`id`, `product_id`, `order_id`, `title`, `price`, `qty`) VALUES
(17, '36_1', 15, 'Регистрация UDID', 100.00, 5),
(16, '13_1', 14, 'Полная проверка GSX', 30.00, 4),
(14, '13_1', 13, 'Полная проверка GSX', 30.00, 4),
(15, '36_1', 14, 'Регистрация UDID', 100.00, 5),
(13, '36_1', 13, 'Регистрация UDID', 100.00, 5),
(18, '13_1', 15, 'Полная проверка GSX', 30.00, 4),
(19, '36_1', 16, 'Регистрация UDID', 100.00, 1),
(20, '36_1', 17, 'Регистрация UDID', 100.00, 1),
(21, '13_1', 17, 'Полная проверка GSX', 30.00, 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
