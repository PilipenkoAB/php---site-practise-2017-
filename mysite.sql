-- phpMyAdmin SQL Dump
-- version 4.0.10.6
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1:3306
-- Время создания: Мар 12 2016 г., 19:55
-- Версия сервера: 5.5.41-log
-- Версия PHP: 5.3.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `mysite`
--

-- --------------------------------------------------------

--
-- Структура таблицы `Commission`
--

CREATE TABLE IF NOT EXISTS `Commission` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `Money` decimal(65,2) DEFAULT NULL,
  `Data` datetime NOT NULL,
  `Job` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Дамп данных таблицы `Commission`
--

INSERT INTO `Commission` (`id`, `Money`, `Data`, `Job`) VALUES
(1, '0.00', '0000-00-00 00:00:00', 0),
(2, '20.00', '2016-03-12 14:44:31', 12),
(3, '20.00', '2016-03-12 18:52:42', 13),
(4, '20.00', '2016-03-12 19:02:41', 54),
(5, '50.00', '2016-03-12 19:34:39', 26),
(6, '10.00', '2016-03-12 19:46:10', 20),
(7, '10.00', '2016-03-12 19:50:23', 56);

-- --------------------------------------------------------

--
-- Структура таблицы `Contracts (old)`
--

CREATE TABLE IF NOT EXISTS `Contracts (old)` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `JobId` int(255) DEFAULT NULL,
  `FreelancerId` int(255) DEFAULT NULL,
  `FreelancerAgree` enum('0','1','2') DEFAULT '0',
  `EmployerAgree` enum('0','1') DEFAULT '0',
  `FreelancerTextBack` varchar(500) NOT NULL,
  `FullPrice` decimal(65,2) DEFAULT '0.00',
  `OpenData` datetime DEFAULT NULL,
  `Prepayment` enum('0','1','2','3','4') DEFAULT '0',
  `Active` enum('0','1') NOT NULL DEFAULT '0',
  `Ban` enum('0','1') NOT NULL DEFAULT '0',
  `Commission` decimal(65,2) DEFAULT '0.00',
  `PrepaymentPaid` enum('0','1') DEFAULT '0',
  `Closed` enum('0','1') NOT NULL DEFAULT '0',
  `CommissionPaid` enum('0','1') NOT NULL DEFAULT '0',
  `CloseData` datetime NOT NULL,
  `Deadline` date DEFAULT NULL,
  `Price` decimal(65,2) DEFAULT '0.00',
  `Parts` enum('1','2','3','4','5','6','7','8','9','10') DEFAULT '1',
  `ReservedMoney` decimal(65,2) DEFAULT NULL,
  `StatusPart_1` enum('0','1','2','3') DEFAULT '1',
  `DescriptionPart_1` varchar(500) NOT NULL,
  `PricePart_1` decimal(65,2) NOT NULL,
  `TextBackPart_1` varchar(500) NOT NULL,
  `StatusPart_2` enum('0','1','2','3') DEFAULT '0',
  `DescriptionPart_2` varchar(500) NOT NULL,
  `PricePart_2` decimal(65,2) NOT NULL,
  `TextBackPart_2` varchar(500) NOT NULL,
  `StatusPart_3` enum('0','1','2','3') DEFAULT '0',
  `DescriptionPart_3` varchar(500) NOT NULL,
  `PricePart_3` decimal(65,2) NOT NULL,
  `TextBackPart_3` varchar(500) NOT NULL,
  `StatusPart_4` enum('0','1','2','3') DEFAULT '0',
  `DescriptionPart_4` varchar(500) NOT NULL,
  `PricePart_4` decimal(65,2) NOT NULL,
  `TextBackPart_4` varchar(500) NOT NULL,
  `StatusPart_5` enum('0','1','2','3') NOT NULL DEFAULT '0',
  `DescriptionPart_5` varchar(500) NOT NULL,
  `PricePart_5` decimal(65,2) NOT NULL,
  `TextBackPart_5` varchar(500) NOT NULL,
  `StatusPart_6` enum('0','1','2','3') NOT NULL DEFAULT '0',
  `DescriptionPart_6` varchar(500) NOT NULL,
  `PricePart_6` decimal(65,2) NOT NULL,
  `TextBackPart_6` varchar(500) NOT NULL,
  `StatusPart_7` enum('0','1','2','3') NOT NULL DEFAULT '0',
  `DescriptionPart_7` varchar(500) NOT NULL,
  `PricePart_7` decimal(65,2) NOT NULL,
  `TextBackPart_7` varchar(500) NOT NULL,
  `StatusPart_8` enum('0','1','2','3') NOT NULL DEFAULT '0',
  `DescriptionPart_8` varchar(500) NOT NULL,
  `PricePart_8` decimal(65,2) NOT NULL,
  `TextBackPart_8` varchar(500) NOT NULL,
  `StatusPart_9` enum('0','1','2','3') NOT NULL DEFAULT '0',
  `DescriptionPart_9` varchar(500) NOT NULL,
  `PricePart_9` decimal(65,2) NOT NULL,
  `TextBackPart_9` varchar(500) NOT NULL,
  `StatusPart_10` enum('0','1','2','3') NOT NULL DEFAULT '0',
  `DescriptionPart_10` varchar(500) NOT NULL,
  `PricePart_10` decimal(65,2) NOT NULL,
  `TextBackPart_10` varchar(500) NOT NULL,
  `Comment` varchar(60) NOT NULL,
  `Reputation` int(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IdJob` (`JobId`),
  KEY `IdFreelancer` (`FreelancerId`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=54 ;

--
-- Дамп данных таблицы `Contracts (old)`
--

INSERT INTO `Contracts (old)` (`id`, `JobId`, `FreelancerId`, `FreelancerAgree`, `EmployerAgree`, `FreelancerTextBack`, `FullPrice`, `OpenData`, `Prepayment`, `Active`, `Ban`, `Commission`, `PrepaymentPaid`, `Closed`, `CommissionPaid`, `CloseData`, `Deadline`, `Price`, `Parts`, `ReservedMoney`, `StatusPart_1`, `DescriptionPart_1`, `PricePart_1`, `TextBackPart_1`, `StatusPart_2`, `DescriptionPart_2`, `PricePart_2`, `TextBackPart_2`, `StatusPart_3`, `DescriptionPart_3`, `PricePart_3`, `TextBackPart_3`, `StatusPart_4`, `DescriptionPart_4`, `PricePart_4`, `TextBackPart_4`, `StatusPart_5`, `DescriptionPart_5`, `PricePart_5`, `TextBackPart_5`, `StatusPart_6`, `DescriptionPart_6`, `PricePart_6`, `TextBackPart_6`, `StatusPart_7`, `DescriptionPart_7`, `PricePart_7`, `TextBackPart_7`, `StatusPart_8`, `DescriptionPart_8`, `PricePart_8`, `TextBackPart_8`, `StatusPart_9`, `DescriptionPart_9`, `PricePart_9`, `TextBackPart_9`, `StatusPart_10`, `DescriptionPart_10`, `PricePart_10`, `TextBackPart_10`, `Comment`, `Reputation`) VALUES
(53, 12, 33, '1', '1', '', '200.00', NULL, '0', '1', '0', '20.00', '1', '1', '1', '2016-03-12 14:44:31', '2016-03-09', '180.00', '1', '200.00', '1', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', 0),
(45, 53, 33, '1', '1', '', '125.00', NULL, '1', '1', '1', '12.50', '1', '0', '0', '0000-00-00 00:00:00', '2016-02-27', '84.38', '1', '125.00', '1', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `Country`
--

CREATE TABLE IF NOT EXISTS `Country` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `Russian` varchar(30) NOT NULL,
  `English` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=231 ;

--
-- Дамп данных таблицы `Country`
--

INSERT INTO `Country` (`id`, `Russian`, `English`) VALUES
(1, '', 'Albania'),
(2, '', 'Algeria'),
(3, '', 'American Samoa'),
(4, '', 'Andorra'),
(5, '', 'Angola'),
(6, '', 'Anguilla'),
(7, '', 'Antarctica'),
(8, '', 'Antigua and Barbuda'),
(9, '', 'Argentina'),
(10, '', 'Armenia'),
(11, '', 'Aruba'),
(12, '', 'Australia'),
(13, '', 'Austria'),
(14, '', 'Azerbaijan'),
(15, '', 'Bahamas'),
(16, '', 'Bahrain'),
(17, '', 'Bangladesh'),
(18, '', 'Barbados'),
(19, '', 'Belarus'),
(20, '', 'Belgium'),
(21, '', 'Belize'),
(22, '', 'Benin'),
(23, '', 'Bermuda'),
(24, '', 'Bhutan'),
(25, '', 'Bolivia'),
(26, '', 'Bosnia and Herzegovina'),
(27, '', 'Botswana'),
(28, '', 'Bouvet Island'),
(29, '', 'Brazil'),
(30, '', 'British Indian Ocean Territory'),
(31, '', 'British Virgin Islands'),
(32, '', 'Brunei Darussalam'),
(33, '', 'Bulgaria'),
(34, '', 'Burkina Faso'),
(35, '', 'Burundi'),
(36, '', 'Cambodia'),
(37, '', 'Cameroon'),
(38, '', 'Canada'),
(39, '', 'Cape Verde'),
(40, '', 'Cayman Islands'),
(41, '', 'Central African Republic '),
(42, '', 'Chad'),
(43, '', 'Chile'),
(44, '', 'China'),
(45, '', 'Christmas Island'),
(46, '', 'Cocos (Keeling) Islands'),
(47, '', 'Colombia'),
(48, '', 'Comoros'),
(49, '', 'Congo'),
(50, '', 'Congo, the Democratic Republic'),
(51, '', 'Cook Islands'),
(52, '', 'Costa Rica'),
(53, '', 'Cote d''Ivoire'),
(54, '', 'Croatia'),
(55, '', 'Cyprus'),
(56, '', 'Czech Republic'),
(57, '', 'Denmark'),
(58, '', 'Djibouti'),
(59, '', 'Dominica'),
(60, '', 'Dominican Republic'),
(61, '', 'Ecuador'),
(62, '', 'Egypt'),
(63, '', 'El Salvador'),
(64, '', 'Equatorial Guinea'),
(65, '', 'Eritrea'),
(66, '', 'Estonia'),
(67, '', 'Ethiopia'),
(68, '', 'Falkland Islands '),
(69, '', 'Faroe Islands'),
(70, '', 'Fiji'),
(71, '', 'Finland'),
(72, '', 'France'),
(73, '', 'French Guiana'),
(74, '', 'French Polynesia'),
(75, '', 'French Southern and Antarctic '),
(76, '', 'Gabon'),
(77, '', 'Gambia'),
(78, '', 'Georgia'),
(79, '', 'Germany'),
(80, '', 'Ghana'),
(81, '', 'Gibraltar'),
(82, '', 'Greece'),
(83, '', 'Greenland'),
(84, '', 'Grenada'),
(85, '', 'Guadeloupe'),
(86, '', 'Guam'),
(87, '', 'Guatemala'),
(88, '', 'Guinea'),
(89, '', 'Guinea-Bissau'),
(90, '', 'Guyana'),
(91, '', 'Haiti'),
(92, '', 'Heard Island and McDonald Isla'),
(93, '', 'Holy See'),
(94, '', 'Honduras'),
(95, '', 'Hong Kong'),
(96, '', 'Hungary'),
(97, '', 'Iceland'),
(98, '', 'India'),
(99, '', 'Indonesia'),
(100, '', 'Ireland'),
(101, '', 'Isle of Man'),
(102, '', 'Israel'),
(103, '', 'Italy'),
(104, '', 'Jamaica'),
(105, '', 'Japan'),
(106, '', 'Jordan'),
(107, '', 'Kazakhstan'),
(108, '', 'Kenya'),
(109, '', 'Kiribati'),
(110, '', 'Kuwait'),
(111, '', 'Kyrgyzstan'),
(112, '', 'Laos'),
(113, '', 'Latvia'),
(114, '', 'Lebanon'),
(115, '', 'Lesotho'),
(116, '', 'Liechtenstein'),
(117, '', 'Lithuania'),
(118, '', 'Luxembourg'),
(119, '', 'Macao'),
(120, '', 'Macedonia'),
(121, '', 'Madagascar'),
(122, '', 'Malawi'),
(123, '', 'Malaysia'),
(124, '', 'Maldives'),
(125, '', 'Mali'),
(126, '', 'Malta'),
(127, '', 'Marshall Islands'),
(128, '', 'Martinique'),
(129, '', 'Mauritania'),
(130, '', 'Mauritius'),
(131, '', 'Mayotte'),
(132, '', 'Mexico'),
(133, '', 'Micronesia, Federated States o'),
(134, '', 'Moldova'),
(135, '', 'Monaco'),
(136, '', 'Mongolia'),
(137, '', 'Montenegro'),
(138, '', 'Montserrat'),
(139, '', 'Morocco'),
(140, '', 'Mozambique'),
(141, '', 'Myanmar'),
(142, '', 'Namibia'),
(143, '', 'Nepal'),
(144, '', 'Netherlands'),
(145, '', 'Netherlands Antilles'),
(146, '', 'New Caledonia'),
(147, '', 'New Zealand'),
(148, '', 'Nicaragua'),
(149, '', 'Niger'),
(150, '', 'Nigeria'),
(151, '', 'Niue'),
(152, '', 'Norfolk Island'),
(153, '', 'Northern Mariana Islands'),
(154, '', 'Norway'),
(155, '', 'Oman'),
(156, '', 'Pakistan'),
(157, '', 'Palau'),
(158, '', 'Palestinian Territories'),
(159, '', 'Panama'),
(160, '', 'Papua New Guinea'),
(161, '', 'Paraguay'),
(162, '', 'Peru'),
(163, '', 'Philippines'),
(164, '', 'Pitcairn'),
(165, '', 'Poland'),
(166, '', 'Portugal'),
(167, '', 'Puerto Rico'),
(168, '', 'Qatar'),
(169, '', 'Reunion'),
(170, '', 'Romania'),
(171, '', 'Russia'),
(172, '', 'Rwanda'),
(173, '', 'Saint Helena'),
(174, '', 'Saint Kitts and Nevis'),
(175, '', 'Saint Lucia'),
(176, '', 'Saint Pierre and Miquelon'),
(177, '', 'Saint Vincent and the Grenadin'),
(178, '', 'Samoa'),
(179, '', 'San Marino'),
(180, '', 'Sao Tome and Principe'),
(181, '', 'Saudi Arabia'),
(182, '', 'Senegal'),
(183, '', 'Serbia'),
(184, '', 'Seychelles'),
(185, '', 'Sierra Leone'),
(186, '', 'Singapore'),
(187, '', 'Slovakia'),
(188, '', 'Slovenia'),
(189, '', 'Solomon Islands'),
(190, '', 'Somalia'),
(191, '', 'South Africa'),
(192, '', 'South Korea'),
(193, '', 'Spain'),
(194, '', 'Sri Lanka'),
(195, '', 'Suriname'),
(196, '', 'Svalbard and Jan Mayen'),
(197, '', 'Swaziland'),
(198, '', 'Sweden'),
(199, '', 'Switzerland'),
(200, '', 'Taiwan'),
(201, '', 'Tajikistan'),
(202, '', 'Tanzania'),
(203, '', 'Thailand'),
(204, '', 'Timor-Leste'),
(205, '', 'Togo'),
(206, '', 'Tokelau'),
(207, '', 'Tonga'),
(208, '', 'Trinidad and Tobago'),
(209, '', 'Tunisia'),
(210, '', 'Turkey'),
(211, '', 'Turkmenistan'),
(212, '', 'Turks and Caicos Islands'),
(213, '', 'Tuvalu'),
(214, '', 'Uganda'),
(215, '', 'Ukraine'),
(216, '', 'United Arab Emirates'),
(217, '', 'United Kingdom'),
(218, '', 'United States'),
(219, '', 'United States Minor Outlying I'),
(220, '', 'United States Virgin Islands'),
(221, '', 'Uruguay'),
(222, '', 'Uzbekistan'),
(223, '', 'Vanuatu'),
(224, '', 'Venezuela'),
(225, '', 'Vietnam'),
(226, '', 'Wallis and Futuna'),
(227, '', 'Western Sahara'),
(228, '', 'Yemen'),
(229, '', 'Zambia'),
(230, '', 'Zimbabwe');

-- --------------------------------------------------------

--
-- Структура таблицы `DurationTranslation`
--

CREATE TABLE IF NOT EXISTS `DurationTranslation` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `Russian` varchar(50) NOT NULL,
  `English` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Дамп данных таблицы `DurationTranslation`
--

INSERT INTO `DurationTranslation` (`id`, `Russian`, `English`) VALUES
(1, 'Больше 6 месяцев', ''),
(2, '3 - 6 месяца', ''),
(3, '1 - 3 месяца', ''),
(4, 'Меньше месяца', ''),
(5, 'Меньше недели', ''),
(6, 'Один день', '');

-- --------------------------------------------------------

--
-- Структура таблицы `Jobs`
--

CREATE TABLE IF NOT EXISTS `Jobs` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `EmployerId` int(255) NOT NULL,
  `LanguageFrom1` int(255) DEFAULT NULL,
  `LanguageTo1` int(255) DEFAULT NULL,
  `LanguageFrom2` int(255) NOT NULL,
  `LanguageTo2` int(255) NOT NULL,
  `LanguageFrom3` int(255) NOT NULL,
  `LanguageTo3` int(255) NOT NULL,
  `LanguageFrom4` int(255) NOT NULL,
  `LanguageTo4` int(255) NOT NULL,
  `LanguageFrom5` int(255) NOT NULL,
  `LanguageTo5` int(255) NOT NULL,
  `Style` enum('1','2','3','4','5','6','7') NOT NULL,
  `Title` varchar(50) NOT NULL,
  `Description` varchar(5000) NOT NULL,
  `Type` enum('1','2','3','4','5') DEFAULT NULL,
  `Search` enum('1','2') NOT NULL,
  `Duration` enum('1','2','3','4','5','6') NOT NULL,
  `Private` enum('0','1') NOT NULL,
  `TestJobDescription` varchar(50) DEFAULT NULL,
  `TestJobText` varchar(1800) DEFAULT NULL,
  `StartMinPrice` decimal(65,2) NOT NULL,
  `StartMaxPrice` decimal(62,2) NOT NULL,
  `DataCreation` datetime NOT NULL,
  `Prepayment` enum('0','1','2','3','4') NOT NULL,
  `File` varchar(150) DEFAULT NULL,
  `FreelancerId` int(255) NOT NULL,
  `ContractId` int(255) DEFAULT '0',
  `ContractId_2` int(255) DEFAULT '0',
  `ContractId_3` int(255) DEFAULT '0',
  `ContractId_4` int(255) DEFAULT '0',
  `ContractId_5` int(255) DEFAULT '0',
  `EmployerAgree` enum('0','1') NOT NULL,
  `FreelancerAgree` enum('0','1','2') NOT NULL,
  `FreelancerTextBack` varchar(500) NOT NULL,
  `FullPrice` decimal(65,2) NOT NULL,
  `Deadline` date NOT NULL,
  `Parts` enum('1','2','3','4','5','6','7','8','9','10') NOT NULL,
  `Active` enum('0','1') NOT NULL,
  `OpenData` datetime NOT NULL,
  `ReservedMoney` decimal(65,2) NOT NULL,
  `Price` decimal(65,2) NOT NULL,
  `Commission` decimal(65,2) NOT NULL,
  `CommissionPaid` enum('0','1') NOT NULL,
  `PrepaymentPaid` enum('0','1') NOT NULL,
  `StatusPart_1` enum('0','1','2','3') NOT NULL,
  `DescriptionPart_1` varchar(500) NOT NULL,
  `PricePart_1` decimal(65,2) NOT NULL,
  `TextBackPart_1` varchar(500) NOT NULL,
  `StatusPart_2` enum('0','1','2','3') NOT NULL,
  `DescriptionPart_2` varchar(500) NOT NULL,
  `PricePart_2` decimal(65,2) NOT NULL,
  `TextBackPart_2` varchar(500) NOT NULL,
  `StatusPart_3` enum('0','1','2','3') NOT NULL,
  `DescriptionPart_3` varchar(500) NOT NULL,
  `PricePart_3` decimal(65,2) NOT NULL,
  `TextBackPart_3` varchar(500) NOT NULL,
  `StatusPart_4` enum('0','1','2','3') NOT NULL,
  `DescriptionPart_4` varchar(500) NOT NULL,
  `PricePart_4` decimal(65,2) NOT NULL,
  `TextBackPart_4` varchar(500) NOT NULL,
  `StatusPart_5` enum('0','1','2','3') NOT NULL,
  `DescriptionPart_5` varchar(500) NOT NULL,
  `PricePart_5` decimal(65,2) NOT NULL,
  `TextBackPart_5` varchar(500) NOT NULL,
  `StatusPart_6` enum('0','1','2','3') NOT NULL,
  `DescriptionPart_6` varchar(500) NOT NULL,
  `PricePart_6` decimal(65,2) NOT NULL,
  `TextBackPart_6` varchar(500) NOT NULL,
  `StatusPart_7` enum('0','1','2','3') NOT NULL,
  `DescriptionPart_7` varchar(500) NOT NULL,
  `PricePart_7` decimal(65,2) NOT NULL,
  `TextBackPart_7` varchar(500) NOT NULL,
  `StatusPart_8` enum('0','1','2','3') NOT NULL,
  `DescriptionPart_8` varchar(500) NOT NULL,
  `PricePart_8` decimal(65,2) NOT NULL,
  `TextBackPart_8` varchar(500) NOT NULL,
  `StatusPart_9` enum('0','1','2','3') NOT NULL,
  `DescriptionPart_9` varchar(500) NOT NULL,
  `PricePart_9` decimal(65,2) NOT NULL,
  `TextBackPart_9` varchar(500) NOT NULL,
  `StatusPart_10` enum('0','1','2','3') NOT NULL,
  `DescriptionPart_10` varchar(500) NOT NULL,
  `PricePart_10` decimal(65,2) NOT NULL,
  `TextBackPart_10` varchar(500) NOT NULL,
  `Ban` enum('0','1') NOT NULL,
  `Closed` enum('0','1') NOT NULL,
  `CloseData` datetime NOT NULL,
  `Reputation` int(255) NOT NULL,
  `Comment` varchar(500) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `EmployerId` (`EmployerId`),
  KEY `Language` (`LanguageFrom1`),
  KEY `Style` (`Style`),
  KEY `Type` (`Type`),
  KEY `Search` (`Search`),
  KEY `Duration` (`Duration`),
  KEY `FreelancerId` (`ContractId`),
  KEY `LanguageTo` (`LanguageTo1`),
  KEY `LanguageFrom2` (`LanguageFrom2`,`LanguageTo2`,`LanguageFrom3`,`LanguageTo3`,`LanguageFrom4`,`LanguageTo4`,`LanguageFrom5`,`LanguageTo5`),
  KEY `FreelancerId_2` (`ContractId_2`,`ContractId_3`,`ContractId_4`,`ContractId_5`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=57 ;

--
-- Дамп данных таблицы `Jobs`
--

INSERT INTO `Jobs` (`id`, `EmployerId`, `LanguageFrom1`, `LanguageTo1`, `LanguageFrom2`, `LanguageTo2`, `LanguageFrom3`, `LanguageTo3`, `LanguageFrom4`, `LanguageTo4`, `LanguageFrom5`, `LanguageTo5`, `Style`, `Title`, `Description`, `Type`, `Search`, `Duration`, `Private`, `TestJobDescription`, `TestJobText`, `StartMinPrice`, `StartMaxPrice`, `DataCreation`, `Prepayment`, `File`, `FreelancerId`, `ContractId`, `ContractId_2`, `ContractId_3`, `ContractId_4`, `ContractId_5`, `EmployerAgree`, `FreelancerAgree`, `FreelancerTextBack`, `FullPrice`, `Deadline`, `Parts`, `Active`, `OpenData`, `ReservedMoney`, `Price`, `Commission`, `CommissionPaid`, `PrepaymentPaid`, `StatusPart_1`, `DescriptionPart_1`, `PricePart_1`, `TextBackPart_1`, `StatusPart_2`, `DescriptionPart_2`, `PricePart_2`, `TextBackPart_2`, `StatusPart_3`, `DescriptionPart_3`, `PricePart_3`, `TextBackPart_3`, `StatusPart_4`, `DescriptionPart_4`, `PricePart_4`, `TextBackPart_4`, `StatusPart_5`, `DescriptionPart_5`, `PricePart_5`, `TextBackPart_5`, `StatusPart_6`, `DescriptionPart_6`, `PricePart_6`, `TextBackPart_6`, `StatusPart_7`, `DescriptionPart_7`, `PricePart_7`, `TextBackPart_7`, `StatusPart_8`, `DescriptionPart_8`, `PricePart_8`, `TextBackPart_8`, `StatusPart_9`, `DescriptionPart_9`, `PricePart_9`, `TextBackPart_9`, `StatusPart_10`, `DescriptionPart_10`, `PricePart_10`, `TextBackPart_10`, `Ban`, `Closed`, `CloseData`, `Reputation`, `Comment`) VALUES
(12, 106, 1, 2, 0, 0, 0, 0, 0, 0, 0, 0, '2', '', '', '1', '1', '2', '0', '0', '0', '12.00', '0.00', '2015-01-21 17:04:51', '1', NULL, 0, 53, 33, 0, 0, 0, '0', '0', '', '0.00', '0000-00-00', '1', '0', '0000-00-00 00:00:00', '0.00', '0.00', '0.00', '0', '0', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '1', '0000-00-00 00:00:00', 0, ''),
(13, 106, 1, 2, 0, 0, 0, 0, 0, 0, 0, 0, '2', '321', '', '1', '1', '2', '1', '0', '0', '12.00', '0.00', '2015-01-22 18:40:41', '0', NULL, 33, 0, 0, 0, 0, 0, '1', '1', '', '200.00', '2016-03-30', '1', '1', '0000-00-00 00:00:00', '200.00', '180.00', '20.00', '1', '1', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '1', '2016-03-12 18:52:42', 0, '0'),
(20, 106, 2, 1, 0, 0, 0, 0, 0, 0, 0, 0, '1', 'For test something else ', 'for test reserving money start', '1', '1', '1', '0', '0', '0', '33333.00', '0.00', '2015-01-31 17:23:18', '2', NULL, 33, 0, 0, 0, 0, 0, '1', '1', '', '100.00', '2016-03-16', '1', '1', '2016-03-12 19:43:10', '100.00', '45.00', '10.00', '1', '1', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '1', '2016-03-12 19:46:10', 0, '0'),
(26, 106, 1, 2, 0, 0, 0, 0, 0, 0, 0, 0, '2', '123', '321', '2', '1', '1', '0', '0', '0', '333.00', '0.00', '2015-02-22 17:54:32', '2', NULL, 33, 0, 0, 0, 0, 0, '1', '1', '', '500.00', '2016-03-15', '1', '1', '2016-03-12 19:12:03', '500.00', '225.00', '50.00', '1', '1', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '1', '2016-03-12 19:34:39', 0, '0'),
(29, 106, 2, 1, 1, 2, 0, 0, 0, 0, 0, 0, '1', '', '', '1', '1', '2', '1', '0', '0', '2222.00', '0.00', '2015-02-22 19:15:01', '3', NULL, 0, 0, 0, 0, 0, 0, '0', '0', '', '0.00', '0000-00-00', '1', '0', '0000-00-00 00:00:00', '0.00', '0.00', '0.00', '0', '0', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '0', '0000-00-00 00:00:00', 0, ''),
(30, 106, 3, 1, 3, 2, 0, 0, 0, 0, 0, 0, '2', '', '', '1', '1', '4', '1', '0', '0', '250.00', '0.00', '2015-02-23 16:14:41', '0', NULL, 0, 0, 0, 0, 0, 0, '0', '0', '', '0.00', '0000-00-00', '1', '0', '0000-00-00 00:00:00', '0.00', '0.00', '0.00', '0', '0', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '0', '0000-00-00 00:00:00', 0, ''),
(31, 108, 2, 1, 2, 3, 0, 0, 0, 0, 0, 0, '2', 'Перевод проверочка', 'проверочный перевод', '1', '1', '4', '1', '0', '0', '100.00', '0.00', '2015-03-14 16:54:47', '0', NULL, 0, 0, 0, 0, 0, 0, '0', '0', '', '0.00', '0000-00-00', '1', '0', '0000-00-00 00:00:00', '0.00', '0.00', '0.00', '0', '0', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '0', '0000-00-00 00:00:00', 0, ''),
(32, 100, 2, 3, 0, 0, 0, 0, 0, 0, 0, 0, '3', '12313', '12313', '2', '1', '2', '1', '0', '0', '123.00', '0.00', '2015-05-13 01:59:27', '0', NULL, 0, 0, 0, 0, 0, 0, '0', '0', '', '0.00', '0000-00-00', '1', '0', '0000-00-00 00:00:00', '0.00', '0.00', '0.00', '0', '0', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '0', '0000-00-00 00:00:00', 0, ''),
(55, 106, 1, 3, 0, 0, 0, 0, 0, 0, 0, 0, '1', '210216 приватность нет', 'без приватности', '1', '2', '1', '1', '0', '0', '10.00', '30.00', '2016-02-21 14:19:56', '1', '', 0, 0, 0, 0, 0, 0, '0', '0', '', '0.00', '0000-00-00', '1', '0', '0000-00-00 00:00:00', '0.00', '0.00', '0.00', '0', '0', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '0', '0000-00-00 00:00:00', 0, ''),
(54, 106, 1, 2, 0, 0, 0, 0, 0, 0, 0, 0, '1', '210216 приватность', 'приватность? поставлена приватность', '1', '1', '1', '1', '0', '0', '10.00', '30.00', '2016-02-21 14:17:47', '2', '', 33, 0, 0, 0, 0, 0, '1', '1', '', '200.00', '2016-03-30', '1', '1', '2016-03-12 19:01:10', '200.00', '90.00', '20.00', '1', '1', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '1', '2016-03-12 19:02:41', 0, '0'),
(53, 106, 1, 2, 0, 0, 0, 0, 0, 0, 0, 0, '1', 'тест 21 02 16', 'блаблабла', '1', '1', '1', '1', '0', '0', '10.00', '30.00', '2016-02-21 13:16:50', '1', '', 0, 45, 0, 0, 0, 0, '0', '0', '', '0.00', '0000-00-00', '1', '0', '0000-00-00 00:00:00', '0.00', '0.00', '0.00', '0', '0', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '0', '0000-00-00 00:00:00', 0, ''),
(51, 109, 1, 3, 1, 2, 0, 0, 0, 0, 0, 0, '2', 'Проверка работоспособности 13 02 2016', 'тест создания проекта', '1', '1', '2', '1', 'переведите', 'welcome', '750.00', '1500.00', '2016-02-13 13:24:11', '1', '', 0, 0, 0, 0, 0, 0, '0', '0', '', '0.00', '0000-00-00', '1', '0', '0000-00-00 00:00:00', '0.00', '0.00', '0.00', '0', '0', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '0', '0000-00-00 00:00:00', 0, ''),
(50, 108, 1, 2, 0, 0, 0, 0, 0, 0, 0, 0, '1', 'Project test Preview Job', 'test preview job', '1', '1', '1', '1', '0', '0', '10.00', '30.00', '2015-07-28 18:48:52', '1', '', 0, 0, 0, 0, 0, 0, '0', '0', '', '0.00', '0000-00-00', '1', '0', '0000-00-00 00:00:00', '0.00', '0.00', '0.00', '0', '0', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '0', '0000-00-00 00:00:00', 0, ''),
(49, 108, 1, 3, 1, 2, 0, 0, 0, 0, 0, 0, '3', 'Окончательный тест загрузки файла', 'Файл загружается с именем &quot;id&quot;+&quot;случайное число от 1 до 10000&quot;+&quot;имя файла&quot;', '3', '2', '4', '1', '0', '0', '750.00', '1500.00', '2015-02-24 17:47:44', '1', '108_354_zvon.m', 0, 0, 0, 0, 0, 0, '0', '0', '', '0.00', '0000-00-00', '1', '0', '0000-00-00 00:00:00', '0.00', '0.00', '0.00', '0', '0', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '0', '0000-00-00 00:00:00', 0, ''),
(56, 106, 2, 1, 0, 0, 0, 0, 0, 0, 0, 0, '1', 'test reserver again', 'tatatat', '1', '1', '1', '1', '0', '0', '10.00', '30.00', '2016-03-12 19:47:56', '2', '', 33, 0, 0, 0, 0, 0, '1', '1', '', '100.00', '2016-03-09', '1', '1', '2016-03-12 19:49:03', '100.00', '45.00', '10.00', '1', '1', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '', '0.00', '', '0', '1', '2016-03-12 19:50:23', 0, '0');

-- --------------------------------------------------------

--
-- Структура таблицы `Language`
--

CREATE TABLE IF NOT EXISTS `Language` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Flag` varchar(50) NOT NULL,
  `Russian` varchar(100) DEFAULT NULL,
  `English` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Дамп данных таблицы `Language`
--

INSERT INTO `Language` (`id`, `Flag`, `Russian`, `English`) VALUES
(1, '/mysite/img/flags/russian.png', 'Русский', 'Russian'),
(2, '/mysite/img/flags/english.png', 'Английский', 'English'),
(3, '/mysite/img/flags/france.png', 'Французский', 'French');

-- --------------------------------------------------------

--
-- Структура таблицы `Messages`
--

CREATE TABLE IF NOT EXISTS `Messages` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `IdFrom` int(255) NOT NULL,
  `IdTo` int(255) NOT NULL,
  `Type` int(255) DEFAULT NULL,
  `Message` varchar(1000) NOT NULL,
  `Time` datetime NOT NULL,
  `Readed` enum('0','1') DEFAULT '0',
  `Deleted` enum('0','1') DEFAULT '0',
  `Archive` enum('0','1') DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `IdFrom` (`IdFrom`,`IdTo`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=93 ;

--
-- Дамп данных таблицы `Messages`
--

INSERT INTO `Messages` (`id`, `IdFrom`, `IdTo`, `Type`, `Message`, `Time`, `Readed`, `Deleted`, `Archive`) VALUES
(1, 105, 107, 0, 'первоое сообщение', '2014-12-07 15:00:51', '0', '0', '0'),
(2, 105, 106, 32, 'второе сообщение', '2014-12-07 15:03:15', '0', '0', '0'),
(3, 106, 105, 32, 'сообщение обратно 1', '2014-12-08 19:38:19', '0', '0', '0'),
(4, 106, 105, 32, 'обратное сообщение 2', '2015-03-05 03:18:36', '0', '0', '0'),
(5, 105, 106, 32, ' обратно 3', '2015-03-01 04:36:08', '0', '0', '0'),
(76, 106, 105, 0, 'ПУСТОЕ СООБЩЕНИЕ ОТПРАВЛЯЕТСЯ', '2015-04-11 14:17:35', '0', '0', '0'),
(75, 106, 105, 0, '', '2015-04-11 14:17:26', '0', '0', '0'),
(74, 106, 105, 0, 'йцу', '2015-04-11 14:17:22', '0', '0', '0'),
(73, 106, 105, 0, 'теперь сообщения идут наверх&lt;div&gt;&lt;br&gt;&lt;/div&gt;', '2015-04-11 14:16:23', '0', '0', '0'),
(72, 106, 105, 0, 'qweqwe', '2015-04-11 14:16:10', '0', '0', '0'),
(71, 106, 105, 0, 'qwe', '2015-04-11 14:16:07', '0', '0', '0'),
(70, 106, 105, 0, 'qe', '2015-04-11 14:14:30', '0', '0', '0'),
(69, 106, 105, 0, '2', '2015-04-11 14:13:30', '0', '0', '0'),
(68, 106, 105, 0, '3&lt;div&gt;&lt;br&gt;&lt;/div&gt;', '2015-04-11 14:13:26', '0', '0', '0'),
(67, 106, 105, 0, '2', '2015-04-11 14:10:28', '0', '0', '0'),
(66, 106, 105, 0, '1\n', '2015-04-11 14:10:01', '0', '0', '0'),
(65, 106, 105, 0, '7', '2015-04-11 14:08:50', '0', '0', '0'),
(64, 106, 105, 0, '6', '2015-04-11 14:08:46', '0', '0', '0'),
(63, 106, 105, 0, '5', '2015-04-11 14:08:38', '0', '0', '0'),
(62, 106, 105, 0, '4', '2015-04-11 14:08:36', '0', '0', '0'),
(60, 106, 105, 0, '2', '2015-04-11 14:08:31', '0', '0', '0'),
(61, 106, 105, 0, '3', '2015-04-11 14:08:33', '0', '0', '0'),
(58, 106, 105, 0, 'нужно много сообщений&lt;div&gt;&lt;br&gt;&lt;/div&gt;&lt;div&gt;&lt;br&gt;&lt;br&gt;&lt;/div&gt;', '2015-04-11 14:08:27', '0', '0', '0'),
(59, 106, 105, 0, '1', '2015-04-11 14:08:30', '0', '0', '0'),
(54, 106, 105, 0, '321', '2015-04-11 13:07:35', '0', '0', '0'),
(57, 106, 105, 0, 'тест куда идет', '2015-04-11 13:27:54', '0', '0', '0'),
(56, 105, 106, 0, 'ответ ответ', '2015-04-11 07:07:07', '0', '0', '0'),
(53, 106, 105, 0, '123', '2015-04-10 16:22:25', '0', '0', '0'),
(52, 106, 2, 0, 'test messages.php&lt;br&gt;&lt;br&gt;', '2015-04-10 14:56:43', '0', '0', '0'),
(51, 2, 106, 0, 'wwweeeqqq', '2015-04-02 00:00:00', '0', '0', '0'),
(45, 106, 32, 33, 'салют другому фрилансеру', '2015-03-10 16:06:28', '0', '0', '0'),
(46, 106, 105, 32, '&lt;p&gt;\ntest from explorer&lt;/p&gt;&lt;p&gt;&lt;br&gt;&lt;/p&gt;', '2015-03-17 18:19:52', '0', '0', '0'),
(47, 106, 105, 32, 'bara bara', '2015-03-28 18:18:54', '0', '0', '0'),
(48, 106, 2, 0, '123', '2015-04-10 14:42:24', '0', '0', '0'),
(49, 106, 2, 0, '321', '2015-04-10 14:50:15', '0', '0', '0'),
(50, 2, 106, 0, 'qqqqwwee', '2015-04-15 00:00:00', '0', '0', '0'),
(44, 106, 105, 32, 'успешно пройден. Обновление каждые 5 секунд. Подтверждено!', '2015-03-10 16:05:26', '0', '0', '0'),
(43, 106, 105, 32, 'окончательный тест первой настройки чата', '2015-03-10 16:05:05', '0', '0', '0'),
(39, 106, 105, 32, '123', '2015-03-10 15:49:31', '0', '0', '0'),
(40, 107, 105, 0, '123', '2015-03-19 00:00:00', '0', '0', '0'),
(41, 106, 105, 32, '123', '2015-03-10 16:01:20', '0', '0', '0'),
(42, 106, 105, 32, 'проверка чата', '2015-03-10 16:03:31', '0', '0', '0'),
(77, 106, 105, 0, '1\n', '2015-04-11 14:33:14', '0', '0', '0'),
(78, 106, 105, 0, '2\n', '2015-04-11 15:14:00', '0', '0', '0'),
(79, 106, 105, 0, 'ы\n', '2015-04-11 15:14:19', '0', '0', '0'),
(80, 106, 105, 0, '1\n', '2015-04-11 15:19:29', '0', '0', '0'),
(81, 106, 105, 0, '2\n', '2015-04-11 15:20:15', '0', '0', '0'),
(82, 106, 105, 0, '3\n', '2015-04-11 15:21:02', '0', '0', '0'),
(83, 106, 105, 0, 'теперь сообщение начинается с последнее, скроллер опущен вниз, при новом сообщении скроллер так же опущен вниз. Однако нужно переделать систему обновления диалогов как добавление нового сообщение, а не обновление всех существующих', '2015-04-11 15:22:00', '0', '0', '0'),
(84, 106, 105, 0, 'теперь сообщения разделяются на свои (справа) и чужие(слева)', '2015-04-11 16:16:11', '0', '0', '0'),
(85, 106, 105, 0, 'и опять проверка', '2015-04-11 16:16:34', '0', '0', '0'),
(86, 105, 106, 0, 'ответное сообщение', '2015-04-11 16:22:05', '0', '0', '0'),
(87, 106, 105, 0, 'ответное ответному', '2015-04-14 15:01:20', '0', '0', '0'),
(88, 100, 0, 0, '23', '2015-04-18 21:13:26', '0', '0', '0'),
(89, 106, 105, 32, 'klhlkh&lt;div&gt;&lt;br&gt;&lt;/div&gt;', '2016-02-13 13:30:11', '0', '0', '0'),
(90, 106, 105, 32, 'qqqqqqqq', '2016-02-13 13:30:26', '0', '0', '0'),
(91, 106, 33, 45, '321', '2016-03-05 13:55:45', '0', '0', '0'),
(92, 106, 33, 45, 'чат типо рабочий&lt;div&gt;&lt;br&gt;&lt;/div&gt;', '2016-03-05 13:55:58', '0', '0', '0');

-- --------------------------------------------------------

--
-- Структура таблицы `News`
--

CREATE TABLE IF NOT EXISTS `News` (
  `id` int(255) NOT NULL,
  `Data` date NOT NULL,
  `Author` varchar(100) NOT NULL,
  `Title` varchar(50) NOT NULL,
  `Text` varchar(450) NOT NULL COMMENT 'чтобы влезало в index news',
  `Image_Large` varchar(100) NOT NULL,
  `Link` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `News`
--

INSERT INTO `News` (`id`, `Data`, `Author`, `Title`, `Text`, `Image_Large`, `Link`) VALUES
(0, '2015-03-11', 'Имя Фамилияавтора', 'Первая новость', 'Текст первой новости прост, проверить функциональность работы новостной ленты', '001_Large.jpg', 'first-news'),
(1, '2015-03-14', 'Пилипенко Александр', 'Проверка второй новости', 'На данном этапе разработки потребовалось написать какой то текст, чтобы он занимал около 450 символов, что являлось бы коротким описанием новости, которая помещается в разделе blog.php и при этом отражало бы всю суть новости и прилично смотрелось', '002_Large.jpg', 'second-news');

-- --------------------------------------------------------

--
-- Структура таблицы `ResponseNewJob`
--

CREATE TABLE IF NOT EXISTS `ResponseNewJob` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `FreelancerId` int(255) NOT NULL,
  `JobId` int(255) NOT NULL,
  `Private` enum('0','1') NOT NULL DEFAULT '0',
  `PrivateDescription` varchar(1000) NOT NULL,
  `DataResponse` datetime NOT NULL,
  `Response` varchar(100) NOT NULL,
  `Price` int(255) NOT NULL,
  `Duration` enum('1','2','3','4','5','6') DEFAULT NULL,
  `TestJobAnswer` varchar(1800) NOT NULL,
  `Result` enum('0','1','2','3') DEFAULT '0',
  `Archive` enum('0','1') NOT NULL DEFAULT '0',
  `Deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `FreelancerId` (`FreelancerId`),
  KEY `JobId` (`JobId`),
  KEY `Duration` (`Duration`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=19 ;

--
-- Дамп данных таблицы `ResponseNewJob`
--

INSERT INTO `ResponseNewJob` (`id`, `FreelancerId`, `JobId`, `Private`, `PrivateDescription`, `DataResponse`, `Response`, `Price`, `Duration`, `TestJobAnswer`, `Result`, `Archive`, `Deleted`) VALUES
(2, 105, 12, '0', '', '2015-01-01 00:00:00', '', 321, '5', '0', '0', '0', '0'),
(14, 33, 13, '0', '', '2016-03-12 18:11:21', 'neee', 100, '1', '0', '2', '0', '0'),
(4, 33, 12, '0', '', '2015-01-31 00:00:00', '13', 222, '3', '0', '2', '0', '0'),
(10, 33, 50, '0', '', '2015-01-31 00:00:00', '13', 222, '3', '0', '0', '0', '0'),
(11, 32, 50, '0', '', '2015-01-07 00:00:00', 'ready', 150, '2', '0', '0', '0', '0'),
(15, 33, 54, '0', '', '2016-03-12 18:56:19', 'tretie zadanie', 200, '1', '0', '2', '0', '0'),
(13, 33, 53, '0', '', '2016-02-21 14:51:34', '2222', 22, '5', '0', '2', '0', '0'),
(16, 33, 26, '0', '', '2016-03-12 19:10:09', '500', 500, '4', '0', '2', '0', '0'),
(17, 33, 20, '0', '', '2016-03-12 19:41:56', '100', 100, '1', '0', '2', '0', '0'),
(18, 33, 56, '0', '', '2016-03-12 19:48:16', '100', 100, '3', '0', '2', '0', '0');

-- --------------------------------------------------------

--
-- Структура таблицы `SavedFreelancers`
--

CREATE TABLE IF NOT EXISTS `SavedFreelancers` (
  `id` int(255) NOT NULL,
  `EmployerId` int(255) NOT NULL,
  `FreelancerId` int(255) NOT NULL,
  `Note` varchar(500) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `SavedFreelancers`
--

INSERT INTO `SavedFreelancers` (`id`, `EmployerId`, `FreelancerId`, `Note`) VALUES
(0, 106, 105, 'заметка о фрилансере'),
(1, 106, 32, 'вторая заметка');

-- --------------------------------------------------------

--
-- Структура таблицы `SavedJobs`
--

CREATE TABLE IF NOT EXISTS `SavedJobs` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `FreelancerId` int(255) NOT NULL,
  `JobId` int(255) NOT NULL,
  `Note` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `JobId` (`JobId`),
  KEY `FreelancerId` (`FreelancerId`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Дамп данных таблицы `SavedJobs`
--

INSERT INTO `SavedJobs` (`id`, `FreelancerId`, `JobId`, `Note`) VALUES
(4, 33, 15, 'qweewq'),
(5, 33, 16, '');

-- --------------------------------------------------------

--
-- Структура таблицы `SearchTranslation`
--

CREATE TABLE IF NOT EXISTS `SearchTranslation` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `Russian` varchar(50) NOT NULL,
  `English` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Дамп данных таблицы `SearchTranslation`
--

INSERT INTO `SearchTranslation` (`id`, `Russian`, `English`) VALUES
(1, '', ''),
(2, '', '');

-- --------------------------------------------------------

--
-- Структура таблицы `StyleTranslation`
--

CREATE TABLE IF NOT EXISTS `StyleTranslation` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `Russian` varchar(50) NOT NULL,
  `English` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Дамп данных таблицы `StyleTranslation`
--

INSERT INTO `StyleTranslation` (`id`, `Russian`, `English`) VALUES
(1, 'Общая тематика', ''),
(2, 'Художественный', ''),
(3, 'Технический', ''),
(4, 'Экономический', ''),
(5, 'Политический', ''),
(6, 'Медицинский', ''),
(7, 'Реклама', '');

-- --------------------------------------------------------

--
-- Структура таблицы `TemplateJob`
--

CREATE TABLE IF NOT EXISTS `TemplateJob` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `EmployerId` int(255) NOT NULL,
  `LanguageFrom1` int(255) DEFAULT NULL,
  `LanguageTo1` int(255) DEFAULT NULL,
  `LanguageFrom2` int(255) NOT NULL,
  `LanguageTo2` int(255) NOT NULL,
  `LanguageFrom3` int(255) NOT NULL,
  `LanguageTo3` int(255) NOT NULL,
  `LanguageFrom4` int(255) NOT NULL,
  `LanguageTo4` int(255) NOT NULL,
  `LanguageFrom5` int(255) NOT NULL,
  `LanguageTo5` int(255) NOT NULL,
  `Style` enum('1','2','3','4','5','6','7') NOT NULL,
  `Title` varchar(50) NOT NULL,
  `Description` varchar(500) NOT NULL,
  `Type` enum('1','2','3','4','5') DEFAULT NULL,
  `Value` enum('1','2','3','4','5') DEFAULT NULL,
  `Search` enum('1','2') NOT NULL,
  `Private` enum('0','1') NOT NULL,
  `TestJob` int(255) NOT NULL,
  `Duration` enum('1','2','3','4','5','6') NOT NULL,
  `Prepayment` enum('0','1','2','3','4') NOT NULL,
  `StartPrice` decimal(65,2) NOT NULL,
  `TestJobDescription` varchar(50) NOT NULL,
  `TestJobText` varchar(1800) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `EmployerId` (`EmployerId`),
  KEY `LanguageTo` (`LanguageTo1`),
  KEY `LanguageFrom` (`LanguageFrom1`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- Дамп данных таблицы `TemplateJob`
--

INSERT INTO `TemplateJob` (`id`, `EmployerId`, `LanguageFrom1`, `LanguageTo1`, `LanguageFrom2`, `LanguageTo2`, `LanguageFrom3`, `LanguageTo3`, `LanguageFrom4`, `LanguageTo4`, `LanguageFrom5`, `LanguageTo5`, `Style`, `Title`, `Description`, `Type`, `Value`, `Search`, `Private`, `TestJob`, `Duration`, `Prepayment`, `StartPrice`, `TestJobDescription`, `TestJobText`) VALUES
(2, 106, 2, 1, 0, 0, 0, 0, 0, 0, 0, 0, '2', '222222', '222222222222 2 2 2', '1', '1', '1', '0', 0, '2', '0', '22222.00', '', ''),
(3, 106, 1, 2, 0, 0, 0, 0, 0, 0, 0, 0, '4', '', '', '1', '1', '1', '0', 0, '1', '1', '23443.00', '', ''),
(6, 106, 1, 2, 0, 0, 0, 0, 0, 0, 0, 0, '2', '', '', '1', '2', '1', '1', 0, '2', '1', '12.00', '0', '0'),
(7, 0, 1, 2, 0, 0, 0, 0, 0, 0, 0, 0, '1', '', '111', '1', '1', '1', '1', 0, '1', '1', '222.00', '0', '0'),
(8, 0, 2, 1, 1, 2, 0, 0, 0, 0, 0, 0, '1', '', '', '1', '1', '1', '1', 0, '2', '3', '2222.00', '0', '0'),
(9, 106, 2, 1, 1, 2, 0, 0, 0, 0, 0, 0, '1', '', '', '1', '1', '1', '1', 0, '2', '3', '2222.00', '0', '0');

-- --------------------------------------------------------

--
-- Структура таблицы `tests`
--

CREATE TABLE IF NOT EXISTS `tests` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `language` varchar(10) NOT NULL,
  `category` varchar(10) NOT NULL,
  `difficult` varchar(10) NOT NULL,
  `type` enum('0','1') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Дамп данных таблицы `tests`
--

INSERT INTO `tests` (`id`, `name`, `language`, `category`, `difficult`, `type`) VALUES
(1, '', 'English', '', '1', '0'),
(2, '', 'English', '', '2', '1');

-- --------------------------------------------------------

--
-- Структура таблицы `TypeTranslation`
--

CREATE TABLE IF NOT EXISTS `TypeTranslation` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `Russian` varchar(50) NOT NULL,
  `English` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Дамп данных таблицы `TypeTranslation`
--

INSERT INTO `TypeTranslation` (`id`, `Russian`, `English`) VALUES
(1, 'Текст', ''),
(2, 'Видео', ''),
(3, 'Аудио', ''),
(4, 'Локализация', '');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `FirstName` varchar(100) NOT NULL,
  `Password` varchar(100) NOT NULL,
  `LastName` varchar(100) DEFAULT NULL,
  `Email` varchar(100) NOT NULL,
  `Activation` enum('0','1') DEFAULT NULL,
  `GroupId` enum('1','2','3','4','5') NOT NULL,
  `Username` varchar(100) NOT NULL,
  `Country` int(100) DEFAULT NULL,
  `Avatar` varchar(30) DEFAULT '/mysite/img/avatars/def.jpg',
  `Activation_code` varchar(100) NOT NULL,
  `Company` varchar(100) NOT NULL,
  `SessionGUID` varchar(100) DEFAULT NULL,
  `City` varchar(100) DEFAULT NULL,
  `Address` varchar(100) DEFAULT NULL,
  `Zip-code` varchar(100) DEFAULT NULL,
  `Phone` varchar(100) DEFAULT NULL,
  `Time-zone` varchar(100) DEFAULT NULL,
  `RegData` datetime DEFAULT NULL,
  `Rating` int(3) NOT NULL DEFAULT '0' COMMENT 'от 1 до 100 влияет на width заполнение звёзд в репутации',
  `Block` enum('0','1') NOT NULL DEFAULT '0',
  `Ban` enum('0','1') NOT NULL DEFAULT '0',
  `Money` decimal(65,2) DEFAULT '0.00',
  `ReservedMoney` decimal(65,2) DEFAULT '0.00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=110 ;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `FirstName`, `Password`, `LastName`, `Email`, `Activation`, `GroupId`, `Username`, `Country`, `Avatar`, `Activation_code`, `Company`, `SessionGUID`, `City`, `Address`, `Zip-code`, `Phone`, `Time-zone`, `RegData`, `Rating`, `Block`, `Ban`, `Money`, `ReservedMoney`) VALUES
(33, 'test', '$2a$10$ETvKhGNdsooUMTouPyTOfuismo/LWI001BoJ/FmrSSIGAIrTJDXau', 'test', 'test@test.test', '0', '1', 'Test', 171, '/mysite/img/avatars/def.jpg', '1210661448', '', '{438AD5B8-505F-EF7F-E71A-1D965F0024B0}', '', '', '2312312', '8 918 918 918 18', '+1', '2014-11-20 18:34:35', 2, '0', '0', '855.00', '0.00'),
(32, 'ivanov', '$2a$10$ETvKhGNdsooUMTouPyTOfuismo/LWI001BoJ/FmrSSIGAIrTJDXau', 'mail', 'ivanov@mail.ru', '0', '1', 'Ivanov', 171, '/mysite/img/avatars/def.jpg', '1278428523', '', '', NULL, NULL, NULL, NULL, NULL, '2014-11-18 19:46:13', 4, '0', '0', '0.00', '0.00'),
(100, 'Имя', '$2a$10$ETvKhGNdsooUMTouPyTOfuismo/LWI001BoJ/FmrSSIGAIrTJDXau', 'Фамилия', 'emailrabota@rabota.ru', '1', '2', 'emailrabota', NULL, '/mysite/img/avatars/def.jpg', '1013687965', 'Company', '{899876C9-BB1A-C18C-0AF8-0E029662034E}', NULL, NULL, NULL, NULL, NULL, '2014-11-21 23:43:01', 46, '0', '0', '100.00', '0.00'),
(103, 'mail ', '$2a$10$ETvKhGNdsooUMTouPyTOfuismo/LWI001BoJ/FmrSSIGAIrTJDXau', 'mail', 'mail@mailmail.com', '0', '2', '', NULL, '/mysite/img/avatars/def.jpg', '1255045505', '', '{C8D57D0C-0F03-2053-52A9-8DB5336256F0}', NULL, NULL, NULL, NULL, NULL, '2014-11-26 21:33:48', 0, '0', '0', '0.00', '0.00'),
(105, 'testpovtora', '$2a$10$ETvKhGNdsooUMTouPyTOfuismo/LWI001BoJ/FmrSSIGAIrTJDXau', 'povt', 'testpovtora@povt.ro', '0', '1', 'testpovtora', 1, '/mysite/img/avatars/def.jpg', '1403925239', '', NULL, NULL, NULL, NULL, NULL, NULL, '2014-11-26 21:36:14', 0, '0', '0', '0.00', '0.00'),
(106, 'rabota', '$25wI8Hz9LEi2', 'rabota', 'Rabota@rabota.rabota', '1', '2', 'username106', NULL, '/mysite/img/avatars/def.jpg', '1385072497', '', '{4E8FCF24-C312-6CC6-8694-4F3832541EFF}', NULL, NULL, NULL, NULL, NULL, '2014-12-05 18:32:45', 75, '0', '0', '9800.00', '0.00'),
(108, 'one', '$2a$10$ETvKhGNdsooUMTouPyTOfuismo/LWI001BoJ/FmrSSIGAIrTJDXau', 'onetwo', 'one@one.one', '1', '2', '', NULL, '/mysite/img/avatars/def.jpg', '1364368131', '', '{3741DAAC-7E5B-C9F8-3E15-C2246677C2F9}', NULL, NULL, NULL, NULL, NULL, '2015-03-04 14:36:16', 0, '0', '0', '0.00', '0.00'),
(109, 'Вася', '$2a$10$bfhqQRszAtECMtAXDgfWf.24Ci2d8KnGOwmA2K1u0gDoKEJOY0cdG', 'Иванов', 'Vasia@vasia.vasia', '0', '2', '', NULL, '/mysite/img/avatars/def.jpg', '1029913478', '', '{AE2D184E-3AEA-7C6F-30B3-2504FFA785F7}', NULL, NULL, NULL, NULL, NULL, '2016-02-13 13:17:00', 0, '0', '0', '0.00', '0.00'),
(1, 'Commission', 'Commission', 'Commission', 'Commission@Commission.Commission', NULL, '5', 'Commission', 1, '/mysite/img/avatars/def.jpg', '', '', NULL, NULL, NULL, NULL, NULL, NULL, '2016-03-05 00:00:00', 100, '0', '0', '130.00', '0.00');

-- --------------------------------------------------------

--
-- Структура таблицы `user_tests`
--

CREATE TABLE IF NOT EXISTS `user_tests` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `userid` int(100) NOT NULL,
  `testid` int(100) NOT NULL,
  `point` int(3) NOT NULL,
  `time` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`),
  KEY `testid` (`testid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `user_tests`
--

INSERT INTO `user_tests` (`id`, `userid`, `testid`, `point`, `time`) VALUES
(1, 33, 1, 5, '12:23:34');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
