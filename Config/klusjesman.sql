-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Gegenereerd op: 28 mei 2025 om 09:04
-- Serverversie: 5.7.17
-- PHP-versie: 8.3.3

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
  `Email` varchar(40) COLLATE utf8_bin NOT NULL,
  `Telefoonnummer` varchar(20) COLLATE utf8_bin NOT NULL,
  `Straat` varchar(30) COLLATE utf8_bin NOT NULL,
  `Huisnummer` varchar(10) COLLATE utf8_bin NOT NULL,
  `Postcode` varchar(20) COLLATE utf8_bin NOT NULL,
  `Plaats` varchar(20) COLLATE utf8_bin NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Gegevens worden geëxporteerd voor tabel `klanten`
--

INSERT INTO `klanten` (`Id`, `Voornaam`, `Tussenvoegsel`, `Achternaam`, `Email`, `Telefoonnummer`, `Straat`, `Huisnummer`, `Postcode`, `Plaats`) VALUES
(10, 'Abd-AL-Rahman', '', 'Zenaty', 'Abood.Zy@gmail.com', '7 54 696 826', 'Tuinstraat', '9', '0000 Zy', 'Lemelerveld'),
(9, 'Abdulla', '', 'Zenaty', '0.abd.zy.0@gmail.com', '6 28 696 475', 'Bergherveld', '50', '7041 XT', '\'s-Heerenberg');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `klant_notities`
--

CREATE TABLE `klant_notities` (
  `id` int(11) NOT NULL,
  `klant_id` int(11) NOT NULL,
  `inhoud` text COLLATE utf8_bin NOT NULL,
  `datum_toegevoegd` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Gegevens worden geëxporteerd voor tabel `klant_notities`
--

INSERT INTO `klant_notities` (`id`, `klant_id`, `inhoud`, `datum_toegevoegd`) VALUES
(10, 9, 'Test', '2025-05-23 10:54:05'),
(11, 9, 'Test2', '2025-05-23 10:55:15'),
(14, 10, 'ABOOOOD', '2025-05-23 11:12:07'),
(17, 10, 'dshauidhasij', '2025-05-23 11:42:56');

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `klanten`
--
ALTER TABLE `klanten`
  ADD PRIMARY KEY (`Id`);

--
-- Indexen voor tabel `klant_notities`
--
ALTER TABLE `klant_notities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `klant_id` (`klant_id`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `klanten`
--
ALTER TABLE `klanten`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT voor een tabel `klant_notities`
--
ALTER TABLE `klant_notities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
