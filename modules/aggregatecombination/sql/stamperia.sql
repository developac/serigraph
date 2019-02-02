-- phpMyAdmin SQL Dump
-- version 4.6.6deb5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Creato il: Gen 28, 2019 alle 23:28
-- Versione del server: 5.7.25-0ubuntu0.18.04.2
-- Versione PHP: 7.2.14-1+ubuntu18.04.1+deb.sury.org+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `stamperia`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `ps_ag_attribute_temp`
--

CREATE TABLE `ps_ag_attribute_temp` (
  `id_attribute_temp` int(10) UNSIGNED NOT NULL,
  `id_attribute` varchar(10) NOT NULL,
  `valore` varchar(255) NOT NULL,
  `tipologia` varchar(255) NOT NULL,
  `id_product` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `ps_ag_group`
--

CREATE TABLE `ps_ag_group` (
  `id_ag_group` int(11) NOT NULL,
  `position` int(10) NOT NULL DEFAULT '0',
  `active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `ps_ag_group`
--

INSERT INTO `ps_ag_group` (`id_ag_group`, `position`, `active`) VALUES
(1, 0, 1),
(2, 0, 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `ps_ag_group_attribute`
--

CREATE TABLE `ps_ag_group_attribute` (
  `id_ag_group_attribute` int(11) NOT NULL,
  `id_ag_group` int(11) NOT NULL,
  `id_attribute` int(11) NOT NULL,
  `id_value` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `ps_ag_group_attribute`
--

INSERT INTO `ps_ag_group_attribute` (`id_ag_group_attribute`, `id_ag_group`, `id_attribute`, `id_value`) VALUES
(1, 1, 6, 28),
(2, 1, 6, 29),
(3, 1, 6, 30),
(4, 1, 6, 31),
(5, 1, 9, 35),
(6, 1, 9, 36);

-- --------------------------------------------------------

--
-- Struttura della tabella `ps_ag_group_lang`
--

CREATE TABLE `ps_ag_group_lang` (
  `id_ag_group` int(11) NOT NULL,
  `id_lang` int(10) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `ps_ag_group_lang`
--

INSERT INTO `ps_ag_group_lang` (`id_ag_group`, `id_lang`, `name`) VALUES
(1, 1, 'Tipologia carta'),
(2, 1, 'Opzioni');

-- --------------------------------------------------------

--
-- Struttura della tabella `ps_ag_group_products`
--

CREATE TABLE `ps_ag_group_products` (
  `id_ag_group_products` int(10) UNSIGNED NOT NULL,
  `id_ag_group` int(10) UNSIGNED NOT NULL,
  `id_product` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `ps_ag_group_products`
--

INSERT INTO `ps_ag_group_products` (`id_ag_group_products`, `id_ag_group`, `id_product`) VALUES
(1, 1, 25);

-- --------------------------------------------------------

--
-- Struttura della tabella `ps_ag_group_products_rule`
--

CREATE TABLE `ps_ag_group_products_rule` (
  `id_ag_group_products_rule` int(11) NOT NULL,
  `id_ag_group_products` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `ps_ag_group_products_rule`
--

INSERT INTO `ps_ag_group_products_rule` (`id_ag_group_products_rule`, `id_ag_group_products`, `name`) VALUES
(1, 1, 'regola 1'),
(2, 1, 'regola 2');

-- --------------------------------------------------------

--
-- Struttura della tabella `ps_ag_group_products_rule_attribute`
--

CREATE TABLE `ps_ag_group_products_rule_attribute` (
  `id_ag_group_products_rule_attribute` int(11) NOT NULL,
  `id_ag_group_products_rule` int(11) NOT NULL,
  `id_attribute` varchar(11) NOT NULL,
  `id_attribute_value` varchar(11) NOT NULL,
  `valore` varchar(255) NOT NULL,
  `tipologia` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `ps_ag_group_products_rule_attribute`
--

INSERT INTO `ps_ag_group_products_rule_attribute` (`id_ag_group_products_rule_attribute`, `id_ag_group_products_rule`, `id_attribute`, `id_attribute_value`, `valore`, `tipologia`) VALUES
(1, 1, '6', '28', '10', '%'),
(2, 1, '9', '35', '10', '%'),
(3, 1, '9', '36', '10', '%'),
(4, 1, '6', '30', '4', '%'),
(5, 1, '6', '31', '4', '%'),
(6, 1, '9', '36', '4', '%');

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `ps_ag_group`
--
ALTER TABLE `ps_ag_group`
  ADD PRIMARY KEY (`id_ag_group`);

--
-- Indici per le tabelle `ps_ag_group_attribute`
--
ALTER TABLE `ps_ag_group_attribute`
  ADD PRIMARY KEY (`id_ag_group_attribute`);

--
-- Indici per le tabelle `ps_ag_group_lang`
--
ALTER TABLE `ps_ag_group_lang`
  ADD PRIMARY KEY (`id_ag_group`);

--
-- Indici per le tabelle `ps_ag_group_products`
--
ALTER TABLE `ps_ag_group_products`
  ADD PRIMARY KEY (`id_ag_group_products`);

--
-- Indici per le tabelle `ps_ag_group_products_rule`
--
ALTER TABLE `ps_ag_group_products_rule`
  ADD PRIMARY KEY (`id_ag_group_products_rule`);

--
-- Indici per le tabelle `ps_ag_group_products_rule_attribute`
--
ALTER TABLE `ps_ag_group_products_rule_attribute`
  ADD PRIMARY KEY (`id_ag_group_products_rule_attribute`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `ps_ag_group`
--
ALTER TABLE `ps_ag_group`
  MODIFY `id_ag_group` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT per la tabella `ps_ag_group_attribute`
--
ALTER TABLE `ps_ag_group_attribute`
  MODIFY `id_ag_group_attribute` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT per la tabella `ps_ag_group_products`
--
ALTER TABLE `ps_ag_group_products`
  MODIFY `id_ag_group_products` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT per la tabella `ps_ag_group_products_rule`
--
ALTER TABLE `ps_ag_group_products_rule`
  MODIFY `id_ag_group_products_rule` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT per la tabella `ps_ag_group_products_rule_attribute`
--
ALTER TABLE `ps_ag_group_products_rule_attribute`
  MODIFY `id_ag_group_products_rule_attribute` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
