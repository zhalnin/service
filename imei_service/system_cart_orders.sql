-- phpMyAdmin SQL Dump
-- version 4.1.11
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Июл 16 2014 г., 01:58
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
-- Структура таблицы `system_cart_orders`
--

CREATE TABLE IF NOT EXISTS `system_cart_orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(100) NOT NULL DEFAULT '',
  `lastname` varchar(100) NOT NULL DEFAULT '',
  `email` varchar(150) NOT NULL DEFAULT '',
  `data` text NOT NULL,
  `country` varchar(100) NOT NULL DEFAULT '',
  `address` varchar(255) NOT NULL DEFAULT '',
  `city` varchar(100) NOT NULL DEFAULT '',
  `zip_code` varchar(10) NOT NULL DEFAULT '',
  `state` varchar(100) NOT NULL DEFAULT '',
  `status` varchar(50) NOT NULL DEFAULT '',
  `amount` decimal(8,2) NOT NULL DEFAULT '0.00',
  `paypal_trans_id` varchar(20) NOT NULL DEFAULT '',
  `created_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 ;

--
-- Дамп данных таблицы `system_cart_orders`
--

INSERT INTO `system_cart_orders` (`id`, `firstname`, `lastname`, `email`, `data`, `country`, `address`, `city`, `zip_code`, `state`, `status`, `amount`, `paypal_trans_id`, `created_at`) VALUES
(16, 'Aleksander', 'Pushkin', 'zhalninpal-buyer@me.com', '-', 'Russia', 'лица Первая, дом 1, квартира 2', 'Москва', '127001', 'Москва', 'Completed', 113.90, '5YE89028R8481390L', '2014-07-16 01:50:01'),
(15, 'Aleksander', 'Pushkin', 'zhalninpal-buyer@me.com', '-', 'Russia', 'лица Первая, дом 1, квартира 2', 'Москва', '127001', 'Москва', 'Completed', 654.18, '1YX15801YH168190M', '2014-07-15 23:44:23'),
(14, 'Aleksander', 'Pushkin', 'zhalninpal-buyer@me.com', '-', 'Russia', 'лица Первая, дом 1, квартира 2', 'Москва', '127001', 'Москва', 'Completed', 654.18, '2GH179289F791340P', '2014-07-15 23:35:50'),
(13, 'Aleksander', 'Pushkin', 'zhalninpal-buyer@me.com', '-', 'Russia', 'лица Первая, дом 1, квартира 2', 'Москва', '127001', 'Москва', 'Completed', 654.18, '0PM67630D10658122', '2014-07-15 23:27:39'),
(17, 'Aleksander', 'Pushkin', 'zhalninpal-buyer@me.com', '-', 'Russia', 'лица Первая, дом 1, квартира 2', 'Москва', '127001', 'Москва', 'Completed', 145.07, '10H60602HN540544N', '2014-07-16 01:56:53');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
