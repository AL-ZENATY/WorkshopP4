-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Gegenereerd op: 17 jun 2025 om 11:32
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
-- Tabelstructuur voor tabel `factuur`
--

CREATE TABLE `factuur` (
  `id` int(11) NOT NULL,
  `klant_id` int(11) NOT NULL,
  `datum` date NOT NULL,
  `bedrag` decimal(10,0) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Gegevens worden geëxporteerd voor tabel `factuur`
--

INSERT INTO `factuur` (`id`, `klant_id`, `datum`, `bedrag`) VALUES
(4, 9, '2025-06-17', 1104),
(2, 13, '2025-06-17', 26),
(3, 13, '2025-06-17', 16);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `factuur_regels`
--

CREATE TABLE `factuur_regels` (
  `id` int(11) NOT NULL,
  `factuur_id` int(11) NOT NULL,
  `omschrijving` varchar(1000) COLLATE utf8_bin NOT NULL,
  `prijs` decimal(10,0) NOT NULL,
  `aantal` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Gegevens worden geëxporteerd voor tabel `factuur_regels`
--

INSERT INTO `factuur_regels` (`id`, `factuur_id`, `omschrijving`, `prijs`, `aantal`) VALUES
(182, 4, 'Isolatie', 9, 100),
(181, 4, 'Rij Kosten', 13, 1),
(180, 3, 'Schroeven', 0, 5),
(179, 3, 'Rij Kosten', 13, 1),
(178, 2, 'Isolatie', 9, 1),
(177, 2, 'Rij Kosten', 13, 1),
(176, 1, 'Schroeven', 0, 500),
(175, 1, 'Schroeven', 0, 500),
(174, 1, 'Rij Kosten', 13, 2);

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
(9, 'Abdulla', '', 'Zenaty', '0.abd.zy.0@gmail.com', '6 28 696 475', 'Bergherveld', '50', '7041 XT', '\'s-Heerenberg'),
(11, 'Henk', 'Van', 'Johan', 'HenkVanJohan@gmail.com', '3 02 692 626', 'kfaieatadsggae', '12', '3623da', 'adalje'),
(12, 'milan', '', 'hoegen', 'hoegen.milan@gmail.com', '2 04 652 624', 'hoflaan', '20', '1835KD', 'Terborg'),
(13, 'Johan', 'Van', 'Derksen', 'JohanVanDerksen@gmail.com', '0 23 553 392', 'hoflaan', '62', '8384KD', 'Terborg'),
(14, 'Jay', '', 'K', 'JayK@gmail.com', '2 35 203 296', 'Kattenstraat', '78a', '8800RS', 'Roeselare');

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
(22, 14, 'Fiets', '2025-06-06 11:30:14'),
(18, 11, 'Fiets', '2025-05-28 09:26:25'),
(21, 10, 'Fiets', '2025-06-06 10:27:56');

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `factuur`
--
ALTER TABLE `factuur`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `factuur_regels`
--
ALTER TABLE `factuur_regels`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT voor een tabel `factuur`
--
ALTER TABLE `factuur`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT voor een tabel `factuur_regels`
--
ALTER TABLE `factuur_regels`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=183;

--
-- AUTO_INCREMENT voor een tabel `klanten`
--
ALTER TABLE `klanten`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT voor een tabel `klant_notities`
--
ALTER TABLE `klant_notities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
