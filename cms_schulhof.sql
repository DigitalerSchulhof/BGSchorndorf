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
-- Datenbank: `cms_schulhof`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `allgemeineeinstellungen`
--

CREATE TABLE `allgemeineeinstellungen` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `inhalt` varbinary(500) NOT NULL,
  `wert` varbinary(5000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `arbeitsgemeinschaften`
--

CREATE TABLE `arbeitsgemeinschaften` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `bezeichnung` varbinary(2000) NOT NULL,
  `icon` varbinary(2000) NOT NULL,
  `sichtbar` int(1) UNSIGNED NOT NULL,
  `schuljahr` bigint(255) UNSIGNED DEFAULT NULL,
  `chataktiv` int(1) UNSIGNED NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `arbeitsgemeinschaftenaufsicht`
--

CREATE TABLE `arbeitsgemeinschaftenaufsicht` (
  `gruppe` bigint(255) UNSIGNED NOT NULL,
  `person` bigint(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `arbeitsgemeinschaftenblogeintraege`
--

CREATE TABLE `arbeitsgemeinschaftenblogeintraege` (
  `gruppe` bigint(255) UNSIGNED NOT NULL,
  `blogeintrag` bigint(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `arbeitsgemeinschaftenblogeintraegeintern`
--

CREATE TABLE `arbeitsgemeinschaftenblogeintraegeintern` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `gruppe` bigint(255) UNSIGNED NOT NULL,
  `bezeichnung` varbinary(5000) NOT NULL,
  `datum` bigint(255) UNSIGNED NOT NULL,
  `genehmigt` int(1) NOT NULL,
  `aktiv` int(1) NOT NULL,
  `text` longblob NOT NULL,
  `vorschau` longblob NOT NULL,
  `autor` varbinary(5000) NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `arbeitsgemeinschaftenblogeintragbeschluesse`
--

CREATE TABLE `arbeitsgemeinschaftenblogeintragbeschluesse` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `blogeintrag` bigint(255) UNSIGNED NOT NULL,
  `titel` varbinary(5000) NOT NULL,
  `langfristig` varbinary(50) NOT NULL,
  `beschreibung` longblob NOT NULL,
  `pro` bigint(255) UNSIGNED NOT NULL,
  `contra` bigint(255) UNSIGNED NOT NULL,
  `enthaltung` bigint(255) UNSIGNED NOT NULL,
  `idvon` bigint(255) NOT NULL,
  `idzeit` bigint(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `arbeitsgemeinschaftenblogeintragdownloads`
--

CREATE TABLE `arbeitsgemeinschaftenblogeintragdownloads` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `blogeintrag` bigint(255) UNSIGNED NOT NULL,
  `pfad` varbinary(5000) NOT NULL,
  `titel` varbinary(5000) NOT NULL,
  `beschreibung` longblob NOT NULL,
  `dateiname` int(1) UNSIGNED NOT NULL,
  `dateigroesse` int(1) UNSIGNED NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `arbeitsgemeinschaftenchat`
--

CREATE TABLE `arbeitsgemeinschaftenchat` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `gruppe` bigint(255) UNSIGNED NOT NULL,
  `person` bigint(255) UNSIGNED NOT NULL,
  `datum` bigint(255) UNSIGNED NOT NULL,
  `inhalt` longblob NOT NULL,
  `meldestatus` int(1) UNSIGNED NOT NULL DEFAULT '0',
  `gemeldetvon` bigint(255) UNSIGNED NOT NULL,
  `gemeldetam` bigint(255) UNSIGNED NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `arbeitsgemeinschaftenmitglieder`
--

CREATE TABLE `arbeitsgemeinschaftenmitglieder` (
  `gruppe` bigint(255) UNSIGNED NOT NULL,
  `person` bigint(255) UNSIGNED NOT NULL,
  `dateiupload` int(1) UNSIGNED NOT NULL,
  `dateidownload` int(1) UNSIGNED NOT NULL,
  `dateiloeschen` int(1) UNSIGNED NOT NULL,
  `dateiumbenennen` int(1) UNSIGNED NOT NULL,
  `termine` int(1) UNSIGNED NOT NULL,
  `blogeintraege` int(1) UNSIGNED NOT NULL,
  `chatten` int(1) UNSIGNED NOT NULL,
  `chattenab` bigint(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `arbeitsgemeinschaftennotifikationsabo`
--

CREATE TABLE `arbeitsgemeinschaftennotifikationsabo` (
  `gruppe` bigint(255) UNSIGNED NOT NULL,
  `person` bigint(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `arbeitsgemeinschaftentermine`
--

CREATE TABLE `arbeitsgemeinschaftentermine` (
  `gruppe` bigint(255) UNSIGNED NOT NULL,
  `termin` bigint(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `arbeitsgemeinschaftentermineintern`
--

CREATE TABLE `arbeitsgemeinschaftentermineintern` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `gruppe` bigint(255) UNSIGNED NOT NULL,
  `bezeichnung` varbinary(5000) NOT NULL,
  `ort` varbinary(5000) NOT NULL,
  `beginn` bigint(255) UNSIGNED NOT NULL,
  `ende` bigint(255) UNSIGNED NOT NULL,
  `mehrtaegigt` int(1) NOT NULL,
  `uhrzeitbt` int(1) NOT NULL,
  `uhrzeitet` int(1) NOT NULL,
  `ortt` int(1) NOT NULL,
  `genehmigt` int(1) NOT NULL,
  `aktiv` int(1) NOT NULL,
  `text` longblob NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `arbeitsgemeinschaftentermineinterndownloads`
--

CREATE TABLE `arbeitsgemeinschaftentermineinterndownloads` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `termin` bigint(255) UNSIGNED NOT NULL,
  `pfad` varbinary(5000) NOT NULL,
  `titel` varbinary(5000) NOT NULL,
  `beschreibung` longblob NOT NULL,
  `dateiname` int(1) UNSIGNED NOT NULL,
  `dateigroesse` int(1) UNSIGNED NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `arbeitsgemeinschaftenvorsitz`
--

CREATE TABLE `arbeitsgemeinschaftenvorsitz` (
  `gruppe` bigint(255) UNSIGNED NOT NULL,
  `person` bigint(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `arbeitskreise`
--

CREATE TABLE `arbeitskreise` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `bezeichnung` varbinary(2000) NOT NULL,
  `icon` varbinary(2000) NOT NULL,
  `sichtbar` int(1) UNSIGNED NOT NULL,
  `schuljahr` bigint(255) UNSIGNED DEFAULT NULL,
  `chataktiv` int(1) UNSIGNED NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `arbeitskreiseaufsicht`
--

CREATE TABLE `arbeitskreiseaufsicht` (
  `gruppe` bigint(255) UNSIGNED NOT NULL,
  `person` bigint(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `arbeitskreiseblogeintraege`
--

CREATE TABLE `arbeitskreiseblogeintraege` (
  `gruppe` bigint(255) UNSIGNED NOT NULL,
  `blogeintrag` bigint(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `arbeitskreiseblogeintraegeintern`
--

CREATE TABLE `arbeitskreiseblogeintraegeintern` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `gruppe` bigint(255) UNSIGNED NOT NULL,
  `bezeichnung` varbinary(5000) NOT NULL,
  `datum` bigint(255) UNSIGNED NOT NULL,
  `genehmigt` int(1) NOT NULL,
  `aktiv` int(1) NOT NULL,
  `text` longblob NOT NULL,
  `vorschau` longblob NOT NULL,
  `autor` varbinary(5000) NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `arbeitskreiseblogeintragbeschluesse`
--

CREATE TABLE `arbeitskreiseblogeintragbeschluesse` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `blogeintrag` bigint(255) UNSIGNED NOT NULL,
  `titel` varbinary(5000) NOT NULL,
  `langfristig` varbinary(50) NOT NULL,
  `beschreibung` longblob NOT NULL,
  `pro` bigint(255) UNSIGNED NOT NULL,
  `contra` bigint(255) UNSIGNED NOT NULL,
  `enthaltung` bigint(255) UNSIGNED NOT NULL,
  `idvon` bigint(255) NOT NULL,
  `idzeit` bigint(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `arbeitskreiseblogeintragdownloads`
--

CREATE TABLE `arbeitskreiseblogeintragdownloads` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `blogeintrag` bigint(255) UNSIGNED NOT NULL,
  `pfad` varbinary(5000) NOT NULL,
  `titel` varbinary(5000) NOT NULL,
  `beschreibung` longblob NOT NULL,
  `dateiname` int(1) UNSIGNED NOT NULL,
  `dateigroesse` int(1) UNSIGNED NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `arbeitskreisechat`
--

CREATE TABLE `arbeitskreisechat` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `gruppe` bigint(255) UNSIGNED NOT NULL,
  `person` bigint(255) UNSIGNED NOT NULL,
  `datum` bigint(255) UNSIGNED NOT NULL,
  `inhalt` longblob NOT NULL,
  `meldestatus` int(1) UNSIGNED NOT NULL DEFAULT '0',
  `gemeldetvon` bigint(255) UNSIGNED NOT NULL,
  `gemeldetam` bigint(255) UNSIGNED NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `arbeitskreisemitglieder`
--

CREATE TABLE `arbeitskreisemitglieder` (
  `gruppe` bigint(255) UNSIGNED NOT NULL,
  `person` bigint(255) UNSIGNED NOT NULL,
  `dateiupload` int(1) UNSIGNED NOT NULL,
  `dateidownload` int(1) UNSIGNED NOT NULL,
  `dateiloeschen` int(1) UNSIGNED NOT NULL,
  `dateiumbenennen` int(1) UNSIGNED NOT NULL,
  `termine` int(1) UNSIGNED NOT NULL,
  `blogeintraege` int(1) UNSIGNED NOT NULL,
  `chatten` int(1) UNSIGNED NOT NULL,
  `chattenab` bigint(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `arbeitskreisenotifikationsabo`
--

CREATE TABLE `arbeitskreisenotifikationsabo` (
  `gruppe` bigint(255) UNSIGNED NOT NULL,
  `person` bigint(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `arbeitskreisetermine`
--

CREATE TABLE `arbeitskreisetermine` (
  `gruppe` bigint(255) UNSIGNED NOT NULL,
  `termin` bigint(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `arbeitskreisetermineintern`
--

CREATE TABLE `arbeitskreisetermineintern` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `gruppe` bigint(255) UNSIGNED NOT NULL,
  `bezeichnung` varbinary(5000) NOT NULL,
  `ort` varbinary(5000) NOT NULL,
  `beginn` bigint(255) UNSIGNED NOT NULL,
  `ende` bigint(255) UNSIGNED NOT NULL,
  `mehrtaegigt` int(1) NOT NULL,
  `uhrzeitbt` int(1) NOT NULL,
  `uhrzeitet` int(1) NOT NULL,
  `ortt` int(1) NOT NULL,
  `genehmigt` int(1) NOT NULL,
  `aktiv` int(1) NOT NULL,
  `text` longblob NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `arbeitskreisetermineinterndownloads`
--

CREATE TABLE `arbeitskreisetermineinterndownloads` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `termin` bigint(255) UNSIGNED NOT NULL,
  `pfad` varbinary(5000) NOT NULL,
  `titel` varbinary(5000) NOT NULL,
  `beschreibung` longblob NOT NULL,
  `dateiname` int(1) UNSIGNED NOT NULL,
  `dateigroesse` int(1) UNSIGNED NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `arbeitskreisevorsitz`
--

CREATE TABLE `arbeitskreisevorsitz` (
  `gruppe` bigint(255) UNSIGNED NOT NULL,
  `person` bigint(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `auffaelliges`
--

CREATE TABLE `auffaelliges` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `ursacher` bigint(255) UNSIGNED NOT NULL,
  `typ` int(1) UNSIGNED NOT NULL,
  `aktion` varbinary(5000) NOT NULL,
  `eingaben` varbinary(5000) NOT NULL,
  `details` varbinary(5000) NOT NULL,
  `notizen` varbinary(5000) NOT NULL,
  `zeitstempel` bigint(255) UNSIGNED NOT NULL,
  `status` bigint(255) UNSIGNED NOT NULL,
  `idvon` bigint(255) UNSIGNED NOT NULL,
  `idzeit` bigint(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `besucherstatistik_blog`
--

CREATE TABLE `besucherstatistik_blog` (
  `jahr` int(4) UNSIGNED NOT NULL,
  `monat` int(2) UNSIGNED NOT NULL,
  `id` bigint(255) UNSIGNED NOT NULL,
  `aufrufe` bigint(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `besucherstatistik_galerien`
--

CREATE TABLE `besucherstatistik_galerien` (
  `jahr` int(4) UNSIGNED NOT NULL,
  `monat` int(2) UNSIGNED NOT NULL,
  `id` bigint(255) UNSIGNED NOT NULL,
  `aufrufe` bigint(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `besucherstatistik_schulhof`
--

CREATE TABLE `besucherstatistik_schulhof` (
  `jahr` int(11) NOT NULL,
  `monat` int(11) NOT NULL,
  `rolle` text NOT NULL,
  `url` text NOT NULL,
  `aufrufe` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `besucherstatistik_termine`
--

CREATE TABLE `besucherstatistik_termine` (
  `jahr` int(4) UNSIGNED NOT NULL,
  `monat` int(2) UNSIGNED NOT NULL,
  `id` bigint(255) UNSIGNED NOT NULL,
  `aufrufe` bigint(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `besucherstatistik_website`
--

CREATE TABLE `besucherstatistik_website` (
  `jahr` int(4) UNSIGNED NOT NULL,
  `monat` int(2) UNSIGNED NOT NULL,
  `id` bigint(255) UNSIGNED NOT NULL,
  `aufrufe` bigint(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `blogeintraege`
--

CREATE TABLE `blogeintraege` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `bezeichnung` varbinary(5000) NOT NULL,
  `datum` bigint(255) UNSIGNED NOT NULL,
  `genehmigt` int(1) NOT NULL,
  `aktiv` int(1) NOT NULL,
  `oeffentlichkeit` int(1) NOT NULL,
  `text` longblob NOT NULL,
  `vorschau` longblob NOT NULL,
  `vorschaubild` varbinary(5000) NOT NULL,
  `autor` varbinary(5000) NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `blogeintragdownloads`
--

CREATE TABLE `blogeintragdownloads` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `blogeintrag` bigint(255) UNSIGNED NOT NULL,
  `pfad` varbinary(5000) NOT NULL,
  `titel` varbinary(5000) NOT NULL,
  `beschreibung` longblob NOT NULL,
  `dateiname` int(1) UNSIGNED NOT NULL,
  `dateigroesse` int(1) UNSIGNED NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `boxen`
--

CREATE TABLE `boxen` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `boxaussen` bigint(255) UNSIGNED NOT NULL,
  `position` bigint(255) UNSIGNED NOT NULL,
  `aktiv` varchar(1) COLLATE utf8_unicode_ci NOT NULL,
  `titelalt` text COLLATE utf8_unicode_ci NOT NULL,
  `titelaktuell` text COLLATE utf8_unicode_ci NOT NULL,
  `titelneu` text COLLATE utf8_unicode_ci NOT NULL,
  `inhaltalt` longtext COLLATE utf8_unicode_ci NOT NULL,
  `inhaltaktuell` longtext COLLATE utf8_unicode_ci NOT NULL,
  `inhaltneu` longtext COLLATE utf8_unicode_ci NOT NULL,
  `stylealt` int(255) UNSIGNED NOT NULL,
  `styleaktuell` int(255) UNSIGNED NOT NULL,
  `styleneu` int(255) UNSIGNED NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `boxenaussen`
--

CREATE TABLE `boxenaussen` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `spalte` bigint(255) UNSIGNED NOT NULL,
  `position` bigint(255) UNSIGNED NOT NULL,
  `aktiv` varchar(1) COLLATE utf8_unicode_ci NOT NULL,
  `ausrichtungalt` varchar(1) COLLATE utf8_unicode_ci NOT NULL,
  `ausrichtungaktuell` varchar(1) COLLATE utf8_unicode_ci NOT NULL,
  `ausrichtungneu` varchar(1) COLLATE utf8_unicode_ci NOT NULL,
  `breitealt` bigint(255) UNSIGNED NOT NULL,
  `breiteaktuell` bigint(255) UNSIGNED NOT NULL,
  `breiteneu` bigint(255) UNSIGNED NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dauerbrenner`
--

CREATE TABLE `dauerbrenner` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `bezeichnung` varbinary(5000) NOT NULL,
  `sichtbars` int(1) UNSIGNED NOT NULL DEFAULT '0',
  `sichtbarl` int(1) UNSIGNED NOT NULL DEFAULT '0',
  `sichtbare` int(1) UNSIGNED NOT NULL DEFAULT '0',
  `sichtbarv` int(1) UNSIGNED NOT NULL DEFAULT '0',
  `sichtbarx` int(1) UNSIGNED NOT NULL DEFAULT '0',
  `inhalt` longblob NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `downloads`
--

CREATE TABLE `downloads` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `spalte` bigint(255) UNSIGNED NOT NULL,
  `position` bigint(255) UNSIGNED NOT NULL,
  `aktiv` varchar(1) COLLATE utf8_unicode_ci NOT NULL,
  `pfadalt` text COLLATE utf8_unicode_ci NOT NULL,
  `pfadaktuell` text COLLATE utf8_unicode_ci NOT NULL,
  `pfadneu` text COLLATE utf8_unicode_ci NOT NULL,
  `titelalt` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `titelaktuell` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `titelneu` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `beschreibungalt` text COLLATE utf8_unicode_ci NOT NULL,
  `beschreibungaktuell` text COLLATE utf8_unicode_ci NOT NULL,
  `beschreibungneu` text COLLATE utf8_unicode_ci NOT NULL,
  `dateinamealt` varchar(1) COLLATE utf8_unicode_ci NOT NULL,
  `dateinameaktuell` varchar(1) COLLATE utf8_unicode_ci NOT NULL,
  `dateinameneu` varchar(1) COLLATE utf8_unicode_ci NOT NULL,
  `dateigroessealt` varchar(1) COLLATE utf8_unicode_ci NOT NULL,
  `dateigroesseaktuell` varchar(1) COLLATE utf8_unicode_ci NOT NULL,
  `dateigroesseneu` varchar(1) COLLATE utf8_unicode_ci NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `editoren`
--

CREATE TABLE `editoren` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `spalte` bigint(255) UNSIGNED NOT NULL,
  `position` bigint(255) UNSIGNED NOT NULL,
  `aktiv` varchar(1) COLLATE utf8_unicode_ci NOT NULL,
  `alt` longtext COLLATE utf8_unicode_ci NOT NULL,
  `aktuell` longtext COLLATE utf8_unicode_ci NOT NULL,
  `neu` longtext COLLATE utf8_unicode_ci NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `emoticons`
--

CREATE TABLE `emoticons` (
  `id` varchar(5000) NOT NULL,
  `aktiv` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ereignisse`
--

CREATE TABLE `ereignisse` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `bezeichnung` varbinary(2000) NOT NULL,
  `icon` varbinary(2000) NOT NULL,
  `sichtbar` int(1) UNSIGNED NOT NULL,
  `schuljahr` bigint(255) UNSIGNED DEFAULT NULL,
  `chataktiv` int(1) UNSIGNED NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ereignisseaufsicht`
--

CREATE TABLE `ereignisseaufsicht` (
  `gruppe` bigint(255) UNSIGNED NOT NULL,
  `person` bigint(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ereignisseblogeintraege`
--

CREATE TABLE `ereignisseblogeintraege` (
  `gruppe` bigint(255) UNSIGNED NOT NULL,
  `blogeintrag` bigint(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ereignisseblogeintraegeintern`
--

CREATE TABLE `ereignisseblogeintraegeintern` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `gruppe` bigint(255) UNSIGNED NOT NULL,
  `bezeichnung` varbinary(5000) NOT NULL,
  `datum` bigint(255) UNSIGNED NOT NULL,
  `genehmigt` int(1) NOT NULL,
  `aktiv` int(1) NOT NULL,
  `text` longblob NOT NULL,
  `vorschau` longblob NOT NULL,
  `autor` varbinary(5000) NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ereignisseblogeintragbeschluesse`
--

CREATE TABLE `ereignisseblogeintragbeschluesse` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `blogeintrag` bigint(255) UNSIGNED NOT NULL,
  `titel` varbinary(5000) NOT NULL,
  `langfristig` varbinary(50) NOT NULL,
  `beschreibung` longblob NOT NULL,
  `pro` bigint(255) UNSIGNED NOT NULL,
  `contra` bigint(255) UNSIGNED NOT NULL,
  `enthaltung` bigint(255) UNSIGNED NOT NULL,
  `idvon` bigint(255) NOT NULL,
  `idzeit` bigint(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ereignisseblogeintragdownloads`
--

CREATE TABLE `ereignisseblogeintragdownloads` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `blogeintrag` bigint(255) UNSIGNED NOT NULL,
  `pfad` varbinary(5000) NOT NULL,
  `titel` varbinary(5000) NOT NULL,
  `beschreibung` longblob NOT NULL,
  `dateiname` int(1) UNSIGNED NOT NULL,
  `dateigroesse` int(1) UNSIGNED NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ereignissechat`
--

CREATE TABLE `ereignissechat` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `gruppe` bigint(255) UNSIGNED NOT NULL,
  `person` bigint(255) UNSIGNED NOT NULL,
  `datum` bigint(255) UNSIGNED NOT NULL,
  `inhalt` longblob NOT NULL,
  `meldestatus` int(1) UNSIGNED NOT NULL DEFAULT '0',
  `gemeldetvon` bigint(255) UNSIGNED NOT NULL,
  `gemeldetam` bigint(255) UNSIGNED NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ereignissemitglieder`
--

CREATE TABLE `ereignissemitglieder` (
  `gruppe` bigint(255) UNSIGNED NOT NULL,
  `person` bigint(255) UNSIGNED NOT NULL,
  `dateiupload` int(1) UNSIGNED NOT NULL,
  `dateidownload` int(1) UNSIGNED NOT NULL,
  `dateiloeschen` int(1) UNSIGNED NOT NULL,
  `dateiumbenennen` int(1) UNSIGNED NOT NULL,
  `termine` int(1) UNSIGNED NOT NULL,
  `blogeintraege` int(1) UNSIGNED NOT NULL,
  `chatten` int(1) UNSIGNED NOT NULL,
  `chattenab` bigint(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ereignissenotifikationsabo`
--

CREATE TABLE `ereignissenotifikationsabo` (
  `gruppe` bigint(255) UNSIGNED NOT NULL,
  `person` bigint(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ereignissetermine`
--

CREATE TABLE `ereignissetermine` (
  `gruppe` bigint(255) UNSIGNED NOT NULL,
  `termin` bigint(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ereignissetermineintern`
--

CREATE TABLE `ereignissetermineintern` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `gruppe` bigint(255) UNSIGNED NOT NULL,
  `bezeichnung` varbinary(5000) NOT NULL,
  `ort` varbinary(5000) NOT NULL,
  `beginn` bigint(255) UNSIGNED NOT NULL,
  `ende` bigint(255) UNSIGNED NOT NULL,
  `mehrtaegigt` int(1) NOT NULL,
  `uhrzeitbt` int(1) NOT NULL,
  `uhrzeitet` int(1) NOT NULL,
  `ortt` int(1) NOT NULL,
  `genehmigt` int(1) NOT NULL,
  `aktiv` int(1) NOT NULL,
  `text` longblob NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ereignissetermineinterndownloads`
--

CREATE TABLE `ereignissetermineinterndownloads` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `termin` bigint(255) UNSIGNED NOT NULL,
  `pfad` varbinary(5000) NOT NULL,
  `titel` varbinary(5000) NOT NULL,
  `beschreibung` longblob NOT NULL,
  `dateiname` int(1) UNSIGNED NOT NULL,
  `dateigroesse` int(1) UNSIGNED NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ereignissevorsitz`
--

CREATE TABLE `ereignissevorsitz` (
  `gruppe` bigint(255) UNSIGNED NOT NULL,
  `person` bigint(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `eventuebersichten`
--

CREATE TABLE `eventuebersichten` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `spalte` bigint(255) UNSIGNED NOT NULL,
  `position` bigint(255) UNSIGNED NOT NULL,
  `aktiv` varchar(1) COLLATE utf8_unicode_ci NOT NULL,
  `terminealt` varchar(1) COLLATE utf8_unicode_ci NOT NULL,
  `termineaktuell` varchar(1) COLLATE utf8_unicode_ci NOT NULL,
  `termineneu` varchar(1) COLLATE utf8_unicode_ci NOT NULL,
  `termineanzahlalt` int(255) UNSIGNED NOT NULL,
  `termineanzahlaktuell` int(255) UNSIGNED NOT NULL,
  `termineanzahlneu` int(255) UNSIGNED NOT NULL,
  `blogalt` varchar(1) COLLATE utf8_unicode_ci NOT NULL,
  `blogaktuell` varchar(1) COLLATE utf8_unicode_ci NOT NULL,
  `blogneu` varchar(1) COLLATE utf8_unicode_ci NOT NULL,
  `bloganzahlalt` int(255) UNSIGNED NOT NULL,
  `bloganzahlaktuell` int(255) UNSIGNED NOT NULL,
  `bloganzahlneu` int(255) UNSIGNED NOT NULL,
  `galeriealt` varchar(1) COLLATE utf8_unicode_ci NOT NULL,
  `galerieaktuell` varchar(1) COLLATE utf8_unicode_ci NOT NULL,
  `galerieneu` varchar(1) COLLATE utf8_unicode_ci NOT NULL,
  `galerieanzahlalt` int(255) UNSIGNED NOT NULL,
  `galerieanzahlaktuell` int(255) UNSIGNED NOT NULL,
  `galerieanzahlneu` int(255) UNSIGNED NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fachkollegen`
--

CREATE TABLE `fachkollegen` (
  `fach` bigint(255) UNSIGNED NOT NULL,
  `kollege` bigint(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fachschaften`
--

CREATE TABLE `fachschaften` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `bezeichnung` varbinary(2000) NOT NULL,
  `icon` varbinary(2000) NOT NULL,
  `sichtbar` int(1) UNSIGNED NOT NULL,
  `schuljahr` bigint(255) UNSIGNED DEFAULT NULL,
  `chataktiv` int(1) UNSIGNED NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fachschaftenaufsicht`
--

CREATE TABLE `fachschaftenaufsicht` (
  `gruppe` bigint(255) UNSIGNED NOT NULL,
  `person` bigint(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fachschaftenblogeintraege`
--

CREATE TABLE `fachschaftenblogeintraege` (
  `gruppe` bigint(255) UNSIGNED NOT NULL,
  `blogeintrag` bigint(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fachschaftenblogeintraegeintern`
--

CREATE TABLE `fachschaftenblogeintraegeintern` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `gruppe` bigint(255) UNSIGNED NOT NULL,
  `bezeichnung` varbinary(5000) NOT NULL,
  `datum` bigint(255) UNSIGNED NOT NULL,
  `genehmigt` int(1) NOT NULL,
  `aktiv` int(1) NOT NULL,
  `text` longblob NOT NULL,
  `vorschau` longblob NOT NULL,
  `autor` varbinary(5000) NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fachschaftenblogeintragbeschluesse`
--

CREATE TABLE `fachschaftenblogeintragbeschluesse` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `blogeintrag` bigint(255) UNSIGNED NOT NULL,
  `titel` varbinary(5000) NOT NULL,
  `langfristig` varbinary(50) NOT NULL,
  `beschreibung` longblob NOT NULL,
  `pro` bigint(255) UNSIGNED NOT NULL,
  `contra` bigint(255) UNSIGNED NOT NULL,
  `enthaltung` bigint(255) UNSIGNED NOT NULL,
  `idvon` bigint(255) NOT NULL,
  `idzeit` bigint(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fachschaftenblogeintragdownloads`
--

CREATE TABLE `fachschaftenblogeintragdownloads` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `blogeintrag` bigint(255) UNSIGNED NOT NULL,
  `pfad` varbinary(5000) NOT NULL,
  `titel` varbinary(5000) NOT NULL,
  `beschreibung` longblob NOT NULL,
  `dateiname` int(1) UNSIGNED NOT NULL,
  `dateigroesse` int(1) UNSIGNED NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fachschaftenchat`
--

CREATE TABLE `fachschaftenchat` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `gruppe` bigint(255) UNSIGNED NOT NULL,
  `person` bigint(255) UNSIGNED NOT NULL,
  `datum` bigint(255) UNSIGNED NOT NULL,
  `inhalt` longblob NOT NULL,
  `meldestatus` int(1) UNSIGNED NOT NULL DEFAULT '0',
  `gemeldetvon` bigint(255) UNSIGNED NOT NULL,
  `gemeldetam` bigint(255) UNSIGNED NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fachschaftenmitglieder`
--

CREATE TABLE `fachschaftenmitglieder` (
  `gruppe` bigint(255) UNSIGNED NOT NULL,
  `person` bigint(255) UNSIGNED NOT NULL,
  `dateiupload` int(1) UNSIGNED NOT NULL,
  `dateidownload` int(1) UNSIGNED NOT NULL,
  `dateiloeschen` int(1) UNSIGNED NOT NULL,
  `dateiumbenennen` int(1) UNSIGNED NOT NULL,
  `termine` int(1) UNSIGNED NOT NULL,
  `blogeintraege` int(1) UNSIGNED NOT NULL,
  `chatten` int(1) UNSIGNED NOT NULL,
  `chattenab` bigint(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fachschaftennotifikationsabo`
--

CREATE TABLE `fachschaftennotifikationsabo` (
  `gruppe` bigint(255) UNSIGNED NOT NULL,
  `person` bigint(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fachschaftentermine`
--

CREATE TABLE `fachschaftentermine` (
  `gruppe` bigint(255) UNSIGNED NOT NULL,
  `termin` bigint(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fachschaftentermineintern`
--

CREATE TABLE `fachschaftentermineintern` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `gruppe` bigint(255) UNSIGNED NOT NULL,
  `bezeichnung` varbinary(5000) NOT NULL,
  `ort` varbinary(5000) NOT NULL,
  `beginn` bigint(255) UNSIGNED NOT NULL,
  `ende` bigint(255) UNSIGNED NOT NULL,
  `mehrtaegigt` int(1) NOT NULL,
  `uhrzeitbt` int(1) NOT NULL,
  `uhrzeitet` int(1) NOT NULL,
  `ortt` int(1) NOT NULL,
  `genehmigt` int(1) NOT NULL,
  `aktiv` int(1) NOT NULL,
  `text` longblob NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fachschaftentermineinterndownloads`
--

CREATE TABLE `fachschaftentermineinterndownloads` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `termin` bigint(255) UNSIGNED NOT NULL,
  `pfad` varbinary(5000) NOT NULL,
  `titel` varbinary(5000) NOT NULL,
  `beschreibung` longblob NOT NULL,
  `dateiname` int(1) UNSIGNED NOT NULL,
  `dateigroesse` int(1) UNSIGNED NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fachschaftenvorsitz`
--

CREATE TABLE `fachschaftenvorsitz` (
  `gruppe` bigint(255) UNSIGNED NOT NULL,
  `person` bigint(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `faecher`
--

CREATE TABLE `faecher` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `schuljahr` bigint(255) UNSIGNED NOT NULL,
  `bezeichnung` varbinary(5000) NOT NULL,
  `kuerzel` varbinary(2000) NOT NULL,
  `farbe` int(2) UNSIGNED NOT NULL,
  `icon` varbinary(500) NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fahrten`
--

CREATE TABLE `fahrten` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `bezeichnung` varbinary(2000) NOT NULL,
  `icon` varbinary(2000) NOT NULL,
  `sichtbar` int(1) UNSIGNED NOT NULL,
  `schuljahr` bigint(255) UNSIGNED DEFAULT NULL,
  `chataktiv` int(1) UNSIGNED NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fahrtenaufsicht`
--

CREATE TABLE `fahrtenaufsicht` (
  `gruppe` bigint(255) UNSIGNED NOT NULL,
  `person` bigint(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fahrtenblogeintraege`
--

CREATE TABLE `fahrtenblogeintraege` (
  `gruppe` bigint(255) UNSIGNED NOT NULL,
  `blogeintrag` bigint(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fahrtenblogeintraegeintern`
--

CREATE TABLE `fahrtenblogeintraegeintern` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `gruppe` bigint(255) UNSIGNED NOT NULL,
  `bezeichnung` varbinary(5000) NOT NULL,
  `datum` bigint(255) UNSIGNED NOT NULL,
  `genehmigt` int(1) NOT NULL,
  `aktiv` int(1) NOT NULL,
  `text` longblob NOT NULL,
  `vorschau` longblob NOT NULL,
  `autor` varbinary(5000) NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fahrtenblogeintragbeschluesse`
--

CREATE TABLE `fahrtenblogeintragbeschluesse` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `blogeintrag` bigint(255) UNSIGNED NOT NULL,
  `titel` varbinary(5000) NOT NULL,
  `langfristig` varbinary(50) NOT NULL,
  `beschreibung` longblob NOT NULL,
  `pro` bigint(255) UNSIGNED NOT NULL,
  `contra` bigint(255) UNSIGNED NOT NULL,
  `enthaltung` bigint(255) UNSIGNED NOT NULL,
  `idvon` bigint(255) NOT NULL,
  `idzeit` bigint(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fahrtenblogeintragdownloads`
--

CREATE TABLE `fahrtenblogeintragdownloads` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `blogeintrag` bigint(255) UNSIGNED NOT NULL,
  `pfad` varbinary(5000) NOT NULL,
  `titel` varbinary(5000) NOT NULL,
  `beschreibung` longblob NOT NULL,
  `dateiname` int(1) UNSIGNED NOT NULL,
  `dateigroesse` int(1) UNSIGNED NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fahrtenchat`
--

CREATE TABLE `fahrtenchat` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `gruppe` bigint(255) UNSIGNED NOT NULL,
  `person` bigint(255) UNSIGNED NOT NULL,
  `datum` bigint(255) UNSIGNED NOT NULL,
  `inhalt` longblob NOT NULL,
  `meldestatus` int(1) UNSIGNED NOT NULL DEFAULT '0',
  `gemeldetvon` bigint(255) UNSIGNED NOT NULL,
  `gemeldetam` bigint(255) UNSIGNED NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fahrtenmitglieder`
--

CREATE TABLE `fahrtenmitglieder` (
  `gruppe` bigint(255) UNSIGNED NOT NULL,
  `person` bigint(255) UNSIGNED NOT NULL,
  `dateiupload` int(1) UNSIGNED NOT NULL,
  `dateidownload` int(1) UNSIGNED NOT NULL,
  `dateiloeschen` int(1) UNSIGNED NOT NULL,
  `dateiumbenennen` int(1) UNSIGNED NOT NULL,
  `termine` int(1) UNSIGNED NOT NULL,
  `blogeintraege` int(1) UNSIGNED NOT NULL,
  `chatten` int(1) UNSIGNED NOT NULL,
  `chattenab` bigint(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fahrtennotifikationsabo`
--

CREATE TABLE `fahrtennotifikationsabo` (
  `gruppe` bigint(255) UNSIGNED NOT NULL,
  `person` bigint(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fahrtentermine`
--

CREATE TABLE `fahrtentermine` (
  `gruppe` bigint(255) UNSIGNED NOT NULL,
  `termin` bigint(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fahrtentermineintern`
--

CREATE TABLE `fahrtentermineintern` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `gruppe` bigint(255) UNSIGNED NOT NULL,
  `bezeichnung` varbinary(5000) NOT NULL,
  `ort` varbinary(5000) NOT NULL,
  `beginn` bigint(255) UNSIGNED NOT NULL,
  `ende` bigint(255) UNSIGNED NOT NULL,
  `mehrtaegigt` int(1) NOT NULL,
  `uhrzeitbt` int(1) NOT NULL,
  `uhrzeitet` int(1) NOT NULL,
  `ortt` int(1) NOT NULL,
  `genehmigt` int(1) NOT NULL,
  `aktiv` int(1) NOT NULL,
  `text` longblob NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fahrtentermineinterndownloads`
--

CREATE TABLE `fahrtentermineinterndownloads` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `termin` bigint(255) UNSIGNED NOT NULL,
  `pfad` varbinary(5000) NOT NULL,
  `titel` varbinary(5000) NOT NULL,
  `beschreibung` longblob NOT NULL,
  `dateiname` int(1) UNSIGNED NOT NULL,
  `dateigroesse` int(1) UNSIGNED NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fahrtenvorsitz`
--

CREATE TABLE `fahrtenvorsitz` (
  `gruppe` bigint(255) UNSIGNED NOT NULL,
  `person` bigint(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `feedback`
--

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `feedback` text NOT NULL,
  `zeitstempel` int(11) NOT NULL,
  `sichtbar` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fehlermeldungen`
--

CREATE TABLE `fehlermeldungen` (
  `id` int(11) NOT NULL,
  `ersteller` text NOT NULL,
  `url` text NOT NULL,
  `titel` text NOT NULL,
  `beschreibung` text NOT NULL,
  `header` text NOT NULL,
  `session` text NOT NULL,
  `zeitstempel` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `sichtbar` int(11) NOT NULL,
  `notizen` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ferien`
--

CREATE TABLE `ferien` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `bezeichnung` varchar(5000) COLLATE utf8_unicode_ci NOT NULL,
  `art` varchar(1) COLLATE utf8_unicode_ci NOT NULL,
  `beginn` bigint(255) UNSIGNED NOT NULL,
  `ende` bigint(255) UNSIGNED NOT NULL,
  `mehrtaegigt` int(1) NOT NULL,
  `idvon` bigint(255) UNSIGNED NOT NULL,
  `idzeit` bigint(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `galerien`
--

CREATE TABLE `galerien` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `gruppe` varchar(2000) COLLATE utf8_unicode_ci NOT NULL,
  `gruppenid` bigint(255) UNSIGNED NOT NULL,
  `titelalt` varchar(2000) COLLATE utf8_unicode_ci NOT NULL,
  `titelaktuell` varchar(2000) COLLATE utf8_unicode_ci NOT NULL,
  `titelneu` varchar(2000) COLLATE utf8_unicode_ci NOT NULL,
  `beschreibungalt` longtext COLLATE utf8_unicode_ci NOT NULL,
  `beschreibungaktuell` longtext COLLATE utf8_unicode_ci NOT NULL,
  `beschreibungneu` longtext COLLATE utf8_unicode_ci NOT NULL,
  `datumalt` bigint(255) UNSIGNED NOT NULL,
  `datumaktuell` bigint(255) UNSIGNED NOT NULL,
  `datumneu` bigint(255) UNSIGNED NOT NULL,
  `aktiv` varchar(1) COLLATE utf8_unicode_ci NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `gremien`
--

CREATE TABLE `gremien` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `bezeichnung` varbinary(2000) NOT NULL,
  `icon` varbinary(2000) NOT NULL,
  `sichtbar` int(1) UNSIGNED NOT NULL,
  `schuljahr` bigint(255) UNSIGNED DEFAULT NULL,
  `chataktiv` int(1) UNSIGNED NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `gremienaufsicht`
--

CREATE TABLE `gremienaufsicht` (
  `gruppe` bigint(255) UNSIGNED NOT NULL,
  `person` bigint(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `gremienblogeintraege`
--

CREATE TABLE `gremienblogeintraege` (
  `gruppe` bigint(255) UNSIGNED NOT NULL,
  `blogeintrag` bigint(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `gremienblogeintraegeintern`
--

CREATE TABLE `gremienblogeintraegeintern` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `gruppe` bigint(255) UNSIGNED NOT NULL,
  `bezeichnung` varbinary(5000) NOT NULL,
  `datum` bigint(255) UNSIGNED NOT NULL,
  `genehmigt` int(1) NOT NULL,
  `aktiv` int(1) NOT NULL,
  `text` longblob NOT NULL,
  `vorschau` longblob NOT NULL,
  `autor` varbinary(5000) NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `gremienblogeintragbeschluesse`
--

CREATE TABLE `gremienblogeintragbeschluesse` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `blogeintrag` bigint(255) UNSIGNED NOT NULL,
  `titel` varbinary(5000) NOT NULL,
  `langfristig` varbinary(50) NOT NULL,
  `beschreibung` longblob NOT NULL,
  `pro` bigint(255) UNSIGNED NOT NULL,
  `contra` bigint(255) UNSIGNED NOT NULL,
  `enthaltung` bigint(255) UNSIGNED NOT NULL,
  `idvon` bigint(255) NOT NULL,
  `idzeit` bigint(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `gremienblogeintragdownloads`
--

CREATE TABLE `gremienblogeintragdownloads` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `blogeintrag` bigint(255) UNSIGNED NOT NULL,
  `pfad` varbinary(5000) NOT NULL,
  `titel` varbinary(5000) NOT NULL,
  `beschreibung` longblob NOT NULL,
  `dateiname` int(1) UNSIGNED NOT NULL,
  `dateigroesse` int(1) UNSIGNED NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `gremienchat`
--

CREATE TABLE `gremienchat` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `gruppe` bigint(255) UNSIGNED NOT NULL,
  `person` bigint(255) UNSIGNED NOT NULL,
  `datum` bigint(255) UNSIGNED NOT NULL,
  `inhalt` longblob NOT NULL,
  `meldestatus` int(1) UNSIGNED NOT NULL DEFAULT '0',
  `gemeldetvon` bigint(255) UNSIGNED NOT NULL,
  `gemeldetam` bigint(255) UNSIGNED NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `gremienmitglieder`
--

CREATE TABLE `gremienmitglieder` (
  `gruppe` bigint(255) UNSIGNED NOT NULL,
  `person` bigint(255) UNSIGNED NOT NULL,
  `dateiupload` int(1) UNSIGNED NOT NULL,
  `dateidownload` int(1) UNSIGNED NOT NULL,
  `dateiloeschen` int(1) UNSIGNED NOT NULL,
  `dateiumbenennen` int(1) UNSIGNED NOT NULL,
  `termine` int(1) UNSIGNED NOT NULL,
  `blogeintraege` int(1) UNSIGNED NOT NULL,
  `chatten` int(1) UNSIGNED NOT NULL,
  `chattenab` bigint(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `gremiennotifikationsabo`
--

CREATE TABLE `gremiennotifikationsabo` (
  `gruppe` bigint(255) UNSIGNED NOT NULL,
  `person` bigint(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `gremientermine`
--

CREATE TABLE `gremientermine` (
  `gruppe` bigint(255) UNSIGNED NOT NULL,
  `termin` bigint(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `gremientermineintern`
--

CREATE TABLE `gremientermineintern` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `gruppe` bigint(255) UNSIGNED NOT NULL,
  `bezeichnung` varbinary(5000) NOT NULL,
  `ort` varbinary(5000) NOT NULL,
  `beginn` bigint(255) UNSIGNED NOT NULL,
  `ende` bigint(255) UNSIGNED NOT NULL,
  `mehrtaegigt` int(1) NOT NULL,
  `uhrzeitbt` int(1) NOT NULL,
  `uhrzeitet` int(1) NOT NULL,
  `ortt` int(1) NOT NULL,
  `genehmigt` int(1) NOT NULL,
  `aktiv` int(1) NOT NULL,
  `text` longblob NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `gremientermineinterndownloads`
--

CREATE TABLE `gremientermineinterndownloads` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `termin` bigint(255) UNSIGNED NOT NULL,
  `pfad` varbinary(5000) NOT NULL,
  `titel` varbinary(5000) NOT NULL,
  `beschreibung` longblob NOT NULL,
  `dateiname` int(1) UNSIGNED NOT NULL,
  `dateigroesse` int(1) UNSIGNED NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `gremienvorsitz`
--

CREATE TABLE `gremienvorsitz` (
  `gruppe` bigint(255) UNSIGNED NOT NULL,
  `person` bigint(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `hausmeisterauftraege`
--

CREATE TABLE `hausmeisterauftraege` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `status` varchar(1) COLLATE utf8_unicode_ci NOT NULL,
  `titel` varbinary(1000) NOT NULL,
  `beschreibung` blob NOT NULL,
  `start` bigint(255) UNSIGNED NOT NULL,
  `ziel` bigint(255) UNSIGNED NOT NULL,
  `erledigt` bigint(255) UNSIGNED DEFAULT NULL,
  `erledigtvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `identitaetsdiebstahl`
--

CREATE TABLE `identitaetsdiebstahl` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `zeit` bigint(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `internedienste`
--

CREATE TABLE `internedienste` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `inhalt` varbinary(500) NOT NULL,
  `wert` varbinary(5000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `klassen`
--

CREATE TABLE `klassen` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `bezeichnung` varbinary(2000) NOT NULL,
  `icon` varbinary(2000) NOT NULL,
  `stundenplanextern` varbinary(5000) NOT NULL,
  `stufenbezextern` varbinary(500) NOT NULL,
  `klassenbezextern` varbinary(500) NOT NULL,
  `stufe` bigint(255) UNSIGNED NOT NULL,
  `sichtbar` int(1) UNSIGNED NOT NULL,
  `schuljahr` bigint(255) UNSIGNED DEFAULT NULL,
  `chataktiv` int(1) UNSIGNED NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `klassenaufsicht`
--

CREATE TABLE `klassenaufsicht` (
  `gruppe` bigint(255) UNSIGNED NOT NULL,
  `person` bigint(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `klassenblogeintraege`
--

CREATE TABLE `klassenblogeintraege` (
  `gruppe` bigint(255) UNSIGNED NOT NULL,
  `blogeintrag` bigint(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `klassenblogeintraegeintern`
--

CREATE TABLE `klassenblogeintraegeintern` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `gruppe` bigint(255) UNSIGNED NOT NULL,
  `bezeichnung` varbinary(5000) NOT NULL,
  `datum` bigint(255) UNSIGNED NOT NULL,
  `genehmigt` int(1) NOT NULL,
  `aktiv` int(1) NOT NULL,
  `text` longblob NOT NULL,
  `vorschau` longblob NOT NULL,
  `autor` varbinary(5000) NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `klassenblogeintragbeschluesse`
--

CREATE TABLE `klassenblogeintragbeschluesse` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `blogeintrag` bigint(255) UNSIGNED NOT NULL,
  `titel` varbinary(5000) NOT NULL,
  `langfristig` varbinary(50) NOT NULL,
  `beschreibung` longblob NOT NULL,
  `pro` bigint(255) UNSIGNED NOT NULL,
  `contra` bigint(255) UNSIGNED NOT NULL,
  `enthaltung` bigint(255) UNSIGNED NOT NULL,
  `idvon` bigint(255) NOT NULL,
  `idzeit` bigint(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `klassenblogeintragdownloads`
--

CREATE TABLE `klassenblogeintragdownloads` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `blogeintrag` bigint(255) UNSIGNED NOT NULL,
  `pfad` varbinary(5000) NOT NULL,
  `titel` varbinary(5000) NOT NULL,
  `beschreibung` longblob NOT NULL,
  `dateiname` int(1) UNSIGNED NOT NULL,
  `dateigroesse` int(1) UNSIGNED NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `klassenchat`
--

CREATE TABLE `klassenchat` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `gruppe` bigint(255) UNSIGNED NOT NULL,
  `person` bigint(255) UNSIGNED NOT NULL,
  `datum` bigint(255) UNSIGNED NOT NULL,
  `inhalt` longblob NOT NULL,
  `meldestatus` int(1) UNSIGNED NOT NULL DEFAULT '0',
  `gemeldetvon` bigint(255) UNSIGNED NOT NULL,
  `gemeldetam` bigint(255) UNSIGNED NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `klassenmitglieder`
--

CREATE TABLE `klassenmitglieder` (
  `gruppe` bigint(255) UNSIGNED NOT NULL,
  `person` bigint(255) UNSIGNED NOT NULL,
  `dateiupload` int(1) UNSIGNED NOT NULL,
  `dateidownload` int(1) UNSIGNED NOT NULL,
  `dateiloeschen` int(1) UNSIGNED NOT NULL,
  `dateiumbenennen` int(1) UNSIGNED NOT NULL,
  `termine` int(1) UNSIGNED NOT NULL,
  `blogeintraege` int(1) UNSIGNED NOT NULL,
  `chatten` int(1) UNSIGNED NOT NULL,
  `chattenab` bigint(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `klassennotifikationsabo`
--

CREATE TABLE `klassennotifikationsabo` (
  `gruppe` bigint(255) UNSIGNED NOT NULL,
  `person` bigint(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `klassentermine`
--

CREATE TABLE `klassentermine` (
  `gruppe` bigint(255) UNSIGNED NOT NULL,
  `termin` bigint(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `klassentermineintern`
--

CREATE TABLE `klassentermineintern` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `gruppe` bigint(255) UNSIGNED NOT NULL,
  `bezeichnung` varbinary(5000) NOT NULL,
  `ort` varbinary(5000) NOT NULL,
  `beginn` bigint(255) UNSIGNED NOT NULL,
  `ende` bigint(255) UNSIGNED NOT NULL,
  `mehrtaegigt` int(1) NOT NULL,
  `uhrzeitbt` int(1) NOT NULL,
  `uhrzeitet` int(1) NOT NULL,
  `ortt` int(1) NOT NULL,
  `genehmigt` int(1) NOT NULL,
  `aktiv` int(1) NOT NULL,
  `text` longblob NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `klassentermineinterndownloads`
--

CREATE TABLE `klassentermineinterndownloads` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `termin` bigint(255) UNSIGNED NOT NULL,
  `pfad` varbinary(5000) NOT NULL,
  `titel` varbinary(5000) NOT NULL,
  `beschreibung` longblob NOT NULL,
  `dateiname` int(1) UNSIGNED NOT NULL,
  `dateigroesse` int(1) UNSIGNED NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `klassenvorsitz`
--

CREATE TABLE `klassenvorsitz` (
  `gruppe` bigint(255) UNSIGNED NOT NULL,
  `person` bigint(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `kontaktformulare`
--

CREATE TABLE `kontaktformulare` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `spalte` bigint(255) UNSIGNED NOT NULL,
  `position` bigint(255) UNSIGNED NOT NULL,
  `aktiv` varchar(1) COLLATE utf8_unicode_ci NOT NULL,
  `betreffalt` varchar(5000) COLLATE utf8_unicode_ci NOT NULL,
  `betreffaktuell` varchar(5000) COLLATE utf8_unicode_ci NOT NULL,
  `betreffneu` varchar(5000) COLLATE utf8_unicode_ci NOT NULL,
  `kopiealt` varchar(1) COLLATE utf8_unicode_ci NOT NULL,
  `kopieaktuell` varchar(1) COLLATE utf8_unicode_ci NOT NULL,
  `kopieneu` varchar(1) COLLATE utf8_unicode_ci NOT NULL,
  `anhangalt` varchar(1) COLLATE utf8_unicode_ci NOT NULL,
  `anhangaktuell` varchar(1) COLLATE utf8_unicode_ci NOT NULL,
  `anhangneu` varchar(1) COLLATE utf8_unicode_ci NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `kontaktformulareempfaenger`
--

CREATE TABLE `kontaktformulareempfaenger` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `kontaktformular` bigint(255) UNSIGNED NOT NULL,
  `namealt` varchar(2000) COLLATE utf8_unicode_ci NOT NULL,
  `nameaktuell` varchar(2000) COLLATE utf8_unicode_ci NOT NULL,
  `nameneu` varchar(2000) COLLATE utf8_unicode_ci NOT NULL,
  `beschreibungalt` text COLLATE utf8_unicode_ci NOT NULL,
  `beschreibungaktuell` text COLLATE utf8_unicode_ci NOT NULL,
  `beschreibungneu` text COLLATE utf8_unicode_ci NOT NULL,
  `mailalt` varbinary(5000) NOT NULL,
  `mailaktuell` varbinary(5000) NOT NULL,
  `mailneu` varbinary(5000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `kurse`
--

CREATE TABLE `kurse` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `bezeichnung` varbinary(2000) NOT NULL,
  `icon` varbinary(2000) NOT NULL,
  `stufe` bigint(255) UNSIGNED DEFAULT NULL,
  `kurzbezeichnung` varbinary(500) NOT NULL,
  `fach` bigint(255) UNSIGNED NOT NULL,
  `kursbezextern` varbinary(500) NOT NULL,
  `sichtbar` int(1) UNSIGNED NOT NULL,
  `schuljahr` bigint(255) UNSIGNED DEFAULT NULL,
  `chataktiv` int(1) UNSIGNED NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `kurseaufsicht`
--

CREATE TABLE `kurseaufsicht` (
  `gruppe` bigint(255) UNSIGNED NOT NULL,
  `person` bigint(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `kurseblogeintraege`
--

CREATE TABLE `kurseblogeintraege` (
  `gruppe` bigint(255) UNSIGNED NOT NULL,
  `blogeintrag` bigint(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `kurseblogeintraegeintern`
--

CREATE TABLE `kurseblogeintraegeintern` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `gruppe` bigint(255) UNSIGNED NOT NULL,
  `bezeichnung` varbinary(5000) NOT NULL,
  `datum` bigint(255) UNSIGNED NOT NULL,
  `genehmigt` int(1) NOT NULL,
  `aktiv` int(1) NOT NULL,
  `text` longblob NOT NULL,
  `vorschau` longblob NOT NULL,
  `autor` varbinary(5000) NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `kurseblogeintragbeschluesse`
--

CREATE TABLE `kurseblogeintragbeschluesse` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `blogeintrag` bigint(255) UNSIGNED NOT NULL,
  `titel` varbinary(5000) NOT NULL,
  `langfristig` varbinary(50) NOT NULL,
  `beschreibung` longblob NOT NULL,
  `pro` bigint(255) UNSIGNED NOT NULL,
  `contra` bigint(255) UNSIGNED NOT NULL,
  `enthaltung` bigint(255) UNSIGNED NOT NULL,
  `idvon` bigint(255) NOT NULL,
  `idzeit` bigint(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `kurseblogeintragdownloads`
--

CREATE TABLE `kurseblogeintragdownloads` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `blogeintrag` bigint(255) UNSIGNED NOT NULL,
  `pfad` varbinary(5000) NOT NULL,
  `titel` varbinary(5000) NOT NULL,
  `beschreibung` longblob NOT NULL,
  `dateiname` int(1) UNSIGNED NOT NULL,
  `dateigroesse` int(1) UNSIGNED NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `kursechat`
--

CREATE TABLE `kursechat` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `gruppe` bigint(255) UNSIGNED NOT NULL,
  `person` bigint(255) UNSIGNED NOT NULL,
  `datum` bigint(255) UNSIGNED NOT NULL,
  `inhalt` longblob NOT NULL,
  `meldestatus` int(1) UNSIGNED NOT NULL DEFAULT '0',
  `gemeldetvon` bigint(255) UNSIGNED NOT NULL,
  `gemeldetam` bigint(255) UNSIGNED NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `kurseklassen`
--

CREATE TABLE `kurseklassen` (
  `kurs` bigint(255) UNSIGNED NOT NULL,
  `klasse` bigint(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `kursemitglieder`
--

CREATE TABLE `kursemitglieder` (
  `gruppe` bigint(255) UNSIGNED NOT NULL,
  `person` bigint(255) UNSIGNED NOT NULL,
  `dateiupload` int(1) UNSIGNED NOT NULL,
  `dateidownload` int(1) UNSIGNED NOT NULL,
  `dateiloeschen` int(1) UNSIGNED NOT NULL,
  `dateiumbenennen` int(1) UNSIGNED NOT NULL,
  `termine` int(1) UNSIGNED NOT NULL,
  `blogeintraege` int(1) UNSIGNED NOT NULL,
  `chatten` int(1) UNSIGNED NOT NULL,
  `chattenab` bigint(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `kursenotifikationsabo`
--

CREATE TABLE `kursenotifikationsabo` (
  `gruppe` bigint(255) UNSIGNED NOT NULL,
  `person` bigint(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `kursetermine`
--

CREATE TABLE `kursetermine` (
  `gruppe` bigint(255) UNSIGNED NOT NULL,
  `termin` bigint(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `kursetermineintern`
--

CREATE TABLE `kursetermineintern` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `gruppe` bigint(255) UNSIGNED NOT NULL,
  `bezeichnung` varbinary(5000) NOT NULL,
  `ort` varbinary(5000) NOT NULL,
  `beginn` bigint(255) UNSIGNED NOT NULL,
  `ende` bigint(255) UNSIGNED NOT NULL,
  `mehrtaegigt` int(1) NOT NULL,
  `uhrzeitbt` int(1) NOT NULL,
  `uhrzeitet` int(1) NOT NULL,
  `ortt` int(1) NOT NULL,
  `genehmigt` int(1) NOT NULL,
  `aktiv` int(1) NOT NULL,
  `text` longblob NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `kursetermineinterndownloads`
--

CREATE TABLE `kursetermineinterndownloads` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `termin` bigint(255) UNSIGNED NOT NULL,
  `pfad` varbinary(5000) NOT NULL,
  `titel` varbinary(5000) NOT NULL,
  `beschreibung` longblob NOT NULL,
  `dateiname` int(1) UNSIGNED NOT NULL,
  `dateigroesse` int(1) UNSIGNED NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `kursevorsitz`
--

CREATE TABLE `kursevorsitz` (
  `gruppe` bigint(255) UNSIGNED NOT NULL,
  `person` bigint(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `lehrer`
--

CREATE TABLE `lehrer` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `kuerzel` varbinary(100) NOT NULL,
  `stundenplan` varbinary(3000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `leihen`
--

CREATE TABLE `leihen` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `bezeichnung` varbinary(2000) NOT NULL,
  `buchbar` int(1) NOT NULL,
  `verfuegbar` int(1) NOT NULL,
  `externverwaltbar` int(1) NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `leihenblockieren`
--

CREATE TABLE `leihenblockieren` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `standort` bigint(255) UNSIGNED NOT NULL,
  `grund` varbinary(5000) NOT NULL,
  `wochentag` int(1) UNSIGNED NOT NULL,
  `beginns` varbinary(100) NOT NULL,
  `beginnm` varbinary(100) NOT NULL,
  `endes` varbinary(100) NOT NULL,
  `endem` varbinary(100) NOT NULL,
  `ferien` int(1) UNSIGNED NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `leihenbuchen`
--

CREATE TABLE `leihenbuchen` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `standort` bigint(255) UNSIGNED NOT NULL,
  `person` bigint(255) UNSIGNED NOT NULL,
  `grund` varbinary(5000) NOT NULL,
  `beginn` bigint(255) UNSIGNED NOT NULL,
  `ende` bigint(255) UNSIGNED NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `leihengeraete`
--

CREATE TABLE `leihengeraete` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `bezeichnung` varbinary(5000) NOT NULL,
  `standort` bigint(255) UNSIGNED NOT NULL,
  `statusnr` int(1) NOT NULL,
  `meldung` longblob NOT NULL,
  `kommentar` longblob NOT NULL,
  `absender` bigint(255) UNSIGNED DEFAULT NULL,
  `zeit` bigint(255) UNSIGNED DEFAULT NULL,
  `ticket` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `navigationen`
--

CREATE TABLE `navigationen` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `art` varchar(1) COLLATE utf8_unicode_ci NOT NULL,
  `ebene` varchar(1) COLLATE utf8_unicode_ci NOT NULL,
  `ebenenzusatz` bigint(255) UNSIGNED DEFAULT NULL,
  `tiefe` bigint(255) UNSIGNED DEFAULT NULL,
  `spalte` bigint(255) UNSIGNED DEFAULT NULL,
  `position` bigint(255) DEFAULT NULL,
  `anzeige` varchar(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `styles` text COLLATE utf8_unicode_ci NOT NULL,
  `klassen` text COLLATE utf8_unicode_ci NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `notifikationen`
--

CREATE TABLE `notifikationen` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `person` bigint(255) UNSIGNED NOT NULL,
  `zeit` bigint(255) UNSIGNED NOT NULL,
  `gruppe` varbinary(1000) NOT NULL,
  `gruppenid` bigint(255) UNSIGNED NOT NULL,
  `zielid` bigint(255) UNSIGNED DEFAULT NULL,
  `status` varchar(1) COLLATE utf8_unicode_ci NOT NULL,
  `art` varchar(1) COLLATE utf8_unicode_ci NOT NULL,
  `titel` varbinary(3000) NOT NULL,
  `vorschau` blob NOT NULL,
  `link` varbinary(5000) NOT NULL,
  `idvon` bigint(255) UNSIGNED NOT NULL,
  `idzeit` bigint(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `nutzerkonten`
--

CREATE TABLE `nutzerkonten` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `benutzername` varbinary(3000) NOT NULL,
  `passwort` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `passworttimeout` bigint(255) UNSIGNED NOT NULL,
  `salt` varbinary(100) NOT NULL,
  `sessionid` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `sessiontimeout` bigint(255) UNSIGNED NOT NULL,
  `schuljahr` bigint(255) UNSIGNED DEFAULT NULL,
  `email` varbinary(3000) NOT NULL,
  `letzteanmeldung` bigint(255) UNSIGNED NOT NULL,
  `vorletzteanmeldung` bigint(255) UNSIGNED NOT NULL,
  `erstellt` bigint(255) UNSIGNED NOT NULL,
  `notizen` longblob NOT NULL,
  `letztenotifikation` bigint(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `personen`
--

CREATE TABLE `personen` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `art` varbinary(50) NOT NULL,
  `titel` varbinary(3000) NOT NULL,
  `nachname` varbinary(3000) NOT NULL,
  `vorname` varbinary(3000) NOT NULL,
  `geschlecht` varbinary(50) NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `personen_einstellungen`
--

CREATE TABLE `personen_einstellungen` (
  `person` bigint(255) UNSIGNED NOT NULL,
  `notifikationsmail` varbinary(50) NOT NULL,
  `postmail` varbinary(50) NOT NULL,
  `postalletage` varbinary(3000) NOT NULL,
  `postpapierkorbtage` varbinary(3000) NOT NULL,
  `vertretungsmail` varbinary(50) NOT NULL,
  `uebersichtsanzahl` varbinary(2000) NOT NULL,
  `oeffentlichertermin` varbinary(50) NOT NULL,
  `oeffentlicherblog` varbinary(50) NOT NULL,
  `oeffentlichegalerie` varbinary(50) NOT NULL,
  `inaktivitaetszeit` varbinary(2000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `personen_signaturen`
--

CREATE TABLE `personen_signaturen` (
  `person` bigint(255) UNSIGNED NOT NULL,
  `signatur` blob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `pinnwaende`
--

CREATE TABLE `pinnwaende` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `bezeichnung` varbinary(5000) NOT NULL,
  `sichtbars` int(1) UNSIGNED NOT NULL DEFAULT '0',
  `sichtbarl` int(1) UNSIGNED NOT NULL DEFAULT '0',
  `sichtbare` int(1) UNSIGNED NOT NULL DEFAULT '0',
  `sichtbarv` int(1) UNSIGNED NOT NULL DEFAULT '0',
  `sichtbarx` int(1) UNSIGNED NOT NULL DEFAULT '0',
  `schreibens` int(1) UNSIGNED NOT NULL DEFAULT '0',
  `schreibenl` int(1) UNSIGNED NOT NULL DEFAULT '0',
  `schreibene` int(1) UNSIGNED NOT NULL DEFAULT '0',
  `schreibenv` int(1) UNSIGNED NOT NULL DEFAULT '0',
  `schreibenx` int(1) UNSIGNED NOT NULL DEFAULT '0',
  `beschreibung` longblob NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `pinnwandanschlag`
--

CREATE TABLE `pinnwandanschlag` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `pinnwand` bigint(255) UNSIGNED NOT NULL,
  `titel` varbinary(5000) NOT NULL,
  `inhalt` longblob NOT NULL,
  `beginn` bigint(255) UNSIGNED NOT NULL,
  `ende` bigint(255) UNSIGNED NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `profile`
--

CREATE TABLE `profile` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `schuljahr` bigint(255) UNSIGNED NOT NULL,
  `art` varchar(1) COLLATE utf8_unicode_ci NOT NULL,
  `bezeichnung` varbinary(2000) NOT NULL,
  `stufe` bigint(255) UNSIGNED NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `profilfaecher`
--

CREATE TABLE `profilfaecher` (
  `profil` bigint(255) UNSIGNED NOT NULL,
  `fach` bigint(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `raeume`
--

CREATE TABLE `raeume` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `bezeichnung` varbinary(2000) NOT NULL,
  `stundenplan` varbinary(3000) NOT NULL,
  `buchbar` int(1) NOT NULL,
  `verfuegbar` int(1) NOT NULL,
  `externverwaltbar` int(1) NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `raeumeblockieren`
--

CREATE TABLE `raeumeblockieren` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `standort` bigint(255) UNSIGNED NOT NULL,
  `grund` varbinary(5000) NOT NULL,
  `wochentag` int(1) UNSIGNED NOT NULL,
  `beginns` varbinary(100) NOT NULL,
  `beginnm` varbinary(100) NOT NULL,
  `endes` varbinary(100) NOT NULL,
  `endem` varbinary(100) NOT NULL,
  `ferien` int(1) UNSIGNED NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `raeumebuchen`
--

CREATE TABLE `raeumebuchen` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `standort` bigint(255) UNSIGNED NOT NULL,
  `person` bigint(255) UNSIGNED NOT NULL,
  `grund` varbinary(5000) NOT NULL,
  `beginn` bigint(255) UNSIGNED NOT NULL,
  `ende` bigint(255) UNSIGNED NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `raeumegeraete`
--

CREATE TABLE `raeumegeraete` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `bezeichnung` varbinary(5000) NOT NULL,
  `standort` bigint(255) UNSIGNED NOT NULL,
  `statusnr` int(1) NOT NULL,
  `meldung` longblob NOT NULL,
  `kommentar` longblob NOT NULL,
  `absender` bigint(255) UNSIGNED DEFAULT NULL,
  `zeit` bigint(255) UNSIGNED DEFAULT NULL,
  `ticket` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `rechte`
--

CREATE TABLE `rechte` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `kategorie` varbinary(2000) NOT NULL,
  `bezeichnung` varbinary(2000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `rechtzuordnung`
--

CREATE TABLE `rechtzuordnung` (
  `person` bigint(255) UNSIGNED NOT NULL,
  `recht` bigint(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `rollen`
--

CREATE TABLE `rollen` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `bezeichnung` varbinary(3000) NOT NULL,
  `personenart` varbinary(50) NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `rollenrechte`
--

CREATE TABLE `rollenrechte` (
  `rolle` bigint(255) UNSIGNED NOT NULL,
  `recht` bigint(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `rollenzuordnung`
--

CREATE TABLE `rollenzuordnung` (
  `person` bigint(255) UNSIGNED NOT NULL,
  `rolle` bigint(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `schluesselposition`
--

CREATE TABLE `schluesselposition` (
  `person` bigint(255) UNSIGNED NOT NULL,
  `position` varbinary(257) NOT NULL,
  `schuljahr` bigint(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `schuelereltern`
--

CREATE TABLE `schuelereltern` (
  `schueler` bigint(255) UNSIGNED NOT NULL,
  `eltern` bigint(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `schulanmeldung`
--

CREATE TABLE `schulanmeldung` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `inhalt` varbinary(1000) NOT NULL,
  `wert` longblob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `schuljahre`
--

CREATE TABLE `schuljahre` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `bezeichnung` varbinary(3000) NOT NULL,
  `beginn` bigint(255) UNSIGNED NOT NULL,
  `ende` bigint(255) UNSIGNED NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `schulstunden`
--

CREATE TABLE `schulstunden` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `zeitraum` bigint(255) UNSIGNED NOT NULL,
  `bezeichnung` varbinary(2000) NOT NULL,
  `beginns` int(2) UNSIGNED NOT NULL,
  `beginnm` int(2) UNSIGNED NOT NULL,
  `endes` int(2) UNSIGNED NOT NULL,
  `endem` int(2) UNSIGNED NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `seiten`
--

CREATE TABLE `seiten` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `art` varchar(1) COLLATE utf8_unicode_ci NOT NULL,
  `position` bigint(255) UNSIGNED NOT NULL,
  `zuordnung` bigint(255) UNSIGNED DEFAULT NULL,
  `bezeichnung` varchar(1000) COLLATE utf8_unicode_ci NOT NULL,
  `beschreibung` text COLLATE utf8_unicode_ci NOT NULL,
  `sidebar` varchar(1) COLLATE utf8_unicode_ci NOT NULL,
  `status` varchar(1) COLLATE utf8_unicode_ci NOT NULL,
  `styles` text COLLATE utf8_unicode_ci NOT NULL,
  `klassen` text COLLATE utf8_unicode_ci NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `sonstigegruppen`
--

CREATE TABLE `sonstigegruppen` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `bezeichnung` varbinary(2000) NOT NULL,
  `icon` varbinary(2000) NOT NULL,
  `sichtbar` int(1) UNSIGNED NOT NULL,
  `schuljahr` bigint(255) UNSIGNED DEFAULT NULL,
  `chataktiv` int(1) UNSIGNED NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `sonstigegruppenaufsicht`
--

CREATE TABLE `sonstigegruppenaufsicht` (
  `gruppe` bigint(255) UNSIGNED NOT NULL,
  `person` bigint(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `sonstigegruppenblogeintraege`
--

CREATE TABLE `sonstigegruppenblogeintraege` (
  `gruppe` bigint(255) UNSIGNED NOT NULL,
  `blogeintrag` bigint(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `sonstigegruppenblogeintraegeintern`
--

CREATE TABLE `sonstigegruppenblogeintraegeintern` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `gruppe` bigint(255) UNSIGNED NOT NULL,
  `bezeichnung` varbinary(5000) NOT NULL,
  `datum` bigint(255) UNSIGNED NOT NULL,
  `genehmigt` int(1) NOT NULL,
  `aktiv` int(1) NOT NULL,
  `text` longblob NOT NULL,
  `vorschau` longblob NOT NULL,
  `autor` varbinary(5000) NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `sonstigegruppenblogeintragbeschluesse`
--

CREATE TABLE `sonstigegruppenblogeintragbeschluesse` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `blogeintrag` bigint(255) UNSIGNED NOT NULL,
  `titel` varbinary(5000) NOT NULL,
  `langfristig` varbinary(50) NOT NULL,
  `beschreibung` longblob NOT NULL,
  `pro` bigint(255) UNSIGNED NOT NULL,
  `contra` bigint(255) UNSIGNED NOT NULL,
  `enthaltung` bigint(255) UNSIGNED NOT NULL,
  `idvon` bigint(255) NOT NULL,
  `idzeit` bigint(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `sonstigegruppenblogeintragdownloads`
--

CREATE TABLE `sonstigegruppenblogeintragdownloads` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `blogeintrag` bigint(255) UNSIGNED NOT NULL,
  `pfad` varbinary(5000) NOT NULL,
  `titel` varbinary(5000) NOT NULL,
  `beschreibung` longblob NOT NULL,
  `dateiname` int(1) UNSIGNED NOT NULL,
  `dateigroesse` int(1) UNSIGNED NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `sonstigegruppenchat`
--

CREATE TABLE `sonstigegruppenchat` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `gruppe` bigint(255) UNSIGNED NOT NULL,
  `person` bigint(255) UNSIGNED NOT NULL,
  `datum` bigint(255) UNSIGNED NOT NULL,
  `inhalt` longblob NOT NULL,
  `meldestatus` int(1) UNSIGNED NOT NULL DEFAULT '0',
  `gemeldetvon` bigint(255) UNSIGNED NOT NULL,
  `gemeldetam` bigint(255) UNSIGNED NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `sonstigegruppenmitglieder`
--

CREATE TABLE `sonstigegruppenmitglieder` (
  `gruppe` bigint(255) UNSIGNED NOT NULL,
  `person` bigint(255) UNSIGNED NOT NULL,
  `dateiupload` int(1) UNSIGNED NOT NULL,
  `dateidownload` int(1) UNSIGNED NOT NULL,
  `dateiloeschen` int(1) UNSIGNED NOT NULL,
  `dateiumbenennen` int(1) UNSIGNED NOT NULL,
  `termine` int(1) UNSIGNED NOT NULL,
  `blogeintraege` int(1) UNSIGNED NOT NULL,
  `chatten` int(1) UNSIGNED NOT NULL,
  `chattenab` bigint(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `sonstigegruppennotifikationsabo`
--

CREATE TABLE `sonstigegruppennotifikationsabo` (
  `gruppe` bigint(255) UNSIGNED NOT NULL,
  `person` bigint(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `sonstigegruppentermine`
--

CREATE TABLE `sonstigegruppentermine` (
  `gruppe` bigint(255) UNSIGNED NOT NULL,
  `termin` bigint(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `sonstigegruppentermineintern`
--

CREATE TABLE `sonstigegruppentermineintern` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `gruppe` bigint(255) UNSIGNED NOT NULL,
  `bezeichnung` varbinary(5000) NOT NULL,
  `ort` varbinary(5000) NOT NULL,
  `beginn` bigint(255) UNSIGNED NOT NULL,
  `ende` bigint(255) UNSIGNED NOT NULL,
  `mehrtaegigt` int(1) NOT NULL,
  `uhrzeitbt` int(1) NOT NULL,
  `uhrzeitet` int(1) NOT NULL,
  `ortt` int(1) NOT NULL,
  `genehmigt` int(1) NOT NULL,
  `aktiv` int(1) NOT NULL,
  `text` longblob NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `sonstigegruppentermineinterndownloads`
--

CREATE TABLE `sonstigegruppentermineinterndownloads` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `termin` bigint(255) UNSIGNED NOT NULL,
  `pfad` varbinary(5000) NOT NULL,
  `titel` varbinary(5000) NOT NULL,
  `beschreibung` longblob NOT NULL,
  `dateiname` int(1) UNSIGNED NOT NULL,
  `dateigroesse` int(1) UNSIGNED NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `sonstigegruppenvorsitz`
--

CREATE TABLE `sonstigegruppenvorsitz` (
  `gruppe` bigint(255) UNSIGNED NOT NULL,
  `person` bigint(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `spalten`
--

CREATE TABLE `spalten` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `seite` bigint(255) UNSIGNED NOT NULL,
  `zeile` bigint(255) UNSIGNED NOT NULL,
  `position` bigint(255) UNSIGNED NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `statistikblog`
--

CREATE TABLE `statistikblog` (
  `jahr` int(4) UNSIGNED NOT NULL,
  `monat` int(2) UNSIGNED NOT NULL,
  `id` bigint(255) UNSIGNED NOT NULL,
  `anzahl` bigint(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `statistikgalerie`
--

CREATE TABLE `statistikgalerie` (
  `jahr` int(4) UNSIGNED NOT NULL,
  `monat` int(2) UNSIGNED NOT NULL,
  `id` bigint(255) UNSIGNED NOT NULL,
  `anzahl` bigint(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `statistiktermine`
--

CREATE TABLE `statistiktermine` (
  `jahr` int(4) UNSIGNED NOT NULL,
  `monat` int(2) UNSIGNED NOT NULL,
  `id` bigint(255) UNSIGNED NOT NULL,
  `anzahl` bigint(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `statistikwebsite`
--

CREATE TABLE `statistikwebsite` (
  `jahr` int(4) UNSIGNED NOT NULL,
  `monat` int(2) UNSIGNED NOT NULL,
  `id` bigint(255) UNSIGNED NOT NULL,
  `anzahl` bigint(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `stufen`
--

CREATE TABLE `stufen` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `bezeichnung` varbinary(2000) NOT NULL,
  `icon` varbinary(2000) NOT NULL,
  `reihenfolge` int(255) UNSIGNED NOT NULL,
  `sichtbar` int(1) UNSIGNED NOT NULL,
  `schuljahr` bigint(255) UNSIGNED DEFAULT NULL,
  `chataktiv` int(1) UNSIGNED NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `stufenaufsicht`
--

CREATE TABLE `stufenaufsicht` (
  `gruppe` bigint(255) UNSIGNED NOT NULL,
  `person` bigint(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `stufenblogeintraege`
--

CREATE TABLE `stufenblogeintraege` (
  `gruppe` bigint(255) UNSIGNED NOT NULL,
  `blogeintrag` bigint(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `stufenblogeintraegeintern`
--

CREATE TABLE `stufenblogeintraegeintern` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `gruppe` bigint(255) UNSIGNED NOT NULL,
  `bezeichnung` varbinary(5000) NOT NULL,
  `datum` bigint(255) UNSIGNED NOT NULL,
  `genehmigt` int(1) NOT NULL,
  `aktiv` int(1) NOT NULL,
  `text` longblob NOT NULL,
  `vorschau` longblob NOT NULL,
  `autor` varbinary(5000) NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `stufenblogeintragbeschluesse`
--

CREATE TABLE `stufenblogeintragbeschluesse` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `blogeintrag` bigint(255) UNSIGNED NOT NULL,
  `titel` varbinary(5000) NOT NULL,
  `langfristig` varbinary(50) NOT NULL,
  `beschreibung` longblob NOT NULL,
  `pro` bigint(255) UNSIGNED NOT NULL,
  `contra` bigint(255) UNSIGNED NOT NULL,
  `enthaltung` bigint(255) UNSIGNED NOT NULL,
  `idvon` bigint(255) NOT NULL,
  `idzeit` bigint(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `stufenblogeintragdownloads`
--

CREATE TABLE `stufenblogeintragdownloads` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `blogeintrag` bigint(255) UNSIGNED NOT NULL,
  `pfad` varbinary(5000) NOT NULL,
  `titel` varbinary(5000) NOT NULL,
  `beschreibung` longblob NOT NULL,
  `dateiname` int(1) UNSIGNED NOT NULL,
  `dateigroesse` int(1) UNSIGNED NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `stufenchat`
--

CREATE TABLE `stufenchat` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `gruppe` bigint(255) UNSIGNED NOT NULL,
  `person` bigint(255) UNSIGNED NOT NULL,
  `datum` bigint(255) UNSIGNED NOT NULL,
  `inhalt` longblob NOT NULL,
  `meldestatus` int(1) UNSIGNED NOT NULL DEFAULT '0',
  `gemeldetvon` bigint(255) UNSIGNED NOT NULL,
  `gemeldetam` bigint(255) UNSIGNED NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `stufenmitglieder`
--

CREATE TABLE `stufenmitglieder` (
  `gruppe` bigint(255) UNSIGNED NOT NULL,
  `person` bigint(255) UNSIGNED NOT NULL,
  `dateiupload` int(1) UNSIGNED NOT NULL,
  `dateidownload` int(1) UNSIGNED NOT NULL,
  `dateiloeschen` int(1) UNSIGNED NOT NULL,
  `dateiumbenennen` int(1) UNSIGNED NOT NULL,
  `termine` int(1) UNSIGNED NOT NULL,
  `blogeintraege` int(1) UNSIGNED NOT NULL,
  `chatten` int(1) UNSIGNED NOT NULL,
  `chattenab` bigint(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `stufennotifikationsabo`
--

CREATE TABLE `stufennotifikationsabo` (
  `gruppe` bigint(255) UNSIGNED NOT NULL,
  `person` bigint(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `stufentermine`
--

CREATE TABLE `stufentermine` (
  `gruppe` bigint(255) UNSIGNED NOT NULL,
  `termin` bigint(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `stufentermineintern`
--

CREATE TABLE `stufentermineintern` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `gruppe` bigint(255) UNSIGNED NOT NULL,
  `bezeichnung` varbinary(5000) NOT NULL,
  `ort` varbinary(5000) NOT NULL,
  `beginn` bigint(255) UNSIGNED NOT NULL,
  `ende` bigint(255) UNSIGNED NOT NULL,
  `mehrtaegigt` int(1) NOT NULL,
  `uhrzeitbt` int(1) NOT NULL,
  `uhrzeitet` int(1) NOT NULL,
  `ortt` int(1) NOT NULL,
  `genehmigt` int(1) NOT NULL,
  `aktiv` int(1) NOT NULL,
  `text` longblob NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `stufentermineinterndownloads`
--

CREATE TABLE `stufentermineinterndownloads` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `termin` bigint(255) UNSIGNED NOT NULL,
  `pfad` varbinary(5000) NOT NULL,
  `titel` varbinary(5000) NOT NULL,
  `beschreibung` longblob NOT NULL,
  `dateiname` int(1) UNSIGNED NOT NULL,
  `dateigroesse` int(1) UNSIGNED NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `stufenvorsitz`
--

CREATE TABLE `stufenvorsitz` (
  `gruppe` bigint(255) UNSIGNED NOT NULL,
  `person` bigint(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `termine`
--

CREATE TABLE `termine` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `bezeichnung` varbinary(5000) NOT NULL,
  `ort` varbinary(5000) NOT NULL,
  `beginn` bigint(255) UNSIGNED NOT NULL,
  `ende` bigint(255) UNSIGNED NOT NULL,
  `mehrtaegigt` int(1) NOT NULL,
  `uhrzeitbt` int(1) NOT NULL,
  `uhrzeitet` int(1) NOT NULL,
  `ortt` int(1) NOT NULL,
  `genehmigt` int(1) NOT NULL,
  `aktiv` int(1) NOT NULL,
  `oeffentlichkeit` int(1) NOT NULL DEFAULT '0',
  `text` longblob NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `terminedownloads`
--

CREATE TABLE `terminedownloads` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `termin` bigint(255) UNSIGNED NOT NULL,
  `pfad` varbinary(5000) NOT NULL,
  `titel` varbinary(5000) NOT NULL,
  `beschreibung` longblob NOT NULL,
  `dateiname` int(1) UNSIGNED NOT NULL,
  `dateigroesse` int(1) UNSIGNED NOT NULL,
  `idvon` bigint(255) UNSIGNED NOT NULL,
  `idzeit` bigint(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `umarmungen`
--

CREATE TABLE `umarmungen` (
  `von` bigint(255) NOT NULL,
  `an` bigint(255) NOT NULL,
  `anonym` bigint(1) NOT NULL,
  `wann` int(11) NOT NULL,
  `gesehen` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `voranmeldung_eltern`
--

CREATE TABLE `voranmeldung_eltern` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `schueler` bigint(255) UNSIGNED NOT NULL,
  `nummer` varbinary(100) NOT NULL,
  `vorname` varbinary(2000) NOT NULL,
  `nachname` varbinary(2000) NOT NULL,
  `geschlecht` varbinary(50) NOT NULL,
  `sorgerecht` varbinary(50) NOT NULL,
  `briefe` varbinary(50) NOT NULL,
  `strasse` varbinary(2000) NOT NULL,
  `hausnummer` varbinary(100) NOT NULL,
  `plz` varbinary(100) NOT NULL,
  `ort` varbinary(2000) NOT NULL,
  `teilort` varbinary(2000) NOT NULL,
  `telefon1` varbinary(500) NOT NULL,
  `telefon2` varbinary(500) NOT NULL,
  `handy` varbinary(500) NOT NULL,
  `mail` varbinary(2000) NOT NULL,
  `idvon` bigint(255) NOT NULL,
  `idzeit` bigint(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `voranmeldung_schueler`
--

CREATE TABLE `voranmeldung_schueler` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `vorname` varbinary(2000) NOT NULL,
  `rufname` varbinary(2000) NOT NULL,
  `nachname` varbinary(2000) NOT NULL,
  `geburtsdatum` varbinary(500) NOT NULL,
  `geburtsort` varbinary(2000) NOT NULL,
  `geburtsland` varbinary(2000) NOT NULL,
  `muttersprache` varbinary(2000) NOT NULL,
  `verkehrssprache` varbinary(2000) NOT NULL,
  `geschlecht` varbinary(50) NOT NULL,
  `religion` varbinary(500) NOT NULL,
  `religionsunterricht` varbinary(500) NOT NULL,
  `staatsangehoerigkeit` varbinary(1000) NOT NULL,
  `zstaatsangehoerigkeit` varbinary(1000) NOT NULL,
  `strasse` varbinary(2000) NOT NULL,
  `hausnummer` varbinary(100) NOT NULL,
  `plz` varbinary(100) NOT NULL,
  `ort` varbinary(2000) NOT NULL,
  `teilort` varbinary(2000) NOT NULL,
  `telefon1` varbinary(500) NOT NULL,
  `telefon2` varbinary(500) NOT NULL,
  `handy1` varbinary(500) NOT NULL,
  `handy2` varbinary(500) NOT NULL,
  `mail` varbinary(2000) NOT NULL,
  `einschulung` varbinary(500) NOT NULL,
  `vorigeschule` varbinary(2000) NOT NULL,
  `vorigeklasse` varbinary(50) NOT NULL,
  `kuenftigesprofil` varbinary(1000) NOT NULL,
  `akzeptiert` varbinary(100) NOT NULL,
  `eingegangen` varbinary(500) NOT NULL,
  `idvon` bigint(255) NOT NULL,
  `idzeit` bigint(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `vpn`
--

CREATE TABLE `vpn` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `bezeichnung` varbinary(1000) NOT NULL,
  `inhalt` varbinary(5000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `wettbewerbe`
--

CREATE TABLE `wettbewerbe` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `bezeichnung` varbinary(2000) NOT NULL,
  `icon` varbinary(2000) NOT NULL,
  `sichtbar` int(1) UNSIGNED NOT NULL,
  `schuljahr` bigint(255) UNSIGNED DEFAULT NULL,
  `chataktiv` int(1) UNSIGNED NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `wettbewerbeaufsicht`
--

CREATE TABLE `wettbewerbeaufsicht` (
  `gruppe` bigint(255) UNSIGNED NOT NULL,
  `person` bigint(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `wettbewerbeblogeintraege`
--

CREATE TABLE `wettbewerbeblogeintraege` (
  `gruppe` bigint(255) UNSIGNED NOT NULL,
  `blogeintrag` bigint(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `wettbewerbeblogeintraegeintern`
--

CREATE TABLE `wettbewerbeblogeintraegeintern` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `gruppe` bigint(255) UNSIGNED NOT NULL,
  `bezeichnung` varbinary(5000) NOT NULL,
  `datum` bigint(255) UNSIGNED NOT NULL,
  `genehmigt` int(1) NOT NULL,
  `aktiv` int(1) NOT NULL,
  `text` longblob NOT NULL,
  `vorschau` longblob NOT NULL,
  `autor` varbinary(5000) NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `wettbewerbeblogeintragbeschluesse`
--

CREATE TABLE `wettbewerbeblogeintragbeschluesse` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `blogeintrag` bigint(255) UNSIGNED NOT NULL,
  `titel` varbinary(5000) NOT NULL,
  `langfristig` varbinary(50) NOT NULL,
  `beschreibung` longblob NOT NULL,
  `pro` bigint(255) UNSIGNED NOT NULL,
  `contra` bigint(255) UNSIGNED NOT NULL,
  `enthaltung` bigint(255) UNSIGNED NOT NULL,
  `idvon` bigint(255) NOT NULL,
  `idzeit` bigint(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `wettbewerbeblogeintragdownloads`
--

CREATE TABLE `wettbewerbeblogeintragdownloads` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `blogeintrag` bigint(255) UNSIGNED NOT NULL,
  `pfad` varbinary(5000) NOT NULL,
  `titel` varbinary(5000) NOT NULL,
  `beschreibung` longblob NOT NULL,
  `dateiname` int(1) UNSIGNED NOT NULL,
  `dateigroesse` int(1) UNSIGNED NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `wettbewerbechat`
--

CREATE TABLE `wettbewerbechat` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `gruppe` bigint(255) UNSIGNED NOT NULL,
  `person` bigint(255) UNSIGNED NOT NULL,
  `datum` bigint(255) UNSIGNED NOT NULL,
  `inhalt` longblob NOT NULL,
  `meldestatus` int(1) UNSIGNED NOT NULL DEFAULT '0',
  `gemeldetvon` bigint(255) UNSIGNED NOT NULL,
  `gemeldetam` bigint(255) UNSIGNED NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `wettbewerbemitglieder`
--

CREATE TABLE `wettbewerbemitglieder` (
  `gruppe` bigint(255) UNSIGNED NOT NULL,
  `person` bigint(255) UNSIGNED NOT NULL,
  `dateiupload` int(1) UNSIGNED NOT NULL,
  `dateidownload` int(1) UNSIGNED NOT NULL,
  `dateiloeschen` int(1) UNSIGNED NOT NULL,
  `dateiumbenennen` int(1) UNSIGNED NOT NULL,
  `termine` int(1) UNSIGNED NOT NULL,
  `blogeintraege` int(1) UNSIGNED NOT NULL,
  `chatten` int(1) UNSIGNED NOT NULL,
  `chattenab` bigint(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `wettbewerbenotifikationsabo`
--

CREATE TABLE `wettbewerbenotifikationsabo` (
  `gruppe` bigint(255) UNSIGNED NOT NULL,
  `person` bigint(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `wettbewerbetermine`
--

CREATE TABLE `wettbewerbetermine` (
  `gruppe` bigint(255) UNSIGNED NOT NULL,
  `termin` bigint(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `wettbewerbetermineintern`
--

CREATE TABLE `wettbewerbetermineintern` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `gruppe` bigint(255) UNSIGNED NOT NULL,
  `bezeichnung` varbinary(5000) NOT NULL,
  `ort` varbinary(5000) NOT NULL,
  `beginn` bigint(255) UNSIGNED NOT NULL,
  `ende` bigint(255) UNSIGNED NOT NULL,
  `mehrtaegigt` int(1) NOT NULL,
  `uhrzeitbt` int(1) NOT NULL,
  `uhrzeitet` int(1) NOT NULL,
  `ortt` int(1) NOT NULL,
  `genehmigt` int(1) NOT NULL,
  `aktiv` int(1) NOT NULL,
  `text` longblob NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `wettbewerbetermineinterndownloads`
--

CREATE TABLE `wettbewerbetermineinterndownloads` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `termin` bigint(255) UNSIGNED NOT NULL,
  `pfad` varbinary(5000) NOT NULL,
  `titel` varbinary(5000) NOT NULL,
  `beschreibung` longblob NOT NULL,
  `dateiname` int(1) UNSIGNED NOT NULL,
  `dateigroesse` int(1) UNSIGNED NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `wettbewerbevorsitz`
--

CREATE TABLE `wettbewerbevorsitz` (
  `gruppe` bigint(255) UNSIGNED NOT NULL,
  `person` bigint(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `zeitraeume`
--

CREATE TABLE `zeitraeume` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `schuljahr` bigint(255) UNSIGNED NOT NULL,
  `bezeichnung` varbinary(5000) NOT NULL,
  `beginn` bigint(255) UNSIGNED NOT NULL,
  `ende` bigint(255) UNSIGNED NOT NULL,
  `mo` int(1) UNSIGNED NOT NULL,
  `di` int(1) UNSIGNED NOT NULL,
  `mi` int(1) UNSIGNED NOT NULL,
  `do` int(1) UNSIGNED NOT NULL,
  `fr` int(1) UNSIGNED NOT NULL,
  `sa` int(1) UNSIGNED NOT NULL,
  `so` int(1) UNSIGNED NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `zulaessigedateien`
--

CREATE TABLE `zulaessigedateien` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `endung` varbinary(100) NOT NULL,
  `kategorie` varbinary(200) NOT NULL,
  `zulaessig` varbinary(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `allgemeineeinstellungen`
--
ALTER TABLE `allgemeineeinstellungen`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `arbeitsgemeinschaften`
--
ALTER TABLE `arbeitsgemeinschaften`
  ADD PRIMARY KEY (`id`),
  ADD KEY `schuljahrearbeitsgemeinschaften` (`schuljahr`);

--
-- Indizes für die Tabelle `arbeitsgemeinschaftenaufsicht`
--
ALTER TABLE `arbeitsgemeinschaftenaufsicht`
  ADD UNIQUE KEY `id` (`gruppe`,`person`),
  ADD KEY `personenarbeitsgemeinschaftenaufsicht` (`person`),
  ADD KEY `gruppenarbeitsgemeinschaftenaufsicht` (`gruppe`);

--
-- Indizes für die Tabelle `arbeitsgemeinschaftenblogeintraege`
--
ALTER TABLE `arbeitsgemeinschaftenblogeintraege`
  ADD UNIQUE KEY `id` (`gruppe`,`blogeintrag`),
  ADD KEY `blogeintraegearbeitsgemeinschaftenblogeintraege` (`blogeintrag`),
  ADD KEY `gruppenarbeitsgemeinschaftenblogeintraege` (`gruppe`);

--
-- Indizes für die Tabelle `arbeitsgemeinschaftenblogeintraegeintern`
--
ALTER TABLE `arbeitsgemeinschaftenblogeintraegeintern`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gruppearbeitsgemeinschaftenblogeintraegeintern` (`gruppe`);

--
-- Indizes für die Tabelle `arbeitsgemeinschaftenblogeintragbeschluesse`
--
ALTER TABLE `arbeitsgemeinschaftenblogeintragbeschluesse`
  ADD PRIMARY KEY (`id`),
  ADD KEY `blogeintragarbeitsgemeinschaftenblogeintragbeschluesse` (`blogeintrag`);

--
-- Indizes für die Tabelle `arbeitsgemeinschaftenblogeintragdownloads`
--
ALTER TABLE `arbeitsgemeinschaftenblogeintragdownloads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `blogeintragdownloadsblogeintraege` (`blogeintrag`);

--
-- Indizes für die Tabelle `arbeitsgemeinschaftenchat`
--
ALTER TABLE `arbeitsgemeinschaftenchat`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`gruppe`,`person`),
  ADD KEY `gruppenarbeitsgemeinschaftenchat` (`gruppe`),
  ADD KEY `personenarbeitsgemeinschaftenchat` (`person`);

--
-- Indizes für die Tabelle `arbeitsgemeinschaftenmitglieder`
--
ALTER TABLE `arbeitsgemeinschaftenmitglieder`
  ADD UNIQUE KEY `id` (`gruppe`,`person`),
  ADD KEY `personenarbeitsgemeinschaftenmitglieder` (`person`),
  ADD KEY `gruppenarbeitsgemeinschaftenmitglieder` (`gruppe`);

--
-- Indizes für die Tabelle `arbeitsgemeinschaftennotifikationsabo`
--
ALTER TABLE `arbeitsgemeinschaftennotifikationsabo`
  ADD UNIQUE KEY `id` (`gruppe`,`person`),
  ADD KEY `personenarbeitsgemeinschaftennotifikationsabo` (`person`),
  ADD KEY `gruppenarbeitsgemeinschaftennotifikationsabo` (`gruppe`);

--
-- Indizes für die Tabelle `arbeitsgemeinschaftentermine`
--
ALTER TABLE `arbeitsgemeinschaftentermine`
  ADD UNIQUE KEY `id` (`gruppe`,`termin`),
  ADD KEY `terminearbeitsgemeinschaftentermine` (`termin`),
  ADD KEY `gruppenarbeitsgemeinschaftentermine` (`gruppe`);

--
-- Indizes für die Tabelle `arbeitsgemeinschaftentermineintern`
--
ALTER TABLE `arbeitsgemeinschaftentermineintern`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gruppearbeitsgemeinschaftentermineintern` (`gruppe`);

--
-- Indizes für die Tabelle `arbeitsgemeinschaftentermineinterndownloads`
--
ALTER TABLE `arbeitsgemeinschaftentermineinterndownloads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `arbeitsgemeinschaftentermineinterndownloadstermineintern` (`termin`);

--
-- Indizes für die Tabelle `arbeitsgemeinschaftenvorsitz`
--
ALTER TABLE `arbeitsgemeinschaftenvorsitz`
  ADD UNIQUE KEY `id` (`gruppe`,`person`),
  ADD KEY `personenarbeitsgemeinschaftenvorsitz` (`person`),
  ADD KEY `gruppenarbeitsgemeinschaftenvorsitz` (`gruppe`);

--
-- Indizes für die Tabelle `arbeitskreise`
--
ALTER TABLE `arbeitskreise`
  ADD PRIMARY KEY (`id`),
  ADD KEY `schuljahrearbeitskreise` (`schuljahr`);

--
-- Indizes für die Tabelle `arbeitskreiseaufsicht`
--
ALTER TABLE `arbeitskreiseaufsicht`
  ADD UNIQUE KEY `id` (`gruppe`,`person`),
  ADD KEY `personenarbeitskreiseaufsicht` (`person`),
  ADD KEY `gruppenarbeitskreiseaufsicht` (`gruppe`);

--
-- Indizes für die Tabelle `arbeitskreiseblogeintraege`
--
ALTER TABLE `arbeitskreiseblogeintraege`
  ADD UNIQUE KEY `id` (`gruppe`,`blogeintrag`),
  ADD KEY `blogeintraegearbeitskreiseblogeintraege` (`blogeintrag`),
  ADD KEY `gruppenarbeitskreiseblogeintraege` (`gruppe`);

--
-- Indizes für die Tabelle `arbeitskreiseblogeintraegeintern`
--
ALTER TABLE `arbeitskreiseblogeintraegeintern`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gruppearbeitskreiseblogeintraegeintern` (`gruppe`);

--
-- Indizes für die Tabelle `arbeitskreiseblogeintragbeschluesse`
--
ALTER TABLE `arbeitskreiseblogeintragbeschluesse`
  ADD PRIMARY KEY (`id`),
  ADD KEY `blogeintragarbeitskreiseblogeintragbeschluesse` (`blogeintrag`);

--
-- Indizes für die Tabelle `arbeitskreiseblogeintragdownloads`
--
ALTER TABLE `arbeitskreiseblogeintragdownloads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `blogeintragdownloadsblogeintraege` (`blogeintrag`);

--
-- Indizes für die Tabelle `arbeitskreisechat`
--
ALTER TABLE `arbeitskreisechat`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`gruppe`,`person`),
  ADD KEY `gruppenarbeitskreisechat` (`gruppe`),
  ADD KEY `personenarbeitskreisechat` (`person`);

--
-- Indizes für die Tabelle `arbeitskreisemitglieder`
--
ALTER TABLE `arbeitskreisemitglieder`
  ADD UNIQUE KEY `id` (`gruppe`,`person`),
  ADD KEY `personenarbeitskreisemitglieder` (`person`),
  ADD KEY `gruppenarbeitskreisemitglieder` (`gruppe`);

--
-- Indizes für die Tabelle `arbeitskreisenotifikationsabo`
--
ALTER TABLE `arbeitskreisenotifikationsabo`
  ADD UNIQUE KEY `id` (`gruppe`,`person`),
  ADD KEY `personenarbeitskreisenotifikationsabo` (`person`),
  ADD KEY `gruppenarbeitskreisenotifikationsabo` (`gruppe`);

--
-- Indizes für die Tabelle `arbeitskreisetermine`
--
ALTER TABLE `arbeitskreisetermine`
  ADD UNIQUE KEY `id` (`gruppe`,`termin`),
  ADD KEY `terminearbeitskreisetermine` (`termin`),
  ADD KEY `gruppenarbeitskreisetermine` (`gruppe`);

--
-- Indizes für die Tabelle `arbeitskreisetermineintern`
--
ALTER TABLE `arbeitskreisetermineintern`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gruppearbeitskreisetermineintern` (`gruppe`);

--
-- Indizes für die Tabelle `arbeitskreisetermineinterndownloads`
--
ALTER TABLE `arbeitskreisetermineinterndownloads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `arbeitskreisetermineinterndownloadstermineintern` (`termin`);

--
-- Indizes für die Tabelle `arbeitskreisevorsitz`
--
ALTER TABLE `arbeitskreisevorsitz`
  ADD UNIQUE KEY `id` (`gruppe`,`person`),
  ADD KEY `personenarbeitskreisevorsitz` (`person`),
  ADD KEY `gruppenarbeitskreisevorsitz` (`gruppe`);

--
-- Indizes für die Tabelle `auffaelliges`
--
ALTER TABLE `auffaelliges`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- Indizes für die Tabelle `besucherstatistik_blog`
--
ALTER TABLE `besucherstatistik_blog`
  ADD UNIQUE KEY `jahr` (`jahr`,`monat`,`id`),
  ADD KEY `seitenbesucherstatistikwebsite` (`id`);

--
-- Indizes für die Tabelle `besucherstatistik_galerien`
--
ALTER TABLE `besucherstatistik_galerien`
  ADD UNIQUE KEY `jahr` (`jahr`,`monat`,`id`),
  ADD KEY `seitenbesucherstatistikwebsite` (`id`);

--
-- Indizes für die Tabelle `besucherstatistik_termine`
--
ALTER TABLE `besucherstatistik_termine`
  ADD UNIQUE KEY `jahr` (`jahr`,`monat`,`id`),
  ADD KEY `seitenbesucherstatistikwebsite` (`id`);

--
-- Indizes für die Tabelle `besucherstatistik_website`
--
ALTER TABLE `besucherstatistik_website`
  ADD UNIQUE KEY `jahr` (`jahr`,`monat`,`id`),
  ADD KEY `seitenbesucherstatistikwebsite` (`id`);

--
-- Indizes für die Tabelle `blogeintraege`
--
ALTER TABLE `blogeintraege`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `blogeintragdownloads`
--
ALTER TABLE `blogeintragdownloads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `blogeintragdownloadsblogeintraege` (`blogeintrag`);

--
-- Indizes für die Tabelle `boxen`
--
ALTER TABLE `boxen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `boxenboxenaussen` (`boxaussen`);

--
-- Indizes für die Tabelle `boxenaussen`
--
ALTER TABLE `boxenaussen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `boxaussenspalten` (`spalte`);

--
-- Indizes für die Tabelle `dauerbrenner`
--
ALTER TABLE `dauerbrenner`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `downloads`
--
ALTER TABLE `downloads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `downloadsspalten` (`spalte`);

--
-- Indizes für die Tabelle `editoren`
--
ALTER TABLE `editoren`
  ADD PRIMARY KEY (`id`),
  ADD KEY `editorenspalten` (`spalte`);

--
-- Indizes für die Tabelle `ereignisse`
--
ALTER TABLE `ereignisse`
  ADD PRIMARY KEY (`id`),
  ADD KEY `schuljahreereignisse` (`schuljahr`);

--
-- Indizes für die Tabelle `ereignisseaufsicht`
--
ALTER TABLE `ereignisseaufsicht`
  ADD UNIQUE KEY `id` (`gruppe`,`person`),
  ADD KEY `personenereignisseaufsicht` (`person`),
  ADD KEY `gruppenereignisseaufsicht` (`gruppe`);

--
-- Indizes für die Tabelle `ereignisseblogeintraege`
--
ALTER TABLE `ereignisseblogeintraege`
  ADD UNIQUE KEY `id` (`gruppe`,`blogeintrag`),
  ADD KEY `blogeintraegeereignisseblogeintraege` (`blogeintrag`),
  ADD KEY `gruppenereignisseblogeintraege` (`gruppe`);

--
-- Indizes für die Tabelle `ereignisseblogeintraegeintern`
--
ALTER TABLE `ereignisseblogeintraegeintern`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gruppeereignisseblogeintraegeintern` (`gruppe`);

--
-- Indizes für die Tabelle `ereignisseblogeintragbeschluesse`
--
ALTER TABLE `ereignisseblogeintragbeschluesse`
  ADD PRIMARY KEY (`id`),
  ADD KEY `blogeintragereignisseblogeintragbeschluesse` (`blogeintrag`);

--
-- Indizes für die Tabelle `ereignisseblogeintragdownloads`
--
ALTER TABLE `ereignisseblogeintragdownloads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `blogeintragdownloadsblogeintraege` (`blogeintrag`);

--
-- Indizes für die Tabelle `ereignissechat`
--
ALTER TABLE `ereignissechat`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`gruppe`,`person`),
  ADD KEY `gruppenereignissechat` (`gruppe`),
  ADD KEY `personenereignissechat` (`person`);

--
-- Indizes für die Tabelle `ereignissemitglieder`
--
ALTER TABLE `ereignissemitglieder`
  ADD UNIQUE KEY `id` (`gruppe`,`person`),
  ADD KEY `personenereignissemitglieder` (`person`),
  ADD KEY `gruppenereignissemitglieder` (`gruppe`);

--
-- Indizes für die Tabelle `ereignissenotifikationsabo`
--
ALTER TABLE `ereignissenotifikationsabo`
  ADD UNIQUE KEY `id` (`gruppe`,`person`),
  ADD KEY `personenereignissenotifikationsabo` (`person`),
  ADD KEY `gruppenereignissenotifikationsabo` (`gruppe`);

--
-- Indizes für die Tabelle `ereignissetermine`
--
ALTER TABLE `ereignissetermine`
  ADD UNIQUE KEY `id` (`gruppe`,`termin`),
  ADD KEY `termineereignissetermine` (`termin`),
  ADD KEY `gruppenereignissetermine` (`gruppe`);

--
-- Indizes für die Tabelle `ereignissetermineintern`
--
ALTER TABLE `ereignissetermineintern`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gruppeereignissetermineintern` (`gruppe`);

--
-- Indizes für die Tabelle `ereignissetermineinterndownloads`
--
ALTER TABLE `ereignissetermineinterndownloads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ereignissetermineinterndownloadstermineintern` (`termin`);

--
-- Indizes für die Tabelle `ereignissevorsitz`
--
ALTER TABLE `ereignissevorsitz`
  ADD UNIQUE KEY `id` (`gruppe`,`person`),
  ADD KEY `personenereignissevorsitz` (`person`),
  ADD KEY `gruppenereignissevorsitz` (`gruppe`);

--
-- Indizes für die Tabelle `eventuebersichten`
--
ALTER TABLE `eventuebersichten`
  ADD PRIMARY KEY (`id`),
  ADD KEY `eventuebersichtenspalten` (`spalte`);

--
-- Indizes für die Tabelle `fachkollegen`
--
ALTER TABLE `fachkollegen`
  ADD PRIMARY KEY (`fach`,`kollege`) USING BTREE,
  ADD KEY `fachkolelgenpersonen` (`kollege`);

--
-- Indizes für die Tabelle `fachschaften`
--
ALTER TABLE `fachschaften`
  ADD PRIMARY KEY (`id`),
  ADD KEY `schuljahrefachschaften` (`schuljahr`);

--
-- Indizes für die Tabelle `fachschaftenaufsicht`
--
ALTER TABLE `fachschaftenaufsicht`
  ADD UNIQUE KEY `id` (`gruppe`,`person`),
  ADD KEY `personenfachschaftenaufsicht` (`person`),
  ADD KEY `gruppenfachschaftenaufsicht` (`gruppe`);

--
-- Indizes für die Tabelle `fachschaftenblogeintraege`
--
ALTER TABLE `fachschaftenblogeintraege`
  ADD UNIQUE KEY `id` (`gruppe`,`blogeintrag`),
  ADD KEY `blogeintraegefachschaftenblogeintraege` (`blogeintrag`),
  ADD KEY `gruppenfachschaftenblogeintraege` (`gruppe`);

--
-- Indizes für die Tabelle `fachschaftenblogeintraegeintern`
--
ALTER TABLE `fachschaftenblogeintraegeintern`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gruppefachschaftenblogeintraegeintern` (`gruppe`);

--
-- Indizes für die Tabelle `fachschaftenblogeintragbeschluesse`
--
ALTER TABLE `fachschaftenblogeintragbeschluesse`
  ADD PRIMARY KEY (`id`),
  ADD KEY `blogeintragfachschaftenblogeintragbeschluesse` (`blogeintrag`);

--
-- Indizes für die Tabelle `fachschaftenblogeintragdownloads`
--
ALTER TABLE `fachschaftenblogeintragdownloads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `blogeintragdownloadsblogeintraege` (`blogeintrag`);

--
-- Indizes für die Tabelle `fachschaftenchat`
--
ALTER TABLE `fachschaftenchat`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`gruppe`,`person`),
  ADD KEY `gruppenfachschaftenchat` (`gruppe`),
  ADD KEY `personenfachschaftenchat` (`person`);

--
-- Indizes für die Tabelle `fachschaftenmitglieder`
--
ALTER TABLE `fachschaftenmitglieder`
  ADD UNIQUE KEY `id` (`gruppe`,`person`),
  ADD KEY `personenfachschaftenmitglieder` (`person`),
  ADD KEY `gruppenfachschaftenmitglieder` (`gruppe`);

--
-- Indizes für die Tabelle `fachschaftennotifikationsabo`
--
ALTER TABLE `fachschaftennotifikationsabo`
  ADD UNIQUE KEY `id` (`gruppe`,`person`),
  ADD KEY `personenfachschaftennotifikationsabo` (`person`),
  ADD KEY `gruppenfachschaftennotifikationsabo` (`gruppe`);

--
-- Indizes für die Tabelle `fachschaftentermine`
--
ALTER TABLE `fachschaftentermine`
  ADD UNIQUE KEY `id` (`gruppe`,`termin`),
  ADD KEY `terminefachschaftentermine` (`termin`),
  ADD KEY `gruppenfachschaftentermine` (`gruppe`);

--
-- Indizes für die Tabelle `fachschaftentermineintern`
--
ALTER TABLE `fachschaftentermineintern`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gruppefachschaftentermineintern` (`gruppe`);

--
-- Indizes für die Tabelle `fachschaftentermineinterndownloads`
--
ALTER TABLE `fachschaftentermineinterndownloads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fachschaftentermineinterndownloadstermineintern` (`termin`);

--
-- Indizes für die Tabelle `fachschaftenvorsitz`
--
ALTER TABLE `fachschaftenvorsitz`
  ADD UNIQUE KEY `id` (`gruppe`,`person`),
  ADD KEY `personenfachschaftenvorsitz` (`person`),
  ADD KEY `gruppenfachschaftenvorsitz` (`gruppe`);

--
-- Indizes für die Tabelle `faecher`
--
ALTER TABLE `faecher`
  ADD PRIMARY KEY (`id`),
  ADD KEY `faecherschuljahre` (`schuljahr`);

--
-- Indizes für die Tabelle `fahrten`
--
ALTER TABLE `fahrten`
  ADD PRIMARY KEY (`id`),
  ADD KEY `schuljahrefahrten` (`schuljahr`);

--
-- Indizes für die Tabelle `fahrtenaufsicht`
--
ALTER TABLE `fahrtenaufsicht`
  ADD UNIQUE KEY `id` (`gruppe`,`person`),
  ADD KEY `personenfahrtenaufsicht` (`person`),
  ADD KEY `gruppenfahrtenaufsicht` (`gruppe`);

--
-- Indizes für die Tabelle `fahrtenblogeintraege`
--
ALTER TABLE `fahrtenblogeintraege`
  ADD UNIQUE KEY `id` (`gruppe`,`blogeintrag`),
  ADD KEY `blogeintraegefahrtenblogeintraege` (`blogeintrag`),
  ADD KEY `gruppenfahrtenblogeintraege` (`gruppe`);

--
-- Indizes für die Tabelle `fahrtenblogeintraegeintern`
--
ALTER TABLE `fahrtenblogeintraegeintern`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gruppefahrtenblogeintraegeintern` (`gruppe`);

--
-- Indizes für die Tabelle `fahrtenblogeintragbeschluesse`
--
ALTER TABLE `fahrtenblogeintragbeschluesse`
  ADD PRIMARY KEY (`id`),
  ADD KEY `blogeintragfahrtenblogeintragbeschluesse` (`blogeintrag`);

--
-- Indizes für die Tabelle `fahrtenblogeintragdownloads`
--
ALTER TABLE `fahrtenblogeintragdownloads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `blogeintragdownloadsblogeintraege` (`blogeintrag`);

--
-- Indizes für die Tabelle `fahrtenchat`
--
ALTER TABLE `fahrtenchat`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`gruppe`,`person`),
  ADD KEY `gruppenfahrtenchat` (`gruppe`),
  ADD KEY `personenfahrtenchat` (`person`);

--
-- Indizes für die Tabelle `fahrtenmitglieder`
--
ALTER TABLE `fahrtenmitglieder`
  ADD UNIQUE KEY `id` (`gruppe`,`person`),
  ADD KEY `personenfahrtenmitglieder` (`person`),
  ADD KEY `gruppenfahrtenmitglieder` (`gruppe`);

--
-- Indizes für die Tabelle `fahrtennotifikationsabo`
--
ALTER TABLE `fahrtennotifikationsabo`
  ADD UNIQUE KEY `id` (`gruppe`,`person`),
  ADD KEY `personenfahrtennotifikationsabo` (`person`),
  ADD KEY `gruppenfahrtennotifikationsabo` (`gruppe`);

--
-- Indizes für die Tabelle `fahrtentermine`
--
ALTER TABLE `fahrtentermine`
  ADD UNIQUE KEY `id` (`gruppe`,`termin`),
  ADD KEY `terminefahrtentermine` (`termin`),
  ADD KEY `gruppenfahrtentermine` (`gruppe`);

--
-- Indizes für die Tabelle `fahrtentermineintern`
--
ALTER TABLE `fahrtentermineintern`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gruppefahrtentermineintern` (`gruppe`);

--
-- Indizes für die Tabelle `fahrtentermineinterndownloads`
--
ALTER TABLE `fahrtentermineinterndownloads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fahrtentermineinterndownloadstermineintern` (`termin`);

--
-- Indizes für die Tabelle `fahrtenvorsitz`
--
ALTER TABLE `fahrtenvorsitz`
  ADD UNIQUE KEY `id` (`gruppe`,`person`),
  ADD KEY `personenfahrtenvorsitz` (`person`),
  ADD KEY `gruppenfahrtenvorsitz` (`gruppe`);

--
-- Indizes für die Tabelle `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- Indizes für die Tabelle `fehlermeldungen`
--
ALTER TABLE `fehlermeldungen`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `ferien`
--
ALTER TABLE `ferien`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `galerien`
--
ALTER TABLE `galerien`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `gremien`
--
ALTER TABLE `gremien`
  ADD PRIMARY KEY (`id`),
  ADD KEY `schuljahregremien` (`schuljahr`);

--
-- Indizes für die Tabelle `gremienaufsicht`
--
ALTER TABLE `gremienaufsicht`
  ADD UNIQUE KEY `id` (`gruppe`,`person`),
  ADD KEY `personengremienaufsicht` (`person`),
  ADD KEY `gruppengremienaufsicht` (`gruppe`);

--
-- Indizes für die Tabelle `gremienblogeintraege`
--
ALTER TABLE `gremienblogeintraege`
  ADD UNIQUE KEY `id` (`gruppe`,`blogeintrag`),
  ADD KEY `blogeintraegegremienblogeintraege` (`blogeintrag`),
  ADD KEY `gruppengremienblogeintraege` (`gruppe`);

--
-- Indizes für die Tabelle `gremienblogeintraegeintern`
--
ALTER TABLE `gremienblogeintraegeintern`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gruppegremienblogeintraegeintern` (`gruppe`);

--
-- Indizes für die Tabelle `gremienblogeintragbeschluesse`
--
ALTER TABLE `gremienblogeintragbeschluesse`
  ADD PRIMARY KEY (`id`),
  ADD KEY `blogeintraggremienblogeintragbeschluesse` (`blogeintrag`);

--
-- Indizes für die Tabelle `gremienblogeintragdownloads`
--
ALTER TABLE `gremienblogeintragdownloads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `blogeintragdownloadsblogeintraege` (`blogeintrag`);

--
-- Indizes für die Tabelle `gremienchat`
--
ALTER TABLE `gremienchat`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`gruppe`,`person`),
  ADD KEY `gruppengremienchat` (`gruppe`),
  ADD KEY `personengremienchat` (`person`);

--
-- Indizes für die Tabelle `gremienmitglieder`
--
ALTER TABLE `gremienmitglieder`
  ADD UNIQUE KEY `id` (`gruppe`,`person`),
  ADD KEY `personengremienmitglieder` (`person`),
  ADD KEY `gruppengremienmitglieder` (`gruppe`);

--
-- Indizes für die Tabelle `gremiennotifikationsabo`
--
ALTER TABLE `gremiennotifikationsabo`
  ADD UNIQUE KEY `id` (`gruppe`,`person`),
  ADD KEY `personengremiennotifikationsabo` (`person`),
  ADD KEY `gruppengremiennotifikationsabo` (`gruppe`);

--
-- Indizes für die Tabelle `gremientermine`
--
ALTER TABLE `gremientermine`
  ADD UNIQUE KEY `id` (`gruppe`,`termin`),
  ADD KEY `terminegremientermine` (`termin`),
  ADD KEY `gruppengremientermine` (`gruppe`);

--
-- Indizes für die Tabelle `gremientermineintern`
--
ALTER TABLE `gremientermineintern`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gruppegremientermineintern` (`gruppe`);

--
-- Indizes für die Tabelle `gremientermineinterndownloads`
--
ALTER TABLE `gremientermineinterndownloads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gremientermineinterndownloadstermineintern` (`termin`);

--
-- Indizes für die Tabelle `gremienvorsitz`
--
ALTER TABLE `gremienvorsitz`
  ADD UNIQUE KEY `id` (`gruppe`,`person`),
  ADD KEY `personengremienvorsitz` (`person`),
  ADD KEY `gruppengremienvorsitz` (`gruppe`);

--
-- Indizes für die Tabelle `hausmeisterauftraege`
--
ALTER TABLE `hausmeisterauftraege`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `identitaetsdiebstahl`
--
ALTER TABLE `identitaetsdiebstahl`
  ADD UNIQUE KEY `id` (`id`,`zeit`);

--
-- Indizes für die Tabelle `internedienste`
--
ALTER TABLE `internedienste`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `klassen`
--
ALTER TABLE `klassen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `schuljahreklassen` (`schuljahr`),
  ADD KEY `stufenklassen` (`stufe`);

--
-- Indizes für die Tabelle `klassenaufsicht`
--
ALTER TABLE `klassenaufsicht`
  ADD UNIQUE KEY `id` (`gruppe`,`person`),
  ADD KEY `personenklassenaufsicht` (`person`),
  ADD KEY `gruppenklassenaufsicht` (`gruppe`);

--
-- Indizes für die Tabelle `klassenblogeintraege`
--
ALTER TABLE `klassenblogeintraege`
  ADD UNIQUE KEY `id` (`gruppe`,`blogeintrag`),
  ADD KEY `blogeintraegeklassenblogeintraege` (`blogeintrag`),
  ADD KEY `gruppenklassenblogeintraege` (`gruppe`);

--
-- Indizes für die Tabelle `klassenblogeintraegeintern`
--
ALTER TABLE `klassenblogeintraegeintern`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gruppeklassenblogeintraegeintern` (`gruppe`);

--
-- Indizes für die Tabelle `klassenblogeintragbeschluesse`
--
ALTER TABLE `klassenblogeintragbeschluesse`
  ADD PRIMARY KEY (`id`),
  ADD KEY `blogeintragklassenblogeintragbeschluesse` (`blogeintrag`);

--
-- Indizes für die Tabelle `klassenblogeintragdownloads`
--
ALTER TABLE `klassenblogeintragdownloads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `blogeintragdownloadsblogeintraege` (`blogeintrag`);

--
-- Indizes für die Tabelle `klassenchat`
--
ALTER TABLE `klassenchat`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`gruppe`,`person`),
  ADD KEY `gruppenklassenchat` (`gruppe`),
  ADD KEY `personenklassenchat` (`person`);

--
-- Indizes für die Tabelle `klassenmitglieder`
--
ALTER TABLE `klassenmitglieder`
  ADD UNIQUE KEY `id` (`gruppe`,`person`),
  ADD KEY `personenklassenmitglieder` (`person`),
  ADD KEY `gruppenklassenmitglieder` (`gruppe`);

--
-- Indizes für die Tabelle `klassennotifikationsabo`
--
ALTER TABLE `klassennotifikationsabo`
  ADD UNIQUE KEY `id` (`gruppe`,`person`),
  ADD KEY `personenklassennotifikationsabo` (`person`),
  ADD KEY `gruppenklassennotifikationsabo` (`gruppe`);

--
-- Indizes für die Tabelle `klassentermine`
--
ALTER TABLE `klassentermine`
  ADD UNIQUE KEY `id` (`gruppe`,`termin`),
  ADD KEY `termineklassentermine` (`termin`),
  ADD KEY `gruppenklassentermine` (`gruppe`);

--
-- Indizes für die Tabelle `klassentermineintern`
--
ALTER TABLE `klassentermineintern`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gruppeklassentermineintern` (`gruppe`);

--
-- Indizes für die Tabelle `klassentermineinterndownloads`
--
ALTER TABLE `klassentermineinterndownloads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `klassentermineinterndownloadstermineintern` (`termin`);

--
-- Indizes für die Tabelle `klassenvorsitz`
--
ALTER TABLE `klassenvorsitz`
  ADD UNIQUE KEY `id` (`gruppe`,`person`),
  ADD KEY `personenklassenvorsitz` (`person`),
  ADD KEY `gruppenklassenvorsitz` (`gruppe`);

--
-- Indizes für die Tabelle `kontaktformulare`
--
ALTER TABLE `kontaktformulare`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kontaktformularespalten` (`spalte`);

--
-- Indizes für die Tabelle `kontaktformulareempfaenger`
--
ALTER TABLE `kontaktformulareempfaenger`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kontaktformulareempfaengerkontaktformulare` (`kontaktformular`);

--
-- Indizes für die Tabelle `kurse`
--
ALTER TABLE `kurse`
  ADD PRIMARY KEY (`id`),
  ADD KEY `schuljahrekurse` (`schuljahr`),
  ADD KEY `stufenkurse` (`stufe`);

--
-- Indizes für die Tabelle `kurseaufsicht`
--
ALTER TABLE `kurseaufsicht`
  ADD UNIQUE KEY `id` (`gruppe`,`person`),
  ADD KEY `personenkurseaufsicht` (`person`),
  ADD KEY `gruppenkurseaufsicht` (`gruppe`);

--
-- Indizes für die Tabelle `kurseblogeintraege`
--
ALTER TABLE `kurseblogeintraege`
  ADD UNIQUE KEY `id` (`gruppe`,`blogeintrag`),
  ADD KEY `blogeintraegekurseblogeintraege` (`blogeintrag`),
  ADD KEY `gruppenkurseblogeintraege` (`gruppe`);

--
-- Indizes für die Tabelle `kurseblogeintraegeintern`
--
ALTER TABLE `kurseblogeintraegeintern`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gruppekurseblogeintraegeintern` (`gruppe`);

--
-- Indizes für die Tabelle `kurseblogeintragbeschluesse`
--
ALTER TABLE `kurseblogeintragbeschluesse`
  ADD PRIMARY KEY (`id`),
  ADD KEY `blogeintragkurseblogeintragbeschluesse` (`blogeintrag`);

--
-- Indizes für die Tabelle `kurseblogeintragdownloads`
--
ALTER TABLE `kurseblogeintragdownloads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `blogeintragdownloadsblogeintraege` (`blogeintrag`);

--
-- Indizes für die Tabelle `kursechat`
--
ALTER TABLE `kursechat`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`gruppe`,`person`),
  ADD KEY `gruppenkursechat` (`gruppe`),
  ADD KEY `personenkursechat` (`person`);

--
-- Indizes für die Tabelle `kurseklassen`
--
ALTER TABLE `kurseklassen`
  ADD UNIQUE KEY `kurs` (`kurs`,`klasse`),
  ADD KEY `kurseklassenklassen` (`klasse`);

--
-- Indizes für die Tabelle `kursemitglieder`
--
ALTER TABLE `kursemitglieder`
  ADD UNIQUE KEY `id` (`gruppe`,`person`),
  ADD KEY `personenkursemitglieder` (`person`),
  ADD KEY `gruppenkursemitglieder` (`gruppe`);

--
-- Indizes für die Tabelle `kursenotifikationsabo`
--
ALTER TABLE `kursenotifikationsabo`
  ADD UNIQUE KEY `id` (`gruppe`,`person`),
  ADD KEY `personenkursenotifikationsabo` (`person`),
  ADD KEY `gruppenkursenotifikationsabo` (`gruppe`);

--
-- Indizes für die Tabelle `kursetermine`
--
ALTER TABLE `kursetermine`
  ADD UNIQUE KEY `id` (`gruppe`,`termin`),
  ADD KEY `terminekursetermine` (`termin`),
  ADD KEY `gruppenkursetermine` (`gruppe`);

--
-- Indizes für die Tabelle `kursetermineintern`
--
ALTER TABLE `kursetermineintern`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gruppekursetermineintern` (`gruppe`);

--
-- Indizes für die Tabelle `kursetermineinterndownloads`
--
ALTER TABLE `kursetermineinterndownloads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kursetermineinterndownloadstermineintern` (`termin`);

--
-- Indizes für die Tabelle `kursevorsitz`
--
ALTER TABLE `kursevorsitz`
  ADD UNIQUE KEY `id` (`gruppe`,`person`),
  ADD KEY `personenkursevorsitz` (`person`),
  ADD KEY `gruppenkursevorsitz` (`gruppe`);

--
-- Indizes für die Tabelle `lehrer`
--
ALTER TABLE `lehrer`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `leihen`
--
ALTER TABLE `leihen`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `leihenblockieren`
--
ALTER TABLE `leihenblockieren`
  ADD PRIMARY KEY (`id`),
  ADD KEY `leihenblockierenleihen` (`standort`) USING BTREE;

--
-- Indizes für die Tabelle `leihenbuchen`
--
ALTER TABLE `leihenbuchen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `leihenbuchenpersonen` (`person`),
  ADD KEY `leihenblockierenleihen` (`standort`) USING BTREE;

--
-- Indizes für die Tabelle `leihengeraete`
--
ALTER TABLE `leihengeraete`
  ADD PRIMARY KEY (`id`),
  ADD KEY `leihengeraeteleihen` (`standort`);

--
-- Indizes für die Tabelle `navigationen`
--
ALTER TABLE `navigationen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `spaltennavigationen` (`spalte`);

--
-- Indizes für die Tabelle `notifikationen`
--
ALTER TABLE `notifikationen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifikationenpersonen` (`person`);

--
-- Indizes für die Tabelle `nutzerkonten`
--
ALTER TABLE `nutzerkonten`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `personen`
--
ALTER TABLE `personen`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `personen_einstellungen`
--
ALTER TABLE `personen_einstellungen`
  ADD PRIMARY KEY (`person`);

--
-- Indizes für die Tabelle `personen_signaturen`
--
ALTER TABLE `personen_signaturen`
  ADD PRIMARY KEY (`person`);

--
-- Indizes für die Tabelle `pinnwaende`
--
ALTER TABLE `pinnwaende`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `pinnwandanschlag`
--
ALTER TABLE `pinnwandanschlag`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pinnwandanschlagpinnwaende` (`pinnwand`);

--
-- Indizes für die Tabelle `profile`
--
ALTER TABLE `profile`
  ADD PRIMARY KEY (`id`),
  ADD KEY `profileschuljahre` (`schuljahr`),
  ADD KEY `profilestufen` (`stufe`);

--
-- Indizes für die Tabelle `profilfaecher`
--
ALTER TABLE `profilfaecher`
  ADD PRIMARY KEY (`profil`,`fach`),
  ADD KEY `profilfaecherfach` (`fach`);

--
-- Indizes für die Tabelle `raeume`
--
ALTER TABLE `raeume`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `raeumeblockieren`
--
ALTER TABLE `raeumeblockieren`
  ADD PRIMARY KEY (`id`),
  ADD KEY `raeumeblockierenraeume` (`standort`);

--
-- Indizes für die Tabelle `raeumebuchen`
--
ALTER TABLE `raeumebuchen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `raeumeblockierenraeume` (`standort`),
  ADD KEY `raeumebuchenperosnen` (`person`);

--
-- Indizes für die Tabelle `raeumegeraete`
--
ALTER TABLE `raeumegeraete`
  ADD PRIMARY KEY (`id`),
  ADD KEY `raeumegeraeteraeume` (`standort`);

--
-- Indizes für die Tabelle `rechte`
--
ALTER TABLE `rechte`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `rechtzuordnung`
--
ALTER TABLE `rechtzuordnung`
  ADD UNIQUE KEY `person` (`person`,`recht`),
  ADD KEY `rechtzuordnungrecht` (`recht`);

--
-- Indizes für die Tabelle `rollen`
--
ALTER TABLE `rollen`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `rollenrechte`
--
ALTER TABLE `rollenrechte`
  ADD UNIQUE KEY `rolle` (`rolle`,`recht`),
  ADD KEY `rollenrechterechte` (`recht`);

--
-- Indizes für die Tabelle `rollenzuordnung`
--
ALTER TABLE `rollenzuordnung`
  ADD UNIQUE KEY `person` (`person`,`rolle`),
  ADD KEY `rollenzuordnungrollen` (`rolle`);

--
-- Indizes für die Tabelle `schluesselposition`
--
ALTER TABLE `schluesselposition`
  ADD UNIQUE KEY `person` (`person`,`position`,`schuljahr`),
  ADD KEY `schluesselpositionschuljahr` (`schuljahr`);

--
-- Indizes für die Tabelle `schuelereltern`
--
ALTER TABLE `schuelereltern`
  ADD UNIQUE KEY `schueler` (`schueler`,`eltern`),
  ADD KEY `schuelerelterneltern` (`eltern`);

--
-- Indizes für die Tabelle `schulanmeldung`
--
ALTER TABLE `schulanmeldung`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `schuljahre`
--
ALTER TABLE `schuljahre`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `schulstunden`
--
ALTER TABLE `schulstunden`
  ADD PRIMARY KEY (`id`),
  ADD KEY `schulstundenzeitraeume` (`zeitraum`);

--
-- Indizes für die Tabelle `seiten`
--
ALTER TABLE `seiten`
  ADD PRIMARY KEY (`id`),
  ADD KEY `seitenseiten` (`zuordnung`);

--
-- Indizes für die Tabelle `sonstigegruppen`
--
ALTER TABLE `sonstigegruppen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `schuljahresonstigegruppen` (`schuljahr`);

--
-- Indizes für die Tabelle `sonstigegruppenaufsicht`
--
ALTER TABLE `sonstigegruppenaufsicht`
  ADD UNIQUE KEY `id` (`gruppe`,`person`),
  ADD KEY `personensonstigegruppenaufsicht` (`person`),
  ADD KEY `gruppensonstigegruppenaufsicht` (`gruppe`);

--
-- Indizes für die Tabelle `sonstigegruppenblogeintraege`
--
ALTER TABLE `sonstigegruppenblogeintraege`
  ADD UNIQUE KEY `id` (`gruppe`,`blogeintrag`),
  ADD KEY `blogeintraegesonstigegruppenblogeintraege` (`blogeintrag`),
  ADD KEY `gruppensonstigegruppenblogeintraege` (`gruppe`);

--
-- Indizes für die Tabelle `sonstigegruppenblogeintraegeintern`
--
ALTER TABLE `sonstigegruppenblogeintraegeintern`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gruppesonstigegruppenblogeintraegeintern` (`gruppe`);

--
-- Indizes für die Tabelle `sonstigegruppenblogeintragbeschluesse`
--
ALTER TABLE `sonstigegruppenblogeintragbeschluesse`
  ADD PRIMARY KEY (`id`),
  ADD KEY `blogeintragsonstigegruppenblogeintragbeschluesse` (`blogeintrag`);

--
-- Indizes für die Tabelle `sonstigegruppenblogeintragdownloads`
--
ALTER TABLE `sonstigegruppenblogeintragdownloads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `blogeintragdownloadsblogeintraege` (`blogeintrag`);

--
-- Indizes für die Tabelle `sonstigegruppenchat`
--
ALTER TABLE `sonstigegruppenchat`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`gruppe`,`person`),
  ADD KEY `gruppensonstigegruppenchat` (`gruppe`),
  ADD KEY `personensonstigegruppenchat` (`person`);

--
-- Indizes für die Tabelle `sonstigegruppenmitglieder`
--
ALTER TABLE `sonstigegruppenmitglieder`
  ADD UNIQUE KEY `id` (`gruppe`,`person`),
  ADD KEY `personensonstigegruppenmitglieder` (`person`),
  ADD KEY `gruppensonstigegruppenmitglieder` (`gruppe`);

--
-- Indizes für die Tabelle `sonstigegruppennotifikationsabo`
--
ALTER TABLE `sonstigegruppennotifikationsabo`
  ADD UNIQUE KEY `id` (`gruppe`,`person`),
  ADD KEY `personensonstigegruppennotifikationsabo` (`person`),
  ADD KEY `gruppensonstigegruppennotifikationsabo` (`gruppe`);

--
-- Indizes für die Tabelle `sonstigegruppentermine`
--
ALTER TABLE `sonstigegruppentermine`
  ADD UNIQUE KEY `id` (`gruppe`,`termin`),
  ADD KEY `terminesonstigegruppentermine` (`termin`),
  ADD KEY `gruppensonstigegruppentermine` (`gruppe`);

--
-- Indizes für die Tabelle `sonstigegruppentermineintern`
--
ALTER TABLE `sonstigegruppentermineintern`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gruppesonstigegruppentermineintern` (`gruppe`);

--
-- Indizes für die Tabelle `sonstigegruppentermineinterndownloads`
--
ALTER TABLE `sonstigegruppentermineinterndownloads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sonstigegruppentermineinterndownloadstermineintern` (`termin`);

--
-- Indizes für die Tabelle `sonstigegruppenvorsitz`
--
ALTER TABLE `sonstigegruppenvorsitz`
  ADD UNIQUE KEY `id` (`gruppe`,`person`),
  ADD KEY `personensonstigegruppenvorsitz` (`person`),
  ADD KEY `gruppensonstigegruppenvorsitz` (`gruppe`);

--
-- Indizes für die Tabelle `spalten`
--
ALTER TABLE `spalten`
  ADD PRIMARY KEY (`id`),
  ADD KEY `spaltenseiten` (`seite`);

--
-- Indizes für die Tabelle `statistikblog`
--
ALTER TABLE `statistikblog`
  ADD UNIQUE KEY `jahr` (`jahr`,`monat`,`id`),
  ADD KEY `seitenstatistikwebsite` (`id`);

--
-- Indizes für die Tabelle `statistikgalerie`
--
ALTER TABLE `statistikgalerie`
  ADD UNIQUE KEY `jahr` (`jahr`,`monat`,`id`),
  ADD KEY `seitenstatistikwebsite` (`id`);

--
-- Indizes für die Tabelle `statistiktermine`
--
ALTER TABLE `statistiktermine`
  ADD UNIQUE KEY `jahr` (`jahr`,`monat`,`id`),
  ADD KEY `seitenstatistikwebsite` (`id`);

--
-- Indizes für die Tabelle `statistikwebsite`
--
ALTER TABLE `statistikwebsite`
  ADD UNIQUE KEY `jahr` (`jahr`,`monat`,`id`),
  ADD KEY `seitenstatistikwebsite` (`id`);

--
-- Indizes für die Tabelle `stufen`
--
ALTER TABLE `stufen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `schuljahrestufen` (`schuljahr`);

--
-- Indizes für die Tabelle `stufenaufsicht`
--
ALTER TABLE `stufenaufsicht`
  ADD UNIQUE KEY `id` (`gruppe`,`person`),
  ADD KEY `personenstufenaufsicht` (`person`),
  ADD KEY `gruppenstufenaufsicht` (`gruppe`);

--
-- Indizes für die Tabelle `stufenblogeintraege`
--
ALTER TABLE `stufenblogeintraege`
  ADD UNIQUE KEY `id` (`gruppe`,`blogeintrag`),
  ADD KEY `blogeintraegestufenblogeintraege` (`blogeintrag`),
  ADD KEY `gruppenstufenblogeintraege` (`gruppe`);

--
-- Indizes für die Tabelle `stufenblogeintraegeintern`
--
ALTER TABLE `stufenblogeintraegeintern`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gruppestufenblogeintraegeintern` (`gruppe`);

--
-- Indizes für die Tabelle `stufenblogeintragbeschluesse`
--
ALTER TABLE `stufenblogeintragbeschluesse`
  ADD PRIMARY KEY (`id`),
  ADD KEY `blogeintragstufenblogeintragbeschluesse` (`blogeintrag`);

--
-- Indizes für die Tabelle `stufenblogeintragdownloads`
--
ALTER TABLE `stufenblogeintragdownloads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `blogeintragdownloadsblogeintraege` (`blogeintrag`);

--
-- Indizes für die Tabelle `stufenchat`
--
ALTER TABLE `stufenchat`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`gruppe`,`person`),
  ADD KEY `gruppenstufenchat` (`gruppe`),
  ADD KEY `personenstufenchat` (`person`);

--
-- Indizes für die Tabelle `stufenmitglieder`
--
ALTER TABLE `stufenmitglieder`
  ADD UNIQUE KEY `id` (`gruppe`,`person`),
  ADD KEY `personenstufenmitglieder` (`person`),
  ADD KEY `gruppenstufenmitglieder` (`gruppe`);

--
-- Indizes für die Tabelle `stufennotifikationsabo`
--
ALTER TABLE `stufennotifikationsabo`
  ADD UNIQUE KEY `id` (`gruppe`,`person`),
  ADD KEY `personenstufennotifikationsabo` (`person`),
  ADD KEY `gruppenstufennotifikationsabo` (`gruppe`);

--
-- Indizes für die Tabelle `stufentermine`
--
ALTER TABLE `stufentermine`
  ADD UNIQUE KEY `id` (`gruppe`,`termin`),
  ADD KEY `terminestufentermine` (`termin`),
  ADD KEY `gruppenstufentermine` (`gruppe`);

--
-- Indizes für die Tabelle `stufentermineintern`
--
ALTER TABLE `stufentermineintern`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gruppestufentermineintern` (`gruppe`);

--
-- Indizes für die Tabelle `stufentermineinterndownloads`
--
ALTER TABLE `stufentermineinterndownloads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stufentermineinterndownloadstermineintern` (`termin`);

--
-- Indizes für die Tabelle `stufenvorsitz`
--
ALTER TABLE `stufenvorsitz`
  ADD UNIQUE KEY `id` (`gruppe`,`person`),
  ADD KEY `personenstufenvorsitz` (`person`),
  ADD KEY `gruppenstufenvorsitz` (`gruppe`);

--
-- Indizes für die Tabelle `termine`
--
ALTER TABLE `termine`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `terminedownloads`
--
ALTER TABLE `terminedownloads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `terminedownloadstermine` (`termin`);

--
-- Indizes für die Tabelle `voranmeldung_eltern`
--
ALTER TABLE `voranmeldung_eltern`
  ADD PRIMARY KEY (`id`),
  ADD KEY `voranmeldungschueleransprechpartner` (`schueler`);

--
-- Indizes für die Tabelle `voranmeldung_schueler`
--
ALTER TABLE `voranmeldung_schueler`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `vpn`
--
ALTER TABLE `vpn`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `wettbewerbe`
--
ALTER TABLE `wettbewerbe`
  ADD PRIMARY KEY (`id`),
  ADD KEY `schuljahrewettbewerbe` (`schuljahr`);

--
-- Indizes für die Tabelle `wettbewerbeaufsicht`
--
ALTER TABLE `wettbewerbeaufsicht`
  ADD UNIQUE KEY `id` (`gruppe`,`person`),
  ADD KEY `personenwettbewerbeaufsicht` (`person`),
  ADD KEY `gruppenwettbewerbeaufsicht` (`gruppe`);

--
-- Indizes für die Tabelle `wettbewerbeblogeintraege`
--
ALTER TABLE `wettbewerbeblogeintraege`
  ADD UNIQUE KEY `id` (`gruppe`,`blogeintrag`),
  ADD KEY `blogeintraegewettbewerbeblogeintraege` (`blogeintrag`),
  ADD KEY `gruppenwettbewerbeblogeintraege` (`gruppe`);

--
-- Indizes für die Tabelle `wettbewerbeblogeintraegeintern`
--
ALTER TABLE `wettbewerbeblogeintraegeintern`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gruppewettbewerbeblogeintraegeintern` (`gruppe`);

--
-- Indizes für die Tabelle `wettbewerbeblogeintragbeschluesse`
--
ALTER TABLE `wettbewerbeblogeintragbeschluesse`
  ADD PRIMARY KEY (`id`),
  ADD KEY `blogeintragwettbewerbeblogeintragbeschluesse` (`blogeintrag`);

--
-- Indizes für die Tabelle `wettbewerbeblogeintragdownloads`
--
ALTER TABLE `wettbewerbeblogeintragdownloads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `blogeintragdownloadsblogeintraege` (`blogeintrag`);

--
-- Indizes für die Tabelle `wettbewerbechat`
--
ALTER TABLE `wettbewerbechat`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`gruppe`,`person`),
  ADD KEY `gruppenwettbewerbechat` (`gruppe`),
  ADD KEY `personenwettbewerbechat` (`person`);

--
-- Indizes für die Tabelle `wettbewerbemitglieder`
--
ALTER TABLE `wettbewerbemitglieder`
  ADD UNIQUE KEY `id` (`gruppe`,`person`),
  ADD KEY `personenwettbewerbemitglieder` (`person`),
  ADD KEY `gruppenwettbewerbemitglieder` (`gruppe`);

--
-- Indizes für die Tabelle `wettbewerbenotifikationsabo`
--
ALTER TABLE `wettbewerbenotifikationsabo`
  ADD UNIQUE KEY `id` (`gruppe`,`person`),
  ADD KEY `personenwettbewerbenotifikationsabo` (`person`),
  ADD KEY `gruppenwettbewerbenotifikationsabo` (`gruppe`);

--
-- Indizes für die Tabelle `wettbewerbetermine`
--
ALTER TABLE `wettbewerbetermine`
  ADD UNIQUE KEY `id` (`gruppe`,`termin`),
  ADD KEY `terminewettbewerbetermine` (`termin`),
  ADD KEY `gruppenwettbewerbetermine` (`gruppe`);

--
-- Indizes für die Tabelle `wettbewerbetermineintern`
--
ALTER TABLE `wettbewerbetermineintern`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gruppewettbewerbetermineintern` (`gruppe`);

--
-- Indizes für die Tabelle `wettbewerbetermineinterndownloads`
--
ALTER TABLE `wettbewerbetermineinterndownloads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `wettbewerbetermineinterndownloadstermineintern` (`termin`);

--
-- Indizes für die Tabelle `wettbewerbevorsitz`
--
ALTER TABLE `wettbewerbevorsitz`
  ADD UNIQUE KEY `id` (`gruppe`,`person`),
  ADD KEY `personenwettbewerbevorsitz` (`person`),
  ADD KEY `gruppenwettbewerbevorsitz` (`gruppe`);

--
-- Indizes für die Tabelle `zeitraeume`
--
ALTER TABLE `zeitraeume`
  ADD PRIMARY KEY (`id`),
  ADD KEY `zeitraumschuljahr` (`schuljahr`);

--
-- Indizes für die Tabelle `zulaessigedateien`
--
ALTER TABLE `zulaessigedateien`
  ADD PRIMARY KEY (`id`);

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `arbeitsgemeinschaften`
--
ALTER TABLE `arbeitsgemeinschaften`
  ADD CONSTRAINT `schuljahrearbeitsgemeinschaften` FOREIGN KEY (`schuljahr`) REFERENCES `schuljahre` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `arbeitsgemeinschaftenaufsicht`
--
ALTER TABLE `arbeitsgemeinschaftenaufsicht`
  ADD CONSTRAINT `gruppenarbeitsgemeinschaftenaufsicht` FOREIGN KEY (`gruppe`) REFERENCES `arbeitsgemeinschaften` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `personenarbeitsgemeinschaftenaufsicht` FOREIGN KEY (`person`) REFERENCES `personen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `arbeitsgemeinschaftenblogeintraege`
--
ALTER TABLE `arbeitsgemeinschaftenblogeintraege`
  ADD CONSTRAINT `blogeintraegearbeitsgemeinschaftenblogeintraege` FOREIGN KEY (`blogeintrag`) REFERENCES `blogeintraege` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `gruppenarbeitsgemeinschaftenblogeintraege` FOREIGN KEY (`gruppe`) REFERENCES `arbeitsgemeinschaften` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `arbeitsgemeinschaftenblogeintraegeintern`
--
ALTER TABLE `arbeitsgemeinschaftenblogeintraegeintern`
  ADD CONSTRAINT `gruppearbeitsgemeinschaftenblogeintraegeintern` FOREIGN KEY (`gruppe`) REFERENCES `arbeitsgemeinschaften` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `arbeitsgemeinschaftenblogeintragbeschluesse`
--
ALTER TABLE `arbeitsgemeinschaftenblogeintragbeschluesse`
  ADD CONSTRAINT `blogeintragarbeitsgemeinschaftenblogeintragbeschluesse` FOREIGN KEY (`blogeintrag`) REFERENCES `arbeitsgemeinschaftenblogeintraegeintern` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `arbeitsgemeinschaftenblogeintragdownloads`
--
ALTER TABLE `arbeitsgemeinschaftenblogeintragdownloads`
  ADD CONSTRAINT `blogeintragarbeitsgemeinschaftenblogeintragdownloads` FOREIGN KEY (`blogeintrag`) REFERENCES `arbeitsgemeinschaftenblogeintraegeintern` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `arbeitsgemeinschaftenchat`
--
ALTER TABLE `arbeitsgemeinschaftenchat`
  ADD CONSTRAINT `gruppenarbeitsgemeinschaftenchat` FOREIGN KEY (`gruppe`) REFERENCES `arbeitsgemeinschaften` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `personenarbeitsgemeinschaftenchat` FOREIGN KEY (`person`) REFERENCES `personen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `arbeitsgemeinschaftenmitglieder`
--
ALTER TABLE `arbeitsgemeinschaftenmitglieder`
  ADD CONSTRAINT `gruppenarbeitsgemeinschaftenmitglieder` FOREIGN KEY (`gruppe`) REFERENCES `arbeitsgemeinschaften` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `personenarbeitsgemeinschaftenmitglieder` FOREIGN KEY (`person`) REFERENCES `personen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `arbeitsgemeinschaftennotifikationsabo`
--
ALTER TABLE `arbeitsgemeinschaftennotifikationsabo`
  ADD CONSTRAINT `gruppenarbeitsgemeinschaftennotifikationsabo` FOREIGN KEY (`gruppe`) REFERENCES `arbeitsgemeinschaften` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `personenarbeitsgemeinschaftennotifikationsabo` FOREIGN KEY (`person`) REFERENCES `personen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `arbeitsgemeinschaftentermine`
--
ALTER TABLE `arbeitsgemeinschaftentermine`
  ADD CONSTRAINT `gruppenarbeitsgemeinschaftentermine` FOREIGN KEY (`gruppe`) REFERENCES `arbeitsgemeinschaften` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `terminearbeitsgemeinschaftentermine` FOREIGN KEY (`termin`) REFERENCES `termine` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `arbeitsgemeinschaftentermineintern`
--
ALTER TABLE `arbeitsgemeinschaftentermineintern`
  ADD CONSTRAINT `gruppearbeitsgemeinschaftentermineintern` FOREIGN KEY (`gruppe`) REFERENCES `arbeitsgemeinschaften` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `arbeitsgemeinschaftentermineinterndownloads`
--
ALTER TABLE `arbeitsgemeinschaftentermineinterndownloads`
  ADD CONSTRAINT `arbeitsgemeinschaftentermineinterndownloadstermineintern` FOREIGN KEY (`termin`) REFERENCES `arbeitsgemeinschaftentermineintern` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `arbeitsgemeinschaftenvorsitz`
--
ALTER TABLE `arbeitsgemeinschaftenvorsitz`
  ADD CONSTRAINT `gruppenarbeitsgemeinschaftenvorsitz` FOREIGN KEY (`gruppe`) REFERENCES `arbeitsgemeinschaften` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `personenarbeitsgemeinschaftenvorsitz` FOREIGN KEY (`person`) REFERENCES `personen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `arbeitskreise`
--
ALTER TABLE `arbeitskreise`
  ADD CONSTRAINT `schuljahrearbeitskreise` FOREIGN KEY (`schuljahr`) REFERENCES `schuljahre` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `arbeitskreiseaufsicht`
--
ALTER TABLE `arbeitskreiseaufsicht`
  ADD CONSTRAINT `gruppenarbeitskreiseaufsicht` FOREIGN KEY (`gruppe`) REFERENCES `arbeitskreise` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `personenarbeitskreiseaufsicht` FOREIGN KEY (`person`) REFERENCES `personen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `arbeitskreiseblogeintraege`
--
ALTER TABLE `arbeitskreiseblogeintraege`
  ADD CONSTRAINT `blogeintraegearbeitskreiseblogeintraege` FOREIGN KEY (`blogeintrag`) REFERENCES `blogeintraege` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `gruppenarbeitskreiseblogeintraege` FOREIGN KEY (`gruppe`) REFERENCES `arbeitskreise` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `arbeitskreiseblogeintraegeintern`
--
ALTER TABLE `arbeitskreiseblogeintraegeintern`
  ADD CONSTRAINT `gruppearbeitskreiseblogeintraegeintern` FOREIGN KEY (`gruppe`) REFERENCES `arbeitskreise` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `arbeitskreiseblogeintragbeschluesse`
--
ALTER TABLE `arbeitskreiseblogeintragbeschluesse`
  ADD CONSTRAINT `blogeintragarbeitskreiseblogeintragbeschluesse` FOREIGN KEY (`blogeintrag`) REFERENCES `arbeitskreiseblogeintraegeintern` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `arbeitskreiseblogeintragdownloads`
--
ALTER TABLE `arbeitskreiseblogeintragdownloads`
  ADD CONSTRAINT `blogeintragarbeitskreiseblogeintragdownloads` FOREIGN KEY (`blogeintrag`) REFERENCES `arbeitskreiseblogeintraegeintern` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `arbeitskreisechat`
--
ALTER TABLE `arbeitskreisechat`
  ADD CONSTRAINT `gruppenarbeitskreisechat` FOREIGN KEY (`gruppe`) REFERENCES `arbeitskreise` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `personenarbeitskreisechat` FOREIGN KEY (`person`) REFERENCES `personen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `arbeitskreisemitglieder`
--
ALTER TABLE `arbeitskreisemitglieder`
  ADD CONSTRAINT `gruppenarbeitskreisemitglieder` FOREIGN KEY (`gruppe`) REFERENCES `arbeitskreise` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `personenarbeitskreisemitglieder` FOREIGN KEY (`person`) REFERENCES `personen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `arbeitskreisenotifikationsabo`
--
ALTER TABLE `arbeitskreisenotifikationsabo`
  ADD CONSTRAINT `gruppenarbeitskreisenotifikationsabo` FOREIGN KEY (`gruppe`) REFERENCES `arbeitskreise` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `personenarbeitskreisenotifikationsabo` FOREIGN KEY (`person`) REFERENCES `personen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `arbeitskreisetermine`
--
ALTER TABLE `arbeitskreisetermine`
  ADD CONSTRAINT `gruppenarbeitskreisetermine` FOREIGN KEY (`gruppe`) REFERENCES `arbeitskreise` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `terminearbeitskreisetermine` FOREIGN KEY (`termin`) REFERENCES `termine` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `arbeitskreisetermineintern`
--
ALTER TABLE `arbeitskreisetermineintern`
  ADD CONSTRAINT `gruppearbeitskreisetermineintern` FOREIGN KEY (`gruppe`) REFERENCES `arbeitskreise` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `arbeitskreisetermineinterndownloads`
--
ALTER TABLE `arbeitskreisetermineinterndownloads`
  ADD CONSTRAINT `arbeitskreisetermineinterndownloadstermineintern` FOREIGN KEY (`termin`) REFERENCES `arbeitskreisetermineintern` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `arbeitskreisevorsitz`
--
ALTER TABLE `arbeitskreisevorsitz`
  ADD CONSTRAINT `gruppenarbeitskreisevorsitz` FOREIGN KEY (`gruppe`) REFERENCES `arbeitskreise` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `personenarbeitskreisevorsitz` FOREIGN KEY (`person`) REFERENCES `personen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `besucherstatistik_blog`
--
ALTER TABLE `besucherstatistik_blog`
  ADD CONSTRAINT `blogbesucherstatistikblog` FOREIGN KEY (`id`) REFERENCES `blogeintraege` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `besucherstatistik_galerien`
--
ALTER TABLE `besucherstatistik_galerien`
  ADD CONSTRAINT `galeriebesucherstatistikgalerie` FOREIGN KEY (`id`) REFERENCES `galerien` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `besucherstatistik_termine`
--
ALTER TABLE `besucherstatistik_termine`
  ADD CONSTRAINT `terminebesucherstatistiktermine` FOREIGN KEY (`id`) REFERENCES `termine` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `besucherstatistik_website`
--
ALTER TABLE `besucherstatistik_website`
  ADD CONSTRAINT `seitenbesucherstatistikwebsite` FOREIGN KEY (`id`) REFERENCES `seiten` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `blogeintragdownloads`
--
ALTER TABLE `blogeintragdownloads`
  ADD CONSTRAINT `blogeintragdownloadsblogeintraege` FOREIGN KEY (`blogeintrag`) REFERENCES `blogeintraege` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `boxen`
--
ALTER TABLE `boxen`
  ADD CONSTRAINT `boxenboxenaussen` FOREIGN KEY (`boxaussen`) REFERENCES `boxenaussen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `boxenaussen`
--
ALTER TABLE `boxenaussen`
  ADD CONSTRAINT `boxaussenspalten` FOREIGN KEY (`spalte`) REFERENCES `spalten` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `downloads`
--
ALTER TABLE `downloads`
  ADD CONSTRAINT `downloadsspalten` FOREIGN KEY (`spalte`) REFERENCES `spalten` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `editoren`
--
ALTER TABLE `editoren`
  ADD CONSTRAINT `editorenspalten` FOREIGN KEY (`spalte`) REFERENCES `spalten` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `ereignisse`
--
ALTER TABLE `ereignisse`
  ADD CONSTRAINT `schuljahreereignisse` FOREIGN KEY (`schuljahr`) REFERENCES `schuljahre` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `ereignisseaufsicht`
--
ALTER TABLE `ereignisseaufsicht`
  ADD CONSTRAINT `gruppenereignisseaufsicht` FOREIGN KEY (`gruppe`) REFERENCES `ereignisse` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `personenereignisseaufsicht` FOREIGN KEY (`person`) REFERENCES `personen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `ereignisseblogeintraege`
--
ALTER TABLE `ereignisseblogeintraege`
  ADD CONSTRAINT `blogeintraegeereignisseblogeintraege` FOREIGN KEY (`blogeintrag`) REFERENCES `blogeintraege` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `gruppenereignisseblogeintraege` FOREIGN KEY (`gruppe`) REFERENCES `ereignisse` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `ereignisseblogeintraegeintern`
--
ALTER TABLE `ereignisseblogeintraegeintern`
  ADD CONSTRAINT `gruppeereignisseblogeintraegeintern` FOREIGN KEY (`gruppe`) REFERENCES `ereignisse` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `ereignisseblogeintragbeschluesse`
--
ALTER TABLE `ereignisseblogeintragbeschluesse`
  ADD CONSTRAINT `blogeintragereignisseblogeintragbeschluesse` FOREIGN KEY (`blogeintrag`) REFERENCES `ereignisseblogeintraegeintern` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `ereignisseblogeintragdownloads`
--
ALTER TABLE `ereignisseblogeintragdownloads`
  ADD CONSTRAINT `blogeintragereignisseblogeintragdownloads` FOREIGN KEY (`blogeintrag`) REFERENCES `ereignisseblogeintraegeintern` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `ereignissechat`
--
ALTER TABLE `ereignissechat`
  ADD CONSTRAINT `gruppenereignissechat` FOREIGN KEY (`gruppe`) REFERENCES `ereignisse` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `personenereignissechat` FOREIGN KEY (`person`) REFERENCES `personen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `ereignissemitglieder`
--
ALTER TABLE `ereignissemitglieder`
  ADD CONSTRAINT `gruppenereignissemitglieder` FOREIGN KEY (`gruppe`) REFERENCES `ereignisse` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `personenereignissemitglieder` FOREIGN KEY (`person`) REFERENCES `personen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `ereignissenotifikationsabo`
--
ALTER TABLE `ereignissenotifikationsabo`
  ADD CONSTRAINT `gruppenereignissenotifikationsabo` FOREIGN KEY (`gruppe`) REFERENCES `ereignisse` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `personenereignissenotifikationsabo` FOREIGN KEY (`person`) REFERENCES `personen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `ereignissetermine`
--
ALTER TABLE `ereignissetermine`
  ADD CONSTRAINT `gruppenereignissetermine` FOREIGN KEY (`gruppe`) REFERENCES `ereignisse` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `termineereignissetermine` FOREIGN KEY (`termin`) REFERENCES `termine` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `ereignissetermineintern`
--
ALTER TABLE `ereignissetermineintern`
  ADD CONSTRAINT `gruppeereignissetermineintern` FOREIGN KEY (`gruppe`) REFERENCES `ereignisse` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `ereignissetermineinterndownloads`
--
ALTER TABLE `ereignissetermineinterndownloads`
  ADD CONSTRAINT `ereignissetermineinterndownloadstermineintern` FOREIGN KEY (`termin`) REFERENCES `ereignissetermineintern` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `ereignissevorsitz`
--
ALTER TABLE `ereignissevorsitz`
  ADD CONSTRAINT `gruppenereignissevorsitz` FOREIGN KEY (`gruppe`) REFERENCES `ereignisse` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `personenereignissevorsitz` FOREIGN KEY (`person`) REFERENCES `personen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `eventuebersichten`
--
ALTER TABLE `eventuebersichten`
  ADD CONSTRAINT `eventuebersichtenspalten` FOREIGN KEY (`spalte`) REFERENCES `spalten` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `fachkollegen`
--
ALTER TABLE `fachkollegen`
  ADD CONSTRAINT `fachkolelgenpersonen` FOREIGN KEY (`kollege`) REFERENCES `personen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fachkollegenfaecher` FOREIGN KEY (`fach`) REFERENCES `faecher` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `fachschaften`
--
ALTER TABLE `fachschaften`
  ADD CONSTRAINT `schuljahrefachschaften` FOREIGN KEY (`schuljahr`) REFERENCES `schuljahre` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `fachschaftenaufsicht`
--
ALTER TABLE `fachschaftenaufsicht`
  ADD CONSTRAINT `gruppenfachschaftenaufsicht` FOREIGN KEY (`gruppe`) REFERENCES `fachschaften` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `personenfachschaftenaufsicht` FOREIGN KEY (`person`) REFERENCES `personen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `fachschaftenblogeintraege`
--
ALTER TABLE `fachschaftenblogeintraege`
  ADD CONSTRAINT `blogeintraegefachschaftenblogeintraege` FOREIGN KEY (`blogeintrag`) REFERENCES `blogeintraege` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `gruppenfachschaftenblogeintraege` FOREIGN KEY (`gruppe`) REFERENCES `fachschaften` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `fachschaftenblogeintraegeintern`
--
ALTER TABLE `fachschaftenblogeintraegeintern`
  ADD CONSTRAINT `gruppefachschaftenblogeintraegeintern` FOREIGN KEY (`gruppe`) REFERENCES `fachschaften` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `fachschaftenblogeintragbeschluesse`
--
ALTER TABLE `fachschaftenblogeintragbeschluesse`
  ADD CONSTRAINT `blogeintragfachschaftenblogeintragbeschluesse` FOREIGN KEY (`blogeintrag`) REFERENCES `fachschaftenblogeintraegeintern` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `fachschaftenblogeintragdownloads`
--
ALTER TABLE `fachschaftenblogeintragdownloads`
  ADD CONSTRAINT `blogeintragfachschaftenblogeintragdownloads` FOREIGN KEY (`blogeintrag`) REFERENCES `fachschaftenblogeintraegeintern` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `fachschaftenchat`
--
ALTER TABLE `fachschaftenchat`
  ADD CONSTRAINT `gruppenfachschaftenchat` FOREIGN KEY (`gruppe`) REFERENCES `fachschaften` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `personenfachschaftenchat` FOREIGN KEY (`person`) REFERENCES `personen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `fachschaftenmitglieder`
--
ALTER TABLE `fachschaftenmitglieder`
  ADD CONSTRAINT `gruppenfachschaftenmitglieder` FOREIGN KEY (`gruppe`) REFERENCES `fachschaften` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `personenfachschaftenmitglieder` FOREIGN KEY (`person`) REFERENCES `personen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `fachschaftennotifikationsabo`
--
ALTER TABLE `fachschaftennotifikationsabo`
  ADD CONSTRAINT `gruppenfachschaftennotifikationsabo` FOREIGN KEY (`gruppe`) REFERENCES `fachschaften` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `personenfachschaftennotifikationsabo` FOREIGN KEY (`person`) REFERENCES `personen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `fachschaftentermine`
--
ALTER TABLE `fachschaftentermine`
  ADD CONSTRAINT `gruppenfachschaftentermine` FOREIGN KEY (`gruppe`) REFERENCES `fachschaften` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `terminefachschaftentermine` FOREIGN KEY (`termin`) REFERENCES `termine` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `fachschaftentermineintern`
--
ALTER TABLE `fachschaftentermineintern`
  ADD CONSTRAINT `gruppefachschaftentermineintern` FOREIGN KEY (`gruppe`) REFERENCES `fachschaften` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `fachschaftentermineinterndownloads`
--
ALTER TABLE `fachschaftentermineinterndownloads`
  ADD CONSTRAINT `fachschaftentermineinterndownloadstermineintern` FOREIGN KEY (`termin`) REFERENCES `fachschaftentermineintern` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `fachschaftenvorsitz`
--
ALTER TABLE `fachschaftenvorsitz`
  ADD CONSTRAINT `gruppenfachschaftenvorsitz` FOREIGN KEY (`gruppe`) REFERENCES `fachschaften` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `personenfachschaftenvorsitz` FOREIGN KEY (`person`) REFERENCES `personen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `faecher`
--
ALTER TABLE `faecher`
  ADD CONSTRAINT `faecherschuljahre` FOREIGN KEY (`schuljahr`) REFERENCES `schuljahre` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `fahrten`
--
ALTER TABLE `fahrten`
  ADD CONSTRAINT `schuljahrefahrten` FOREIGN KEY (`schuljahr`) REFERENCES `schuljahre` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `fahrtenaufsicht`
--
ALTER TABLE `fahrtenaufsicht`
  ADD CONSTRAINT `gruppenfahrtenaufsicht` FOREIGN KEY (`gruppe`) REFERENCES `fahrten` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `personenfahrtenaufsicht` FOREIGN KEY (`person`) REFERENCES `personen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `fahrtenblogeintraege`
--
ALTER TABLE `fahrtenblogeintraege`
  ADD CONSTRAINT `blogeintraegefahrtenblogeintraege` FOREIGN KEY (`blogeintrag`) REFERENCES `blogeintraege` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `gruppenfahrtenblogeintraege` FOREIGN KEY (`gruppe`) REFERENCES `fahrten` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `fahrtenblogeintraegeintern`
--
ALTER TABLE `fahrtenblogeintraegeintern`
  ADD CONSTRAINT `gruppefahrtenblogeintraegeintern` FOREIGN KEY (`gruppe`) REFERENCES `fahrten` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `fahrtenblogeintragbeschluesse`
--
ALTER TABLE `fahrtenblogeintragbeschluesse`
  ADD CONSTRAINT `blogeintragfahrtenblogeintragbeschluesse` FOREIGN KEY (`blogeintrag`) REFERENCES `fahrtenblogeintraegeintern` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `fahrtenblogeintragdownloads`
--
ALTER TABLE `fahrtenblogeintragdownloads`
  ADD CONSTRAINT `blogeintragfahrtenblogeintragdownloads` FOREIGN KEY (`blogeintrag`) REFERENCES `fahrtenblogeintraegeintern` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `fahrtenchat`
--
ALTER TABLE `fahrtenchat`
  ADD CONSTRAINT `gruppenfahrtenchat` FOREIGN KEY (`gruppe`) REFERENCES `fahrten` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `personenfahrtenchat` FOREIGN KEY (`person`) REFERENCES `personen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `fahrtenmitglieder`
--
ALTER TABLE `fahrtenmitglieder`
  ADD CONSTRAINT `gruppenfahrtenmitglieder` FOREIGN KEY (`gruppe`) REFERENCES `fahrten` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `personenfahrtenmitglieder` FOREIGN KEY (`person`) REFERENCES `personen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `fahrtennotifikationsabo`
--
ALTER TABLE `fahrtennotifikationsabo`
  ADD CONSTRAINT `gruppenfahrtennotifikationsabo` FOREIGN KEY (`gruppe`) REFERENCES `fahrten` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `personenfahrtennotifikationsabo` FOREIGN KEY (`person`) REFERENCES `personen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `fahrtentermine`
--
ALTER TABLE `fahrtentermine`
  ADD CONSTRAINT `gruppenfahrtentermine` FOREIGN KEY (`gruppe`) REFERENCES `fahrten` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `terminefahrtentermine` FOREIGN KEY (`termin`) REFERENCES `termine` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `fahrtentermineintern`
--
ALTER TABLE `fahrtentermineintern`
  ADD CONSTRAINT `gruppefahrtentermineintern` FOREIGN KEY (`gruppe`) REFERENCES `fahrten` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `fahrtentermineinterndownloads`
--
ALTER TABLE `fahrtentermineinterndownloads`
  ADD CONSTRAINT `fahrtentermineinterndownloadstermineintern` FOREIGN KEY (`termin`) REFERENCES `fahrtentermineintern` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `fahrtenvorsitz`
--
ALTER TABLE `fahrtenvorsitz`
  ADD CONSTRAINT `gruppenfahrtenvorsitz` FOREIGN KEY (`gruppe`) REFERENCES `fahrten` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `personenfahrtenvorsitz` FOREIGN KEY (`person`) REFERENCES `personen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `gremien`
--
ALTER TABLE `gremien`
  ADD CONSTRAINT `schuljahregremien` FOREIGN KEY (`schuljahr`) REFERENCES `schuljahre` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `gremienaufsicht`
--
ALTER TABLE `gremienaufsicht`
  ADD CONSTRAINT `gruppengremienaufsicht` FOREIGN KEY (`gruppe`) REFERENCES `gremien` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `personengremienaufsicht` FOREIGN KEY (`person`) REFERENCES `personen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `gremienblogeintraege`
--
ALTER TABLE `gremienblogeintraege`
  ADD CONSTRAINT `blogeintraegegremienblogeintraege` FOREIGN KEY (`blogeintrag`) REFERENCES `blogeintraege` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `gruppengremienblogeintraege` FOREIGN KEY (`gruppe`) REFERENCES `gremien` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `gremienblogeintraegeintern`
--
ALTER TABLE `gremienblogeintraegeintern`
  ADD CONSTRAINT `gruppegremienblogeintraegeintern` FOREIGN KEY (`gruppe`) REFERENCES `gremien` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `gremienblogeintragbeschluesse`
--
ALTER TABLE `gremienblogeintragbeschluesse`
  ADD CONSTRAINT `blogeintraggremienblogeintragbeschluesse` FOREIGN KEY (`blogeintrag`) REFERENCES `gremienblogeintraegeintern` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `gremienblogeintragdownloads`
--
ALTER TABLE `gremienblogeintragdownloads`
  ADD CONSTRAINT `blogeintraggremienblogeintragdownloads` FOREIGN KEY (`blogeintrag`) REFERENCES `gremienblogeintraegeintern` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `gremienchat`
--
ALTER TABLE `gremienchat`
  ADD CONSTRAINT `gruppengremienchat` FOREIGN KEY (`gruppe`) REFERENCES `gremien` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `personengremienchat` FOREIGN KEY (`person`) REFERENCES `personen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `gremienmitglieder`
--
ALTER TABLE `gremienmitglieder`
  ADD CONSTRAINT `gruppengremienmitglieder` FOREIGN KEY (`gruppe`) REFERENCES `gremien` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `personengremienmitglieder` FOREIGN KEY (`person`) REFERENCES `personen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `gremiennotifikationsabo`
--
ALTER TABLE `gremiennotifikationsabo`
  ADD CONSTRAINT `gruppengremiennotifikationsabo` FOREIGN KEY (`gruppe`) REFERENCES `gremien` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `personengremiennotifikationsabo` FOREIGN KEY (`person`) REFERENCES `personen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `gremientermine`
--
ALTER TABLE `gremientermine`
  ADD CONSTRAINT `gruppengremientermine` FOREIGN KEY (`gruppe`) REFERENCES `gremien` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `terminegremientermine` FOREIGN KEY (`termin`) REFERENCES `termine` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `gremientermineintern`
--
ALTER TABLE `gremientermineintern`
  ADD CONSTRAINT `gruppegremientermineintern` FOREIGN KEY (`gruppe`) REFERENCES `gremien` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `gremientermineinterndownloads`
--
ALTER TABLE `gremientermineinterndownloads`
  ADD CONSTRAINT `gremientermineinterndownloadstermineintern` FOREIGN KEY (`termin`) REFERENCES `gremientermineintern` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `gremienvorsitz`
--
ALTER TABLE `gremienvorsitz`
  ADD CONSTRAINT `gruppengremienvorsitz` FOREIGN KEY (`gruppe`) REFERENCES `gremien` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `personengremienvorsitz` FOREIGN KEY (`person`) REFERENCES `personen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `identitaetsdiebstahl`
--
ALTER TABLE `identitaetsdiebstahl`
  ADD CONSTRAINT `personenidentitaetsdiebstahl` FOREIGN KEY (`id`) REFERENCES `personen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `klassen`
--
ALTER TABLE `klassen`
  ADD CONSTRAINT `schuljahreklassen` FOREIGN KEY (`schuljahr`) REFERENCES `schuljahre` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `stufenklassen` FOREIGN KEY (`stufe`) REFERENCES `stufen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `klassenaufsicht`
--
ALTER TABLE `klassenaufsicht`
  ADD CONSTRAINT `gruppenklassenaufsicht` FOREIGN KEY (`gruppe`) REFERENCES `klassen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `personenklassenaufsicht` FOREIGN KEY (`person`) REFERENCES `personen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `klassenblogeintraege`
--
ALTER TABLE `klassenblogeintraege`
  ADD CONSTRAINT `blogeintraegeklassenblogeintraege` FOREIGN KEY (`blogeintrag`) REFERENCES `blogeintraege` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `gruppenklassenblogeintraege` FOREIGN KEY (`gruppe`) REFERENCES `klassen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `klassenblogeintraegeintern`
--
ALTER TABLE `klassenblogeintraegeintern`
  ADD CONSTRAINT `gruppeklassenblogeintraegeintern` FOREIGN KEY (`gruppe`) REFERENCES `klassen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `klassenblogeintragbeschluesse`
--
ALTER TABLE `klassenblogeintragbeschluesse`
  ADD CONSTRAINT `blogeintragklassenblogeintragbeschluesse` FOREIGN KEY (`blogeintrag`) REFERENCES `klassenblogeintraegeintern` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `klassenblogeintragdownloads`
--
ALTER TABLE `klassenblogeintragdownloads`
  ADD CONSTRAINT `blogeintragklassenblogeintragdownloads` FOREIGN KEY (`blogeintrag`) REFERENCES `klassenblogeintraegeintern` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `klassenchat`
--
ALTER TABLE `klassenchat`
  ADD CONSTRAINT `gruppenklassenchat` FOREIGN KEY (`gruppe`) REFERENCES `klassen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `personenklassenchat` FOREIGN KEY (`person`) REFERENCES `personen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `klassenmitglieder`
--
ALTER TABLE `klassenmitglieder`
  ADD CONSTRAINT `gruppenklassenmitglieder` FOREIGN KEY (`gruppe`) REFERENCES `klassen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `personenklassenmitglieder` FOREIGN KEY (`person`) REFERENCES `personen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `klassennotifikationsabo`
--
ALTER TABLE `klassennotifikationsabo`
  ADD CONSTRAINT `gruppenklassennotifikationsabo` FOREIGN KEY (`gruppe`) REFERENCES `klassen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `personenklassennotifikationsabo` FOREIGN KEY (`person`) REFERENCES `personen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `klassentermine`
--
ALTER TABLE `klassentermine`
  ADD CONSTRAINT `gruppenklassentermine` FOREIGN KEY (`gruppe`) REFERENCES `klassen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `termineklassentermine` FOREIGN KEY (`termin`) REFERENCES `termine` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `klassentermineintern`
--
ALTER TABLE `klassentermineintern`
  ADD CONSTRAINT `gruppeklassentermineintern` FOREIGN KEY (`gruppe`) REFERENCES `klassen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `klassentermineinterndownloads`
--
ALTER TABLE `klassentermineinterndownloads`
  ADD CONSTRAINT `klassentermineinterndownloadstermineintern` FOREIGN KEY (`termin`) REFERENCES `klassentermineintern` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `klassenvorsitz`
--
ALTER TABLE `klassenvorsitz`
  ADD CONSTRAINT `gruppenklassenvorsitz` FOREIGN KEY (`gruppe`) REFERENCES `klassen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `personenklassenvorsitz` FOREIGN KEY (`person`) REFERENCES `personen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `kontaktformulare`
--
ALTER TABLE `kontaktformulare`
  ADD CONSTRAINT `kontaktformularespalten` FOREIGN KEY (`spalte`) REFERENCES `spalten` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `kontaktformulareempfaenger`
--
ALTER TABLE `kontaktformulareempfaenger`
  ADD CONSTRAINT `kontaktformulareempfaengerkontaktformulare` FOREIGN KEY (`kontaktformular`) REFERENCES `kontaktformulare` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `kurse`
--
ALTER TABLE `kurse`
  ADD CONSTRAINT `schuljahrekurse` FOREIGN KEY (`schuljahr`) REFERENCES `schuljahre` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `stufenkurse` FOREIGN KEY (`stufe`) REFERENCES `stufen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `kurseaufsicht`
--
ALTER TABLE `kurseaufsicht`
  ADD CONSTRAINT `gruppenkurseaufsicht` FOREIGN KEY (`gruppe`) REFERENCES `kurse` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `personenkurseaufsicht` FOREIGN KEY (`person`) REFERENCES `personen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `kurseblogeintraege`
--
ALTER TABLE `kurseblogeintraege`
  ADD CONSTRAINT `blogeintraegekurseblogeintraege` FOREIGN KEY (`blogeintrag`) REFERENCES `blogeintraege` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `gruppenkurseblogeintraege` FOREIGN KEY (`gruppe`) REFERENCES `kurse` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `kurseblogeintraegeintern`
--
ALTER TABLE `kurseblogeintraegeintern`
  ADD CONSTRAINT `gruppekurseblogeintraegeintern` FOREIGN KEY (`gruppe`) REFERENCES `kurse` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `kurseblogeintragbeschluesse`
--
ALTER TABLE `kurseblogeintragbeschluesse`
  ADD CONSTRAINT `blogeintragkurseblogeintragbeschluesse` FOREIGN KEY (`blogeintrag`) REFERENCES `kurseblogeintraegeintern` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `kurseblogeintragdownloads`
--
ALTER TABLE `kurseblogeintragdownloads`
  ADD CONSTRAINT `blogeintragkurseblogeintragdownloads` FOREIGN KEY (`blogeintrag`) REFERENCES `kurseblogeintraegeintern` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `kursechat`
--
ALTER TABLE `kursechat`
  ADD CONSTRAINT `gruppenkursechat` FOREIGN KEY (`gruppe`) REFERENCES `kurse` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `personenkursechat` FOREIGN KEY (`person`) REFERENCES `personen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `kurseklassen`
--
ALTER TABLE `kurseklassen`
  ADD CONSTRAINT `klassenkurseklassen` FOREIGN KEY (`klasse`) REFERENCES `klassen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `kursekurseklassen` FOREIGN KEY (`kurs`) REFERENCES `kurse` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `kursemitglieder`
--
ALTER TABLE `kursemitglieder`
  ADD CONSTRAINT `gruppenkursemitglieder` FOREIGN KEY (`gruppe`) REFERENCES `kurse` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `personenkursemitglieder` FOREIGN KEY (`person`) REFERENCES `personen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `kursenotifikationsabo`
--
ALTER TABLE `kursenotifikationsabo`
  ADD CONSTRAINT `gruppenkursenotifikationsabo` FOREIGN KEY (`gruppe`) REFERENCES `kurse` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `personenkursenotifikationsabo` FOREIGN KEY (`person`) REFERENCES `personen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `kursetermine`
--
ALTER TABLE `kursetermine`
  ADD CONSTRAINT `gruppenkursetermine` FOREIGN KEY (`gruppe`) REFERENCES `kurse` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `terminekursetermine` FOREIGN KEY (`termin`) REFERENCES `termine` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `kursetermineintern`
--
ALTER TABLE `kursetermineintern`
  ADD CONSTRAINT `gruppekursetermineintern` FOREIGN KEY (`gruppe`) REFERENCES `kurse` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `kursetermineinterndownloads`
--
ALTER TABLE `kursetermineinterndownloads`
  ADD CONSTRAINT `kursetermineinterndownloadstermineintern` FOREIGN KEY (`termin`) REFERENCES `kursetermineintern` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `kursevorsitz`
--
ALTER TABLE `kursevorsitz`
  ADD CONSTRAINT `gruppenkursevorsitz` FOREIGN KEY (`gruppe`) REFERENCES `kurse` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `personenkursevorsitz` FOREIGN KEY (`person`) REFERENCES `personen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `lehrer`
--
ALTER TABLE `lehrer`
  ADD CONSTRAINT `lehrerpersonen` FOREIGN KEY (`id`) REFERENCES `personen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `leihenblockieren`
--
ALTER TABLE `leihenblockieren`
  ADD CONSTRAINT `leihenblockierenleihen` FOREIGN KEY (`standort`) REFERENCES `leihen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `leihenbuchen`
--
ALTER TABLE `leihenbuchen`
  ADD CONSTRAINT `leihenbuchenleihen` FOREIGN KEY (`standort`) REFERENCES `leihen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `leihenbuchenpersonen` FOREIGN KEY (`person`) REFERENCES `personen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `leihengeraete`
--
ALTER TABLE `leihengeraete`
  ADD CONSTRAINT `leihengeraeteleihen` FOREIGN KEY (`standort`) REFERENCES `leihen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `navigationen`
--
ALTER TABLE `navigationen`
  ADD CONSTRAINT `spaltennavigationen` FOREIGN KEY (`spalte`) REFERENCES `spalten` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `notifikationen`
--
ALTER TABLE `notifikationen`
  ADD CONSTRAINT `notifikationenpersonen` FOREIGN KEY (`person`) REFERENCES `personen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `nutzerkonten`
--
ALTER TABLE `nutzerkonten`
  ADD CONSTRAINT `nutzerkontenpersonen` FOREIGN KEY (`id`) REFERENCES `personen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `personen_einstellungen`
--
ALTER TABLE `personen_einstellungen`
  ADD CONSTRAINT `personeneinstellungenpersonen` FOREIGN KEY (`person`) REFERENCES `personen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `personen_signaturen`
--
ALTER TABLE `personen_signaturen`
  ADD CONSTRAINT `postfachsignaturenpersonen` FOREIGN KEY (`person`) REFERENCES `personen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `pinnwandanschlag`
--
ALTER TABLE `pinnwandanschlag`
  ADD CONSTRAINT `pinnwandanschlagpinnwaende` FOREIGN KEY (`pinnwand`) REFERENCES `pinnwaende` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `profile`
--
ALTER TABLE `profile`
  ADD CONSTRAINT `profileschuljahre` FOREIGN KEY (`schuljahr`) REFERENCES `schuljahre` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `profilestufen` FOREIGN KEY (`stufe`) REFERENCES `stufen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `profilfaecher`
--
ALTER TABLE `profilfaecher`
  ADD CONSTRAINT `profilfaecherfach` FOREIGN KEY (`fach`) REFERENCES `faecher` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `profilfaecherprofil` FOREIGN KEY (`profil`) REFERENCES `profile` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `raeumeblockieren`
--
ALTER TABLE `raeumeblockieren`
  ADD CONSTRAINT `raeumeblockierenraeume` FOREIGN KEY (`standort`) REFERENCES `raeume` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `raeumebuchen`
--
ALTER TABLE `raeumebuchen`
  ADD CONSTRAINT `raeumebuchenperosnen` FOREIGN KEY (`person`) REFERENCES `personen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `raeumebuchenraeume` FOREIGN KEY (`standort`) REFERENCES `raeume` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `raeumegeraete`
--
ALTER TABLE `raeumegeraete`
  ADD CONSTRAINT `raeumegeraeteraeume` FOREIGN KEY (`standort`) REFERENCES `raeume` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `rechtzuordnung`
--
ALTER TABLE `rechtzuordnung`
  ADD CONSTRAINT `rechtzuordnungrecht` FOREIGN KEY (`recht`) REFERENCES `rechte` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `rechtzuornungpersonen` FOREIGN KEY (`person`) REFERENCES `personen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `rollenrechte`
--
ALTER TABLE `rollenrechte`
  ADD CONSTRAINT `rollenrechterechte` FOREIGN KEY (`recht`) REFERENCES `rechte` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `rollenrechterollen` FOREIGN KEY (`rolle`) REFERENCES `rollen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `rollenzuordnung`
--
ALTER TABLE `rollenzuordnung`
  ADD CONSTRAINT `rollenzuordnungpersonen` FOREIGN KEY (`person`) REFERENCES `personen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `rollenzuordnungrollen` FOREIGN KEY (`rolle`) REFERENCES `rollen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `schluesselposition`
--
ALTER TABLE `schluesselposition`
  ADD CONSTRAINT `schluesselpositionpersonen` FOREIGN KEY (`person`) REFERENCES `personen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `schluesselpositionschuljahr` FOREIGN KEY (`schuljahr`) REFERENCES `schuljahre` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `schuelereltern`
--
ALTER TABLE `schuelereltern`
  ADD CONSTRAINT `schuelerelterneltern` FOREIGN KEY (`eltern`) REFERENCES `personen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `schuelerelternschueler` FOREIGN KEY (`schueler`) REFERENCES `personen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `schulstunden`
--
ALTER TABLE `schulstunden`
  ADD CONSTRAINT `schulstundenzeitraeume` FOREIGN KEY (`zeitraum`) REFERENCES `zeitraeume` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `sonstigegruppen`
--
ALTER TABLE `sonstigegruppen`
  ADD CONSTRAINT `schuljahresonstigegruppen` FOREIGN KEY (`schuljahr`) REFERENCES `schuljahre` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `sonstigegruppenaufsicht`
--
ALTER TABLE `sonstigegruppenaufsicht`
  ADD CONSTRAINT `gruppensonstigegruppenaufsicht` FOREIGN KEY (`gruppe`) REFERENCES `sonstigegruppen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `personensonstigegruppenaufsicht` FOREIGN KEY (`person`) REFERENCES `personen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `sonstigegruppenblogeintraege`
--
ALTER TABLE `sonstigegruppenblogeintraege`
  ADD CONSTRAINT `blogeintraegesonstigegruppenblogeintraege` FOREIGN KEY (`blogeintrag`) REFERENCES `blogeintraege` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `gruppensonstigegruppenblogeintraege` FOREIGN KEY (`gruppe`) REFERENCES `sonstigegruppen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `sonstigegruppenblogeintraegeintern`
--
ALTER TABLE `sonstigegruppenblogeintraegeintern`
  ADD CONSTRAINT `gruppesonstigegruppenblogeintraegeintern` FOREIGN KEY (`gruppe`) REFERENCES `sonstigegruppen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `sonstigegruppenblogeintragbeschluesse`
--
ALTER TABLE `sonstigegruppenblogeintragbeschluesse`
  ADD CONSTRAINT `blogeintragsonstigegruppenblogeintragbeschluesse` FOREIGN KEY (`blogeintrag`) REFERENCES `sonstigegruppenblogeintraegeintern` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `sonstigegruppenblogeintragdownloads`
--
ALTER TABLE `sonstigegruppenblogeintragdownloads`
  ADD CONSTRAINT `blogeintragsonstigegruppenblogeintragdownloads` FOREIGN KEY (`blogeintrag`) REFERENCES `sonstigegruppenblogeintraegeintern` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `sonstigegruppenchat`
--
ALTER TABLE `sonstigegruppenchat`
  ADD CONSTRAINT `gruppensonstigegruppenchat` FOREIGN KEY (`gruppe`) REFERENCES `sonstigegruppen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `personensonstigegruppenchat` FOREIGN KEY (`person`) REFERENCES `personen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `sonstigegruppenmitglieder`
--
ALTER TABLE `sonstigegruppenmitglieder`
  ADD CONSTRAINT `gruppensonstigegruppenmitglieder` FOREIGN KEY (`gruppe`) REFERENCES `sonstigegruppen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `personensonstigegruppenmitglieder` FOREIGN KEY (`person`) REFERENCES `personen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `sonstigegruppennotifikationsabo`
--
ALTER TABLE `sonstigegruppennotifikationsabo`
  ADD CONSTRAINT `gruppensonstigegruppennotifikationsabo` FOREIGN KEY (`gruppe`) REFERENCES `sonstigegruppen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `personensonstigegruppennotifikationsabo` FOREIGN KEY (`person`) REFERENCES `personen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `sonstigegruppentermine`
--
ALTER TABLE `sonstigegruppentermine`
  ADD CONSTRAINT `gruppensonstigegruppentermine` FOREIGN KEY (`gruppe`) REFERENCES `sonstigegruppen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `terminesonstigegruppentermine` FOREIGN KEY (`termin`) REFERENCES `termine` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `sonstigegruppentermineintern`
--
ALTER TABLE `sonstigegruppentermineintern`
  ADD CONSTRAINT `gruppesonstigegruppentermineintern` FOREIGN KEY (`gruppe`) REFERENCES `sonstigegruppen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `sonstigegruppentermineinterndownloads`
--
ALTER TABLE `sonstigegruppentermineinterndownloads`
  ADD CONSTRAINT `sonstigegruppentermineinterndownloadstermineintern` FOREIGN KEY (`termin`) REFERENCES `sonstigegruppentermineintern` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `sonstigegruppenvorsitz`
--
ALTER TABLE `sonstigegruppenvorsitz`
  ADD CONSTRAINT `gruppensonstigegruppenvorsitz` FOREIGN KEY (`gruppe`) REFERENCES `sonstigegruppen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `personensonstigegruppenvorsitz` FOREIGN KEY (`person`) REFERENCES `personen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `spalten`
--
ALTER TABLE `spalten`
  ADD CONSTRAINT `spaltenseiten` FOREIGN KEY (`seite`) REFERENCES `seiten` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `statistikblog`
--
ALTER TABLE `statistikblog`
  ADD CONSTRAINT `blogstatistikblog` FOREIGN KEY (`id`) REFERENCES `blogeintraege` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `statistikgalerie`
--
ALTER TABLE `statistikgalerie`
  ADD CONSTRAINT `galeriestatistikgalerie` FOREIGN KEY (`id`) REFERENCES `galerien` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `statistiktermine`
--
ALTER TABLE `statistiktermine`
  ADD CONSTRAINT `terminestatistiktermine` FOREIGN KEY (`id`) REFERENCES `termine` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `statistikwebsite`
--
ALTER TABLE `statistikwebsite`
  ADD CONSTRAINT `seitenstatistikwebsite` FOREIGN KEY (`id`) REFERENCES `seiten` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `stufen`
--
ALTER TABLE `stufen`
  ADD CONSTRAINT `schuljahrestufen` FOREIGN KEY (`schuljahr`) REFERENCES `schuljahre` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `stufenaufsicht`
--
ALTER TABLE `stufenaufsicht`
  ADD CONSTRAINT `gruppenstufenaufsicht` FOREIGN KEY (`gruppe`) REFERENCES `stufen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `personenstufenaufsicht` FOREIGN KEY (`person`) REFERENCES `personen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `stufenblogeintraege`
--
ALTER TABLE `stufenblogeintraege`
  ADD CONSTRAINT `blogeintraegestufenblogeintraege` FOREIGN KEY (`blogeintrag`) REFERENCES `blogeintraege` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `gruppenstufenblogeintraege` FOREIGN KEY (`gruppe`) REFERENCES `stufen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `stufenblogeintraegeintern`
--
ALTER TABLE `stufenblogeintraegeintern`
  ADD CONSTRAINT `gruppestufenblogeintraegeintern` FOREIGN KEY (`gruppe`) REFERENCES `stufen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `stufenblogeintragbeschluesse`
--
ALTER TABLE `stufenblogeintragbeschluesse`
  ADD CONSTRAINT `blogeintragstufenblogeintragbeschluesse` FOREIGN KEY (`blogeintrag`) REFERENCES `stufenblogeintraegeintern` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `stufenblogeintragdownloads`
--
ALTER TABLE `stufenblogeintragdownloads`
  ADD CONSTRAINT `blogeintragstufenblogeintragdownloads` FOREIGN KEY (`blogeintrag`) REFERENCES `stufenblogeintraegeintern` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `stufenchat`
--
ALTER TABLE `stufenchat`
  ADD CONSTRAINT `gruppenstufenchat` FOREIGN KEY (`gruppe`) REFERENCES `stufen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `personenstufenchat` FOREIGN KEY (`person`) REFERENCES `personen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `stufenmitglieder`
--
ALTER TABLE `stufenmitglieder`
  ADD CONSTRAINT `gruppenstufenmitglieder` FOREIGN KEY (`gruppe`) REFERENCES `stufen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `personenstufenmitglieder` FOREIGN KEY (`person`) REFERENCES `personen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `stufennotifikationsabo`
--
ALTER TABLE `stufennotifikationsabo`
  ADD CONSTRAINT `gruppenstufennotifikationsabo` FOREIGN KEY (`gruppe`) REFERENCES `stufen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `personenstufennotifikationsabo` FOREIGN KEY (`person`) REFERENCES `personen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `stufentermine`
--
ALTER TABLE `stufentermine`
  ADD CONSTRAINT `gruppenstufentermine` FOREIGN KEY (`gruppe`) REFERENCES `stufen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `terminestufentermine` FOREIGN KEY (`termin`) REFERENCES `termine` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `stufentermineintern`
--
ALTER TABLE `stufentermineintern`
  ADD CONSTRAINT `gruppestufentermineintern` FOREIGN KEY (`gruppe`) REFERENCES `stufen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `stufentermineinterndownloads`
--
ALTER TABLE `stufentermineinterndownloads`
  ADD CONSTRAINT `stufentermineinterndownloadstermineintern` FOREIGN KEY (`termin`) REFERENCES `stufentermineintern` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `stufenvorsitz`
--
ALTER TABLE `stufenvorsitz`
  ADD CONSTRAINT `gruppenstufenvorsitz` FOREIGN KEY (`gruppe`) REFERENCES `stufen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `personenstufenvorsitz` FOREIGN KEY (`person`) REFERENCES `personen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `terminedownloads`
--
ALTER TABLE `terminedownloads`
  ADD CONSTRAINT `terminedownloadstermine` FOREIGN KEY (`termin`) REFERENCES `termine` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `voranmeldung_eltern`
--
ALTER TABLE `voranmeldung_eltern`
  ADD CONSTRAINT `voranmeldungschueleransprechpartner` FOREIGN KEY (`schueler`) REFERENCES `voranmeldung_schueler` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `wettbewerbe`
--
ALTER TABLE `wettbewerbe`
  ADD CONSTRAINT `schuljahrewettbewerbe` FOREIGN KEY (`schuljahr`) REFERENCES `schuljahre` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `wettbewerbeaufsicht`
--
ALTER TABLE `wettbewerbeaufsicht`
  ADD CONSTRAINT `gruppenwettbewerbeaufsicht` FOREIGN KEY (`gruppe`) REFERENCES `wettbewerbe` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `personenwettbewerbeaufsicht` FOREIGN KEY (`person`) REFERENCES `personen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `wettbewerbeblogeintraege`
--
ALTER TABLE `wettbewerbeblogeintraege`
  ADD CONSTRAINT `blogeintraegewettbewerbeblogeintraege` FOREIGN KEY (`blogeintrag`) REFERENCES `blogeintraege` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `gruppenwettbewerbeblogeintraege` FOREIGN KEY (`gruppe`) REFERENCES `wettbewerbe` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `wettbewerbeblogeintraegeintern`
--
ALTER TABLE `wettbewerbeblogeintraegeintern`
  ADD CONSTRAINT `gruppewettbewerbeblogeintraegeintern` FOREIGN KEY (`gruppe`) REFERENCES `wettbewerbe` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `wettbewerbeblogeintragbeschluesse`
--
ALTER TABLE `wettbewerbeblogeintragbeschluesse`
  ADD CONSTRAINT `blogeintragwettbewerbeblogeintragbeschluesse` FOREIGN KEY (`blogeintrag`) REFERENCES `wettbewerbeblogeintraegeintern` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `wettbewerbeblogeintragdownloads`
--
ALTER TABLE `wettbewerbeblogeintragdownloads`
  ADD CONSTRAINT `blogeintragwettbewerbeblogeintragdownloads` FOREIGN KEY (`blogeintrag`) REFERENCES `wettbewerbeblogeintraegeintern` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `wettbewerbechat`
--
ALTER TABLE `wettbewerbechat`
  ADD CONSTRAINT `gruppenwettbewerbechat` FOREIGN KEY (`gruppe`) REFERENCES `wettbewerbe` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `personenwettbewerbechat` FOREIGN KEY (`person`) REFERENCES `personen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `wettbewerbemitglieder`
--
ALTER TABLE `wettbewerbemitglieder`
  ADD CONSTRAINT `gruppenwettbewerbemitglieder` FOREIGN KEY (`gruppe`) REFERENCES `wettbewerbe` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `personenwettbewerbemitglieder` FOREIGN KEY (`person`) REFERENCES `personen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `wettbewerbenotifikationsabo`
--
ALTER TABLE `wettbewerbenotifikationsabo`
  ADD CONSTRAINT `gruppenwettbewerbenotifikationsabo` FOREIGN KEY (`gruppe`) REFERENCES `wettbewerbe` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `personenwettbewerbenotifikationsabo` FOREIGN KEY (`person`) REFERENCES `personen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `wettbewerbetermine`
--
ALTER TABLE `wettbewerbetermine`
  ADD CONSTRAINT `gruppenwettbewerbetermine` FOREIGN KEY (`gruppe`) REFERENCES `wettbewerbe` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `terminewettbewerbetermine` FOREIGN KEY (`termin`) REFERENCES `termine` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `wettbewerbetermineintern`
--
ALTER TABLE `wettbewerbetermineintern`
  ADD CONSTRAINT `gruppewettbewerbetermineintern` FOREIGN KEY (`gruppe`) REFERENCES `wettbewerbe` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `wettbewerbetermineinterndownloads`
--
ALTER TABLE `wettbewerbetermineinterndownloads`
  ADD CONSTRAINT `wettbewerbetermineinterndownloadstermineintern` FOREIGN KEY (`termin`) REFERENCES `wettbewerbetermineintern` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `wettbewerbevorsitz`
--
ALTER TABLE `wettbewerbevorsitz`
  ADD CONSTRAINT `gruppenwettbewerbevorsitz` FOREIGN KEY (`gruppe`) REFERENCES `wettbewerbe` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `personenwettbewerbevorsitz` FOREIGN KEY (`person`) REFERENCES `personen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `zeitraeume`
--
ALTER TABLE `zeitraeume`
  ADD CONSTRAINT `zeitraumschuljahr` FOREIGN KEY (`schuljahr`) REFERENCES `schuljahre` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
