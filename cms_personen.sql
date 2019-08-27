-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Erstellungszeit: 27. Aug 2019 um 10:19
-- Server-Version: 10.1.37-MariaDB
-- PHP-Version: 7.3.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `cms_personen`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `postausgang_0`
--

CREATE TABLE `postausgang_0` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `absender` bigint(255) UNSIGNED NOT NULL,
  `empfaenger` text COLLATE utf8_unicode_ci NOT NULL,
  `zeit` bigint(255) UNSIGNED NOT NULL,
  `betreff` varbinary(5000) NOT NULL,
  `nachricht` longblob NOT NULL,
  `papierkorb` varbinary(50) NOT NULL,
  `papierkorbseit` bigint(255) UNSIGNED NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `posteingang_0`
--

CREATE TABLE `posteingang_0` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `absender` bigint(255) UNSIGNED NOT NULL,
  `empfaenger` bigint(255) UNSIGNED NOT NULL,
  `alle` text COLLATE utf8_unicode_ci NOT NULL,
  `zeit` bigint(255) UNSIGNED NOT NULL,
  `betreff` varbinary(5000) NOT NULL,
  `nachricht` longblob NOT NULL,
  `gelesen` varbinary(50) NOT NULL,
  `papierkorb` varbinary(50) NOT NULL,
  `papierkorbseit` bigint(255) UNSIGNED NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `postentwurf_0`
--

CREATE TABLE `postentwurf_0` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `absender` bigint(255) UNSIGNED NOT NULL,
  `empfaenger` text COLLATE utf8_unicode_ci NOT NULL,
  `zeit` bigint(255) UNSIGNED NOT NULL,
  `betreff` varbinary(5000) NOT NULL,
  `nachricht` longblob NOT NULL,
  `papierkorb` varbinary(50) NOT NULL,
  `papierkorbseit` bigint(255) UNSIGNED NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `postgetaggedausgang_0`
--

CREATE TABLE `postgetaggedausgang_0` (
  `tag` bigint(255) UNSIGNED NOT NULL,
  `nachricht` bigint(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `postgetaggedeingang_0`
--

CREATE TABLE `postgetaggedeingang_0` (
  `tag` bigint(255) UNSIGNED NOT NULL,
  `nachricht` bigint(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `postgetaggedentwurf_0`
--

CREATE TABLE `postgetaggedentwurf_0` (
  `tag` bigint(255) UNSIGNED NOT NULL,
  `nachricht` bigint(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `posttags_0`
--

CREATE TABLE `posttags_0` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `person` bigint(255) UNSIGNED NOT NULL,
  `titel` varbinary(2000) NOT NULL,
  `farbe` int(2) NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `termine_0`
--

CREATE TABLE `termine_0` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `person` bigint(255) UNSIGNED NOT NULL,
  `bezeichnung` varbinary(5000) NOT NULL,
  `ort` varbinary(5000) NOT NULL,
  `beginn` bigint(255) UNSIGNED NOT NULL,
  `ende` bigint(255) UNSIGNED NOT NULL,
  `mehrtaegigt` varbinary(50) NOT NULL,
  `uhrzeitbt` varbinary(50) NOT NULL,
  `uhrzeitet` varbinary(50) NOT NULL,
  `ortt` varbinary(50) NOT NULL,
  `text` longblob NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `postausgang_0`
--
ALTER TABLE `postausgang_0`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nachrichtengesendetpersonen` (`absender`);

--
-- Indizes für die Tabelle `posteingang_0`
--
ALTER TABLE `posteingang_0`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nachrichteneingangpersonen` (`empfaenger`);

--
-- Indizes für die Tabelle `postentwurf_0`
--
ALTER TABLE `postentwurf_0`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nachrichtenentwurf` (`absender`);

--
-- Indizes für die Tabelle `postgetaggedausgang_0`
--
ALTER TABLE `postgetaggedausgang_0`
  ADD UNIQUE KEY `tag` (`tag`,`nachricht`),
  ADD KEY `nachrichtposttaggedausgang_0` (`nachricht`);

--
-- Indizes für die Tabelle `postgetaggedeingang_0`
--
ALTER TABLE `postgetaggedeingang_0`
  ADD UNIQUE KEY `tag` (`tag`,`nachricht`),
  ADD KEY `nachrichtposttaggedeingang_0` (`nachricht`);

--
-- Indizes für die Tabelle `postgetaggedentwurf_0`
--
ALTER TABLE `postgetaggedentwurf_0`
  ADD UNIQUE KEY `tag` (`tag`,`nachricht`),
  ADD KEY `nachrichtposttaggedentwurf_0` (`nachricht`);

--
-- Indizes für die Tabelle `posttags_0`
--
ALTER TABLE `posttags_0`
  ADD PRIMARY KEY (`id`),
  ADD KEY `postfachtagspersonen` (`person`);

--
-- Indizes für die Tabelle `termine_0`
--
ALTER TABLE `termine_0`
  ADD KEY `personentermine_0` (`person`);

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `postausgang_0`
--
ALTER TABLE `postausgang_0`
  ADD CONSTRAINT `personpostausgang_0` FOREIGN KEY (`absender`) REFERENCES `cms_schulhof`.`personen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `posteingang_0`
--
ALTER TABLE `posteingang_0`
  ADD CONSTRAINT `personposteingang_0` FOREIGN KEY (`empfaenger`) REFERENCES `cms_schulhof`.`personen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `postentwurf_0`
--
ALTER TABLE `postentwurf_0`
  ADD CONSTRAINT `personpostentwurf_0` FOREIGN KEY (`absender`) REFERENCES `cms_schulhof`.`personen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `postgetaggedausgang_0`
--
ALTER TABLE `postgetaggedausgang_0`
  ADD CONSTRAINT `nachrichtposttaggedausgang_0` FOREIGN KEY (`nachricht`) REFERENCES `postausgang_0` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tagposttaggedausgang_0` FOREIGN KEY (`tag`) REFERENCES `posttags_0` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `postgetaggedeingang_0`
--
ALTER TABLE `postgetaggedeingang_0`
  ADD CONSTRAINT `nachrichtposttaggedeingang_0` FOREIGN KEY (`nachricht`) REFERENCES `posteingang_0` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tagposttaggedeingang_0` FOREIGN KEY (`tag`) REFERENCES `posttags_0` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `postgetaggedentwurf_0`
--
ALTER TABLE `postgetaggedentwurf_0`
  ADD CONSTRAINT `nachrichtposttaggedentwurf_0` FOREIGN KEY (`nachricht`) REFERENCES `postentwurf_0` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tagposttaggedentwurf_0` FOREIGN KEY (`tag`) REFERENCES `posttags_0` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `posttags_0`
--
ALTER TABLE `posttags_0`
  ADD CONSTRAINT `personenposttags_0` FOREIGN KEY (`person`) REFERENCES `cms_schulhof`.`personen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `termine_0`
--
ALTER TABLE `termine_0`
  ADD CONSTRAINT `personentermine_0` FOREIGN KEY (`person`) REFERENCES `cms_schulhof`.`personen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
