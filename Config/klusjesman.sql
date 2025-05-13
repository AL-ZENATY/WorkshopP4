-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Gegenereerd op: 13 mei 2025 om 11:52
-- Serverversie: 5.7.17
-- PHP-versie: 8.2.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `klusjesman`
--
CREATE DATABASE IF NOT EXISTS `klusjesman` DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;
USE `klusjesman`;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `gebruikers`
--

CREATE TABLE `gebruikers` (
  `Id` int(11) DEFAULT NULL,
  `Voornaam` varchar(20) COLLATE utf8_bin NOT NULL,
  `Tussenvoegsel` varchar(20) COLLATE utf8_bin NOT NULL,
  `Achternaam` varchar(20) COLLATE utf8_bin NOT NULL,
  `Adres` varchar(20) COLLATE utf8_bin NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `klanten`
--

CREATE TABLE `klanten` (
  `Id` int(11) NOT NULL,
  `Voornaam` varchar(20) COLLATE utf8_bin NOT NULL,
  `Tussenvoegsel` varchar(20) COLLATE utf8_bin NOT NULL,
  `Achternaam` varchar(20) COLLATE utf8_bin NOT NULL,
  `Email` varchar(20) COLLATE utf8_bin NOT NULL,
  `Telefoonnummer` varchar(20) COLLATE utf8_bin NOT NULL,
  `Straat` varchar(30) COLLATE utf8_bin NOT NULL,
  `Huisnummer` double NOT NULL,
  `Postcode` varchar(20) COLLATE utf8_bin NOT NULL,
  `Plaats` varchar(20) COLLATE utf8_bin NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `klanten`
--
ALTER TABLE `klanten`
  ADD PRIMARY KEY (`Id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
