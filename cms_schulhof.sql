-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Erstellungszeit: 02. Dez 2018 um 22:29
-- Server-Version: 10.1.36-MariaDB
-- PHP-Version: 7.2.10

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

--
-- Daten für Tabelle `allgemeineeinstellungen`
--

INSERT INTO `allgemeineeinstellungen` (`id`, `inhalt`, `wert`) VALUES
(0, 0x615d6989eec27342dacfbaa208d91710ee4cfa14f1035401b3a7d5f0228f23d5394f463d86b066be54ffc3a68fa2c172, 0xe9e36909e63e57fc361751d2d80cfdeb),
(1, 0x615d6989eec27342dacfbaa208d917100c84cb1dd6bb9fddf2e3e5d251a9914acb2d8cf95a01d10bfcd25788c3ac9ca5, 0xd33da3ac4df75f376fe4e52f7f44329f),
(2, 0x615d6989eec27342dacfbaa208d917103783973912b1c392e968645c9210473786f8fcbe5bdaa6899db2e39b0b708945, 0xccfe3e8df115b0b551be1c3e7da65e9c),
(3, 0x615d6989eec27342dacfbaa208d917108ac429ee2cfef73ff856d66862f6c529fc33929ad8feb7054c06bb7e73603c4a, 0xbce650bc2085903a0e823b50764a0918),
(4, 0x615d6989eec27342dacfbaa208d91710e68a4055c6c306e3c82867b8005476c0, 0x3f8517e77f8cdb44b7afb6d09dd9b4a7),
(5, 0x615d6989eec27342dacfbaa208d917104cf96e6462c3d1d7600cbef5cce9b567, 0x35dc2783a4036ce2b085a2293c303afaa22de57d125a71f29b78147458a4959a),
(6, 0x91c4c697bcfef26b7a50b49e0cb7f6d422cff7b602f522849f7d9b7a716d3ed2, 0xaa2d612c5f9a3bc943fa6868593da18f),
(7, 0x91c4c697bcfef26b7a50b49e0cb7f6d4d5369e5215c63d3fb6356916ad41c50afa466b86707a475897d42ec786c73895, 0xaa2d612c5f9a3bc943fa6868593da18f),
(8, 0x715fe2c3d65143eca39dd947ad2a2335be12516464b71fc17747ff2b35df26b6, 0xe9e36909e63e57fc361751d2d80cfdeb),
(9, 0xf5ad90a40c6b7c54021f7e0c3edb4047b387dd52a9043682153795bd4ffa907c, 0xe9e36909e63e57fc361751d2d80cfdeb),
(10, 0xf5ad90a40c6b7c54021f7e0c3edb40474a64329fbfd2600b46153b282c8207d03f8517e77f8cdb44b7afb6d09dd9b4a7, 0x4c831bc185465a7d1f8ebf514196431fc8a872e5597a782405670c0fcc81c6e2a73bc9721511511ac041235b74ed41e0ccb41483846fdf9b53bcd9fc2897ed18),
(11, 0xf5ad90a40c6b7c54021f7e0c3edb4047d6dafe6e2804a57a1ad2e7cd52cb5ee1746f39b8e408b3bdaece3d9f4c419cca, 0x4c831bc185465a7d1f8ebf514196431fc8a872e5597a782405670c0fcc81c6e2765588c986b6071f72f5bf4b9b668768d93282dca7bac7dbf55a9414378ac071),
(12, 0xf5ad90a40c6b7c54021f7e0c3edb4047a62ab2e18a9b316f21a281d4fa8d2594, 0x4c831bc185465a7d1f8ebf514196431f4ea83426d3caaebae77f8370d70153d9e177a475233d60db17252581df1acdffcc3a3cd20f4ffbc5edbaa7b5b97ce585),
(13, 0xf5ad90a40c6b7c54021f7e0c3edb4047abd7e156fd749f96a955195eb0a79eb6, 0x4c831bc185465a7d1f8ebf514196431f4ea83426d3caaebae77f8370d70153d9c87ed2811502946916cacc51a69b6ef9efa59a1f49e95a99a207c8428e0cd95d),
(14, 0xb870981fd1ad73eefa9a64c48900d6940d893d5e6c24db9feb2ee1a1781615ac, 0xe9e36909e63e57fc361751d2d80cfdeb),
(15, 0xe8748ffc7fb08a1760698d71c2776685bd51e5463ed451d2236cf7b56ccb3e19, 0xe9e36909e63e57fc361751d2d80cfdeb),
(16, 0x0a255ca1f7acd4f5a35b6e8e271a9baf0e8a2e8ba31380297f8fefa1912fa1c9, 0xe9e36909e63e57fc361751d2d80cfdeb),
(17, 0x8163fa7c0822431eaa19d6b1180c05f628a34bdfbbab83f60fd59219e6a5708a1b12aacb9e33884a01f527569429ec0e, 0xe9e36909e63e57fc361751d2d80cfdeb),
(18, 0x8163fa7c0822431eaa19d6b1180c05f65c187e8f13c8d44c4f110e097c5cb190f6c09761c486bfde7fb1387e5f61b0af, 0xe9e36909e63e57fc361751d2d80cfdeb),
(19, 0x8163fa7c0822431eaa19d6b1180c05f67310ec9936fac5bbea82528f0f4194b1cae889adfa4d7b898fa3ce51327cfb51, 0xe9e36909e63e57fc361751d2d80cfdeb),
(20, 0x8163fa7c0822431eaa19d6b1180c05f65cc561d58eebdd7f733e1308715444c0146b83e91e56ab682d190108dd185128, 0xe9e36909e63e57fc361751d2d80cfdeb),
(21, 0x8163fa7c0822431eaa19d6b1180c05f6f54dac3cb7172d9bae4025a649c46ea6d015577bca6d7d812f30b48fcf826f28a7d47f26641a26d5d4c9c64289f17fbc, 0xe9e36909e63e57fc361751d2d80cfdeb),
(22, 0x8163fa7c0822431eaa19d6b1180c05f6f54dac3cb7172d9bae4025a649c46ea6db27fba777d4e6bf0b73e2240111d991a7517b8e043e1b29ecb6f1ce40751979, 0xe9e36909e63e57fc361751d2d80cfdeb),
(23, 0xaab8ce6328dcd81fb9f565572601f7e2bc1ea1a4c1c6429e959cf727ae6d75b233e807c9b02be6af094b6abd15aa4a7d, 0xe9e36909e63e57fc361751d2d80cfdeb),
(24, 0xaab8ce6328dcd81fb9f565572601f7e276e0306455ce391c223822647549c708698bedad2ef98f7d2bd32a65e41a19623738f0bb525096a907b18a2c9d59b3c3, 0xe9e36909e63e57fc361751d2d80cfdeb),
(25, 0xaab8ce6328dcd81fb9f565572601f7e249abb0f1c6ede82b80c9efadef784b591e4bc66020c402bdcc2c3dcbac6ded65e4affe0817e55cfddd329d1d6cf58e20, 0xe9e36909e63e57fc361751d2d80cfdeb),
(26, 0xaab8ce6328dcd81fb9f565572601f7e2826b2174c2d891dd9cedfe9bd31a0404b29787326d115666055d06a75c558ddc, 0xe9e36909e63e57fc361751d2d80cfdeb),
(27, 0xaab8ce6328dcd81fb9f565572601f7e242c4e0fa0c2474cf3c37dbcd16bd7af86041d19cdca4bd8b377b7479100d53bb1b12aacb9e33884a01f527569429ec0e, 0xe9e36909e63e57fc361751d2d80cfdeb),
(28, 0xaab8ce6328dcd81fb9f565572601f7e242c4e0fa0c2474cf3c37dbcd16bd7af81abee1a1ff55806ece8469d45ffac89e146b83e91e56ab682d190108dd185128, 0xe9e36909e63e57fc361751d2d80cfdeb),
(29, 0xbc96372488cc8d003c6d38664506f187e4aa08cdbdfbf9ca8c793f1c636cfe80cae889adfa4d7b898fa3ce51327cfb51, 0xe9e36909e63e57fc361751d2d80cfdeb),
(30, 0xbc96372488cc8d003c6d38664506f187261366e5f173bae24c34a209a1f50730c468970ca1c73c5995a60559fadaa24a3f8517e77f8cdb44b7afb6d09dd9b4a7, 0xe9e36909e63e57fc361751d2d80cfdeb),
(31, 0xbc96372488cc8d003c6d38664506f18705d6de7ab371ed4300d254f97f6396a3b454f0122e1ebb6fa0508a4505aed53d, 0xe9e36909e63e57fc361751d2d80cfdeb),
(32, 0xbc96372488cc8d003c6d38664506f1874a1406b5ab9a09b291129d6d5a3c9f2f5afe7d2424a87f444caf5c5b33b4c625, 0xe9e36909e63e57fc361751d2d80cfdeb),
(33, 0xbc96372488cc8d003c6d38664506f1872b6232cddb668ef14bcde1a501ce6aefaa0e8c7188bbf1ca5a3a0cb6b5544526651eab53388a6051a31828719ffe02cd, 0xe9e36909e63e57fc361751d2d80cfdeb),
(34, 0xbc96372488cc8d003c6d38664506f1872b6232cddb668ef14bcde1a501ce6aefc24008a03d34237547f768fce5b9ecac49ae9588e2e16f9c9244d401a6d3c3fb, 0xe9e36909e63e57fc361751d2d80cfdeb),
(35, 0x1864f34f271c4bf0c53e7a9ea0f81c080b3c7103632ebd076b8a1de6782192481b12aacb9e33884a01f527569429ec0e, 0xe9e36909e63e57fc361751d2d80cfdeb),
(36, 0x1864f34f271c4bf0c53e7a9ea0f81c08488358771f110d8bd687845e6ecd5f8ef6c09761c486bfde7fb1387e5f61b0af, 0xe9e36909e63e57fc361751d2d80cfdeb),
(37, 0x1864f34f271c4bf0c53e7a9ea0f81c08058a4aa13e513e6ef58f25ef83d69156cae889adfa4d7b898fa3ce51327cfb51, 0xe9e36909e63e57fc361751d2d80cfdeb),
(38, 0x1864f34f271c4bf0c53e7a9ea0f81c08a636954a09aed1ba977658998b96d5e7146b83e91e56ab682d190108dd185128, 0xe9e36909e63e57fc361751d2d80cfdeb),
(39, 0x1864f34f271c4bf0c53e7a9ea0f81c0857e7680d938a721ce34405544ad855d7d015577bca6d7d812f30b48fcf826f28a7d47f26641a26d5d4c9c64289f17fbc, 0xe9e36909e63e57fc361751d2d80cfdeb),
(40, 0x1864f34f271c4bf0c53e7a9ea0f81c0857e7680d938a721ce34405544ad855d7db27fba777d4e6bf0b73e2240111d991a7517b8e043e1b29ecb6f1ce40751979, 0xe9e36909e63e57fc361751d2d80cfdeb);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `aufsichten`
--

CREATE TABLE `aufsichten` (
  `gruppe` varbinary(500) NOT NULL,
  `gruppenid` bigint(255) NOT NULL,
  `person` bigint(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `blogeintraege`
--

CREATE TABLE `blogeintraege` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `gruppe` varchar(2000) COLLATE utf8_unicode_ci NOT NULL,
  `gruppenid` bigint(255) UNSIGNED NOT NULL,
  `titelalt` varchar(2000) COLLATE utf8_unicode_ci NOT NULL,
  `titelaktuell` varchar(2000) COLLATE utf8_unicode_ci NOT NULL,
  `titelneu` varchar(2000) COLLATE utf8_unicode_ci NOT NULL,
  `inhaltalt` longtext COLLATE utf8_unicode_ci NOT NULL,
  `inhaltaktuell` longtext COLLATE utf8_unicode_ci NOT NULL,
  `inhaltneu` longtext COLLATE utf8_unicode_ci NOT NULL,
  `datumalt` bigint(255) UNSIGNED NOT NULL,
  `datumaktuell` bigint(255) UNSIGNED NOT NULL,
  `datumneu` bigint(255) UNSIGNED NOT NULL,
  `aktiv` varchar(1) COLLATE utf8_unicode_ci NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `blogeintraege`
--

INSERT INTO `blogeintraege` (`id`, `gruppe`, `gruppenid`, `titelalt`, `titelaktuell`, `titelneu`, `inhaltalt`, `inhaltaktuell`, `inhaltneu`, `datumalt`, `datumaktuell`, `datumneu`, `aktiv`, `idvon`, `idzeit`) VALUES
(0, 'Gremien', 0, 'kjhvklsfdn', 'kjhvklsfdn', 'kjhvklsfdn', '<p>...</p>', '<p>...</p>', '<p>...</p>', 1534888800, 1534888800, 1534888800, '1', NULL, NULL),
(1, 'Ereignisse', 1, 'tvrtwvwc', 'tvrtwvwc', 'tvrtwvwc', '<p><br></p>', '<p><br></p>', '<p><br></p>', 1535234400, 1535234400, 1535234400, '1', NULL, NULL);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `blogeintragdownloads`
--

CREATE TABLE `blogeintragdownloads` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `blogeintrag` bigint(255) UNSIGNED NOT NULL,
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

--
-- Daten für Tabelle `editoren`
--

INSERT INTO `editoren` (`id`, `spalte`, `position`, `aktiv`, `alt`, `aktuell`, `neu`, `idvon`, `idzeit`) VALUES
(0, 0, 1, '1', '<h1>Herzlich Willkommen am BG!</h1>', '<h1>Herzlich Willkommen am BG!</h1>', '<h1>Herzlich Willkommen am BG!</h1>', NULL, NULL);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ereignisse`
--

CREATE TABLE `ereignisse` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `bezeichnung` varbinary(2000) NOT NULL,
  `icon` varbinary(5000) NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `ereignisse`
--

INSERT INTO `ereignisse` (`id`, `bezeichnung`, `icon`, `idvon`, `idzeit`) VALUES
(0, 0x3802bda73c27c38eca9e78168f9c2cf6, 0x5966435c0fb69254f2dbccaf6a2cf825daeb4697dc0d9a7d34254c2b58923fc0, NULL, NULL),
(1, 0x123a209f0c5fb91b1b3aff4fe45042f2, 0xace991f565eabbac6f458d46bdb3d9dce0015f998e824ae259fffe53b443b3fc, NULL, NULL);

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

--
-- Daten für Tabelle `eventuebersichten`
--

INSERT INTO `eventuebersichten` (`id`, `spalte`, `position`, `aktiv`, `terminealt`, `termineaktuell`, `termineneu`, `termineanzahlalt`, `termineanzahlaktuell`, `termineanzahlneu`, `blogalt`, `blogaktuell`, `blogneu`, `bloganzahlalt`, `bloganzahlaktuell`, `bloganzahlneu`, `galeriealt`, `galerieaktuell`, `galerieneu`, `galerieanzahlalt`, `galerieanzahlaktuell`, `galerieanzahlneu`, `idvon`, `idzeit`) VALUES
(0, 0, 2, '1', '1', '1', '1', 10, 10, 10, '1', '1', '1', 5, 5, 5, '0', '0', '0', 5, 5, 5, NULL, NULL);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fachschaften`
--

CREATE TABLE `fachschaften` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `bezeichnung` varbinary(2000) NOT NULL,
  `icon` varbinary(2000) NOT NULL,
  `sichtbar` varbinary(50) NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `fachschaften`
--

INSERT INTO `fachschaften` (`id`, `bezeichnung`, `icon`, `sichtbar`, `idvon`, `idzeit`) VALUES
(0, 0x8161f593860f982ea2b47c73e16670c0, 0x72cac1a4e83fab270a2b7c82e5ee759dcddffe0eec1d987784c75dd61c272d16, 0xe9e36909e63e57fc361751d2d80cfdeb, NULL, NULL),
(1, 0x7c3cdf6284bfaf96ff9200d2c475f9e8, 0xe15d347fe5ff60ddb419651cd4fbe88c2809eaecdbb6db1c3f9156484d552f2a, 0xe9e36909e63e57fc361751d2d80cfdeb, NULL, NULL),
(2, 0x363385e81a6b2530b64fc72fe0bb91ae, 0x546c51b57cf10fe4200c896a6c11064f3ccf17b2624c2f892eec3f424d42933c, 0xe9e36909e63e57fc361751d2d80cfdeb, NULL, NULL),
(3, 0x6a8da4edd522e13f03c1beddd26c7a283eb979a9bfceb51f85c44776988d09e4, 0xf1c110354178bedd28e1ea59ac8943c90c6e8441363e89d17fc7ad7ddd49fb06, 0xe9e36909e63e57fc361751d2d80cfdeb, NULL, NULL),
(4, 0xc7d6e88b526da9a482f35c23670d4e54, 0x88b950b0a468035e2191c2ebd77213533f8517e77f8cdb44b7afb6d09dd9b4a7, 0xe9e36909e63e57fc361751d2d80cfdeb, NULL, NULL),
(5, 0x67b45720e44672dfb610541a6a3b31fa, 0xfe45280946cd0d643587413b2c5e6dfa, 0xe9e36909e63e57fc361751d2d80cfdeb, NULL, NULL),
(6, 0xfb1e3d48208d65aec064340a14d6d3ba, 0x6c8638c88e8a69fe7f9d44eb783244c4daeb4697dc0d9a7d34254c2b58923fc0, 0xe9e36909e63e57fc361751d2d80cfdeb, NULL, NULL),
(7, 0xfca29e2654ae40cef5390e9a9007fa2934941b6eabfe22ef4e70e2bad5f3bcf6, 0x00ca2e35720f0c8c5565c477981d5bc2f4e1015b4963c3f121ae9e7db1e6ca08, 0xe9e36909e63e57fc361751d2d80cfdeb, NULL, NULL),
(8, 0xd22c953b50a6afcd4db8d324f697a5b0, 0xe831aa0715de80fe2cddb6d294882498e4485127e117a6ad0545562f145a6d82, 0xe9e36909e63e57fc361751d2d80cfdeb, NULL, NULL),
(9, 0x3984df2985ceb2d5b42ed61a7a8fbf3d, 0xd63592e15d6ddcb852f8539b6e8ec8ff3ccf17b2624c2f892eec3f424d42933c, 0xe9e36909e63e57fc361751d2d80cfdeb, NULL, NULL),
(10, 0xc0b9eb414c72d62515983af5b2e7b188, 0x7707d9e54dfce1f2370b5a22aee3053475f02b6130c44c39d8663105decbacb0, 0xe9e36909e63e57fc361751d2d80cfdeb, NULL, NULL),
(11, 0x392c4271e2b12fa0d35e48f86485dc9f, 0x605e2bcde7a9b914aa10ec957cd15565746f39b8e408b3bdaece3d9f4c419cca, 0xe9e36909e63e57fc361751d2d80cfdeb, NULL, NULL),
(12, 0x4ec279429e1b5b4bc444cc3d89f3543b, 0xd8e996613e7b428eb6426137849429e4c7345e69648d03c2a9c526036f6b6331, 0xe9e36909e63e57fc361751d2d80cfdeb, NULL, NULL),
(13, 0x31b155a532e7213ff0f01e1ef608c83f, 0xe7208f086b0947cfd32e055b32e10c773ccf17b2624c2f892eec3f424d42933c, 0xe9e36909e63e57fc361751d2d80cfdeb, NULL, NULL),
(14, 0xc1c9e4321667288d47d3579836765afa, 0x8de865ec5bb0e88a152ca6baa8f29dd43ccf17b2624c2f892eec3f424d42933c, 0xe9e36909e63e57fc361751d2d80cfdeb, NULL, NULL),
(15, 0xf06fbdbc2bac8f7a791a6ce6edbab190, 0xf7f81154f4252fd3531988e79f8d9a023f8517e77f8cdb44b7afb6d09dd9b4a7, 0xe9e36909e63e57fc361751d2d80cfdeb, NULL, NULL),
(16, 0x59904003511139575520b5eea2ea830c, 0x90454cae592501e75f266abca5bf8d04746f39b8e408b3bdaece3d9f4c419cca, 0xe9e36909e63e57fc361751d2d80cfdeb, NULL, NULL),
(17, 0x1b75032713018e8b7f1340cb424b2a72, 0x1b82ade5897feeba1117031e9a7f068eeffdaeb071dd827ee089b673ab14e0b6, 0xe9e36909e63e57fc361751d2d80cfdeb, NULL, NULL),
(18, 0x88ebf4ca5e936a0b8a829dfa6ede3f00, 0x0bb0ffd98d577123920a93894c87fcdfbc5cea2140dcd40f6f32762a37c49b35, 0xe9e36909e63e57fc361751d2d80cfdeb, NULL, NULL),
(19, 0xa2cafd2bbfdc905dcdbf05e85e2aaadc3d09b01d0624a78c7f8ef3064e21cf91, 0x27281374606afe1449e06aa76e376034, 0xe9e36909e63e57fc361751d2d80cfdeb, NULL, NULL),
(20, 0x398cad99acd1fa37b80c6ea4b548b01b, 0x21efa44407c08077a84bb52430ee63273c3678b1b878b870875dbfe49332a7e4, 0xe9e36909e63e57fc361751d2d80cfdeb, NULL, NULL);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `faecher`
--

CREATE TABLE `faecher` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `bezeichnung` varbinary(5000) NOT NULL,
  `kuerzel` varbinary(200) NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `faecher`
--

INSERT INTO `faecher` (`id`, `bezeichnung`, `kuerzel`, `idvon`, `idzeit`) VALUES
(0, 0x67b45720e44672dfb610541a6a3b31fa, 0xd95feb43dd6152d7a021d246e51eca22, NULL, NULL),
(1, 0x8161f593860f982ea2b47c73e16670c0, 0x7b70edf7c97fed341e218078f97254ae, NULL, NULL);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ferien`
--

CREATE TABLE `ferien` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `bezeichnung` varbinary(2000) NOT NULL,
  `icon` varbinary(2000) NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `ferien`
--

INSERT INTO `ferien` (`id`, `bezeichnung`, `icon`, `idvon`, `idzeit`) VALUES
(0, 0xb279c605fce4d6d846f5b5847e6e841c, 0x009f5c0a2b7fe562b5631b94d81adc3dcc42efea8997617ebea939a627fc6b52, NULL, NULL),
(1, 0xe96860524947868c6ea7679bc2e59151acf27f73bc3f1683e134f6b09707797b, 0x43a6d5b18444137adad938f8bb64719d532022c4bb7516729edfe380fddacd57, NULL, NULL);

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
-- Tabellenstruktur für Tabelle `geraete`
--

CREATE TABLE `geraete` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `bezeichnung` varbinary(5000) NOT NULL,
  `standort` bigint(255) UNSIGNED NOT NULL,
  `art` varbinary(500) NOT NULL,
  `statusnr` int(1) NOT NULL,
  `meldung` longblob NOT NULL,
  `kommentar` longblob NOT NULL,
  `absender` bigint(255) UNSIGNED DEFAULT NULL,
  `zeit` bigint(255) UNSIGNED DEFAULT NULL,
  `ticket` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `geraete`
--

INSERT INTO `geraete` (`id`, `bezeichnung`, `standort`, `art`, `statusnr`, `meldung`, `kommentar`, `absender`, `zeit`, `ticket`, `idvon`, `idzeit`) VALUES
(0, 0xbda9ff6b8f4e499f7f1d3bbb82e89988, 0, 0x66b1a3a05e7d7d4f9dccda0a83c08f1b, 0, 0x3f8517e77f8cdb44b7afb6d09dd9b4a7, 0x3f8517e77f8cdb44b7afb6d09dd9b4a7, NULL, NULL, '', NULL, NULL),
(1, 0xbda9ff6b8f4e499f7f1d3bbb82e89988, 1, 0x66b1a3a05e7d7d4f9dccda0a83c08f1b, 0, 0x3f8517e77f8cdb44b7afb6d09dd9b4a7, 0x3f8517e77f8cdb44b7afb6d09dd9b4a7, NULL, NULL, '', NULL, NULL),
(2, 0x7bf9bc5a817f5b706dd18d14d08aaebc, 0, 0x66b1a3a05e7d7d4f9dccda0a83c08f1b, 0, 0x3f8517e77f8cdb44b7afb6d09dd9b4a7, 0x3f8517e77f8cdb44b7afb6d09dd9b4a7, NULL, NULL, '', NULL, NULL),
(3, 0x5effcc089da88da41b50469a376fbcc6, 0, 0x1bda4a7f3c647ec0c679a20ffc5219a6, 0, 0x3f8517e77f8cdb44b7afb6d09dd9b4a7, 0x3f8517e77f8cdb44b7afb6d09dd9b4a7, NULL, NULL, '', NULL, NULL),
(5, 0xb70acbc59c7c3e58847cd14262998ebf, 0, 0x1bda4a7f3c647ec0c679a20ffc5219a6, 0, 0x3f8517e77f8cdb44b7afb6d09dd9b4a7, 0x3f8517e77f8cdb44b7afb6d09dd9b4a7, NULL, NULL, '', NULL, NULL),
(6, 0xb2ba96b4a3aebb5bc3207a6d8d289e21, 0, 0x1bda4a7f3c647ec0c679a20ffc5219a6, 0, 0x3f8517e77f8cdb44b7afb6d09dd9b4a7, 0x3f8517e77f8cdb44b7afb6d09dd9b4a7, NULL, NULL, '', NULL, NULL);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `gremien`
--

CREATE TABLE `gremien` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `bezeichnung` varbinary(2000) NOT NULL,
  `icon` varbinary(2000) NOT NULL,
  `sichtbar` varbinary(50) NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `gremien`
--

INSERT INTO `gremien` (`id`, `bezeichnung`, `icon`, `sichtbar`, `idvon`, `idzeit`) VALUES
(0, 0x6d24ed9751c047320351f68a37a6ed30, 0xb38c5e860af3496cdb9e90fe25936db2, 0xaa2d612c5f9a3bc943fa6868593da18f, NULL, NULL),
(1, 0x7810673fddcdcd567eb3c86bae89c252a7355d74143691cff52da6ed2fb4ee08, 0xf6080e7a1c5e4d8977143b2260cb08313f8517e77f8cdb44b7afb6d09dd9b4a7, 0xaa2d612c5f9a3bc943fa6868593da18f, NULL, NULL),
(2, 0x610d2656593e6e05de089fd2e93f7d4a, 0xdf03d0061c15172a66726ebf6219f530daeb4697dc0d9a7d34254c2b58923fc0, 0xe9e36909e63e57fc361751d2d80cfdeb, NULL, NULL),
(3, 0xd0d8b5da6a7e538e68b22e95ed20fcc8, 0x957b4b61a9c9822e44600b786fc20d84746f39b8e408b3bdaece3d9f4c419cca, 0xe9e36909e63e57fc361751d2d80cfdeb, NULL, NULL),
(4, 0xada5f31c53627144c79979d5e526b900, 0xad3da84b1d3eacbad7e670f9fa40c5c7, 0xaa2d612c5f9a3bc943fa6868593da18f, NULL, NULL),
(5, 0xa5786526cc9c29bb5bc5d4e9d7127240, 0x53e30c4cf860a8b403e8151b5bc13cb0fe257fc0d14ebaf6643e02c87100f570, 0xe9e36909e63e57fc361751d2d80cfdeb, NULL, NULL);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `klassen`
--

CREATE TABLE `klassen` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `klassenstufe` bigint(255) UNSIGNED NOT NULL,
  `bezeichnung` varbinary(5000) NOT NULL,
  `stundenplan` varbinary(3000) NOT NULL,
  `idvon` bigint(255) DEFAULT NULL,
  `idzeit` bigint(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `klassen`
--

INSERT INTO `klassen` (`id`, `klassenstufe`, `bezeichnung`, `stundenplan`, `idvon`, `idzeit`) VALUES
(0, 1, 0x078c5d39377de204b7ca35f68eba8a2f, 0x4c831bc185465a7d1f8ebf514196431fdfd294582c7c0be80c7c545dec02fd7f5f4882c6c559c10dde27068f8df3eb8bf95facf75794a7eb4955ad0e7967664b, NULL, NULL);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `klassenelternvertreter`
--

CREATE TABLE `klassenelternvertreter` (
  `eltern` bigint(255) UNSIGNED NOT NULL,
  `klasse` bigint(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `klassenkurse`
--

CREATE TABLE `klassenkurse` (
  `klasse` bigint(255) UNSIGNED NOT NULL,
  `kurs` bigint(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `klassenleitung`
--

CREATE TABLE `klassenleitung` (
  `lehrer` bigint(255) UNSIGNED NOT NULL,
  `klasse` bigint(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `klassenleitung`
--

INSERT INTO `klassenleitung` (`lehrer`, `klasse`) VALUES
(0, 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `klassenschueler`
--

CREATE TABLE `klassenschueler` (
  `schueler` bigint(255) UNSIGNED NOT NULL,
  `klasse` bigint(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `klassenschueler`
--

INSERT INTO `klassenschueler` (`schueler`, `klasse`) VALUES
(8, 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `klassensprecher`
--

CREATE TABLE `klassensprecher` (
  `schueler` bigint(255) UNSIGNED NOT NULL,
  `klasse` bigint(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `klassenstellvertretung`
--

CREATE TABLE `klassenstellvertretung` (
  `lehrer` bigint(255) UNSIGNED NOT NULL,
  `klasse` bigint(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `klassenstufen`
--

CREATE TABLE `klassenstufen` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `schuljahr` bigint(255) UNSIGNED NOT NULL,
  `bezeichnung` varbinary(5000) NOT NULL,
  `reihenfolge` int(255) NOT NULL,
  `kurssystem` int(1) NOT NULL DEFAULT '0',
  `idvon` bigint(20) DEFAULT NULL,
  `idzeit` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `klassenstufen`
--

INSERT INTO `klassenstufen` (`id`, `schuljahr`, `bezeichnung`, `reihenfolge`, `kurssystem`, `idvon`, `idzeit`) VALUES
(0, 0, 0xcd267630596219ee586bb39b3969eb76, 1, 0, NULL, NULL),
(1, 0, 0xdb40d99cd18ba98f1b0bd9bad2020723, 2, 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `kurse`
--

CREATE TABLE `kurse` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `klassenstufe` bigint(255) UNSIGNED NOT NULL,
  `fach` bigint(255) UNSIGNED NOT NULL,
  `bezeichnung` varbinary(5000) NOT NULL,
  `idvon` bigint(20) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `kurse`
--

INSERT INTO `kurse` (`id`, `klassenstufe`, `fach`, `bezeichnung`, `idvon`, `idzeit`) VALUES
(0, 0, 1, 0xa365b5ac745241caf8032eafaa794446, NULL, NULL);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `kursklassen`
--

CREATE TABLE `kursklassen` (
  `kurs` bigint(255) UNSIGNED NOT NULL,
  `klasse` bigint(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `kurslehrer`
--

CREATE TABLE `kurslehrer` (
  `kurs` bigint(255) UNSIGNED NOT NULL,
  `lehrer` bigint(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `kurslehrer`
--

INSERT INTO `kurslehrer` (`kurs`, `lehrer`) VALUES
(0, 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `kursschueler`
--

CREATE TABLE `kursschueler` (
  `kurs` bigint(255) UNSIGNED NOT NULL,
  `schueler` bigint(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `kursschueler`
--

INSERT INTO `kursschueler` (`kurs`, `schueler`) VALUES
(0, 8);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `lehrer`
--

CREATE TABLE `lehrer` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `kuerzel` varbinary(100) NOT NULL,
  `stundenplan` varbinary(3000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `lehrer`
--

INSERT INTO `lehrer` (`id`, `kuerzel`, `stundenplan`) VALUES
(0, 0x4bf21e921ae20236353b908aa5097e62, 0x4c831bc185465a7d1f8ebf514196431f4ea83426d3caaebae77f8370d70153d9ddc324f690635766a82156bc1809331778023ea8c78412f7f644145a154452a3),
(1, 0x2af4467523648ec7930305a591d2698f, 0x4c831bc185465a7d1f8ebf514196431f4ea83426d3caaebae77f8370d70153d98992ce74a5308e0cf653278d7aa7934378023ea8c78412f7f644145a154452a3),
(3, 0x2cf3b15e7b5102da2faae070782b9e6b, 0x4c831bc185465a7d1f8ebf514196431f4ea83426d3caaebae77f8370d70153d9cb961af01483d9a2db2c025004b77c0278023ea8c78412f7f644145a154452a3);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `leihgeraete`
--

CREATE TABLE `leihgeraete` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `bezeichnung` varbinary(2000) NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `leihgeraete`
--

INSERT INTO `leihgeraete` (`id`, `bezeichnung`, `idvon`, `idzeit`) VALUES
(0, 0x2cfa9aff03df15a06e4c8fa9d0b8d4363f8517e77f8cdb44b7afb6d09dd9b4a7, NULL, NULL);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `leihgeraetverfuegbar`
--

CREATE TABLE `leihgeraetverfuegbar` (
  `schuljahr` bigint(20) UNSIGNED NOT NULL,
  `leihgeraet` bigint(20) UNSIGNED NOT NULL,
  `buchbar` varbinary(50) NOT NULL,
  `verfuegbar` varbinary(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `leihgeraetverfuegbar`
--

INSERT INTO `leihgeraetverfuegbar` (`schuljahr`, `leihgeraet`, `buchbar`, `verfuegbar`) VALUES
(0, 0, 0xe9e36909e63e57fc361751d2d80cfdeb, 0xe9e36909e63e57fc361751d2d80cfdeb),
(1, 0, 0xe9e36909e63e57fc361751d2d80cfdeb, 0xe9e36909e63e57fc361751d2d80cfdeb);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `mitgliedschaften`
--

CREATE TABLE `mitgliedschaften` (
  `gruppe` varbinary(257) NOT NULL,
  `gruppenid` bigint(255) UNSIGNED NOT NULL,
  `person` bigint(255) UNSIGNED NOT NULL,
  `vorsitz` varbinary(50) NOT NULL,
  `mv` varbinary(50) NOT NULL,
  `sch` varbinary(50) NOT NULL,
  `dho` varbinary(50) NOT NULL,
  `dru` varbinary(50) NOT NULL,
  `dum` varbinary(50) NOT NULL,
  `dlo` varbinary(50) NOT NULL,
  `oan` varbinary(50) NOT NULL,
  `oum` varbinary(50) NOT NULL,
  `olo` varbinary(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `mitgliedschaften`
--

INSERT INTO `mitgliedschaften` (`gruppe`, `gruppenid`, `person`, `vorsitz`, `mv`, `sch`, `dho`, `dru`, `dum`, `dlo`, `oan`, `oum`, `olo`) VALUES
(0x165bf5753a509fe5f302c2262ce69884, 2, 0, 0xe9e36909e63e57fc361751d2d80cfdeb, 0xe9e36909e63e57fc361751d2d80cfdeb, 0xe9e36909e63e57fc361751d2d80cfdeb, 0xe9e36909e63e57fc361751d2d80cfdeb, 0xe9e36909e63e57fc361751d2d80cfdeb, 0xe9e36909e63e57fc361751d2d80cfdeb, 0xe9e36909e63e57fc361751d2d80cfdeb, 0xe9e36909e63e57fc361751d2d80cfdeb, 0xe9e36909e63e57fc361751d2d80cfdeb, 0xe9e36909e63e57fc361751d2d80cfdeb),
(0x48cc251aeba24f5620049f115261bba4, 10, 1, 0xaa2d612c5f9a3bc943fa6868593da18f, 0xaa2d612c5f9a3bc943fa6868593da18f, 0xaa2d612c5f9a3bc943fa6868593da18f, 0xaa2d612c5f9a3bc943fa6868593da18f, 0xaa2d612c5f9a3bc943fa6868593da18f, 0xaa2d612c5f9a3bc943fa6868593da18f, 0xaa2d612c5f9a3bc943fa6868593da18f, 0xaa2d612c5f9a3bc943fa6868593da18f, 0xaa2d612c5f9a3bc943fa6868593da18f, 0xaa2d612c5f9a3bc943fa6868593da18f),
(0x48cc251aeba24f5620049f115261bba4, 10, 3, 0xaa2d612c5f9a3bc943fa6868593da18f, 0xaa2d612c5f9a3bc943fa6868593da18f, 0xaa2d612c5f9a3bc943fa6868593da18f, 0xaa2d612c5f9a3bc943fa6868593da18f, 0xaa2d612c5f9a3bc943fa6868593da18f, 0xaa2d612c5f9a3bc943fa6868593da18f, 0xaa2d612c5f9a3bc943fa6868593da18f, 0xaa2d612c5f9a3bc943fa6868593da18f, 0xaa2d612c5f9a3bc943fa6868593da18f, 0xaa2d612c5f9a3bc943fa6868593da18f),
(0x48cc251aeba24f5620049f115261bba4, 10, 6, 0xaa2d612c5f9a3bc943fa6868593da18f, 0xaa2d612c5f9a3bc943fa6868593da18f, 0xaa2d612c5f9a3bc943fa6868593da18f, 0xaa2d612c5f9a3bc943fa6868593da18f, 0xaa2d612c5f9a3bc943fa6868593da18f, 0xaa2d612c5f9a3bc943fa6868593da18f, 0xaa2d612c5f9a3bc943fa6868593da18f, 0xaa2d612c5f9a3bc943fa6868593da18f, 0xaa2d612c5f9a3bc943fa6868593da18f, 0xaa2d612c5f9a3bc943fa6868593da18f),
(0x48cc251aeba24f5620049f115261bba4, 19, 0, 0xe9e36909e63e57fc361751d2d80cfdeb, 0xe9e36909e63e57fc361751d2d80cfdeb, 0xe9e36909e63e57fc361751d2d80cfdeb, 0xe9e36909e63e57fc361751d2d80cfdeb, 0xe9e36909e63e57fc361751d2d80cfdeb, 0xe9e36909e63e57fc361751d2d80cfdeb, 0xe9e36909e63e57fc361751d2d80cfdeb, 0xe9e36909e63e57fc361751d2d80cfdeb, 0xe9e36909e63e57fc361751d2d80cfdeb, 0xe9e36909e63e57fc361751d2d80cfdeb);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `nachrichten_eingang`
--

CREATE TABLE `nachrichten_eingang` (
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

--
-- Daten für Tabelle `nachrichten_eingang`
--

INSERT INTO `nachrichten_eingang` (`id`, `absender`, `empfaenger`, `alle`, `zeit`, `betreff`, `nachricht`, `gelesen`, `papierkorb`, `papierkorbseit`, `idvon`, `idzeit`) VALUES
(0, 0, 1, '|1', 1535802103, 0xa365b5ac745241caf8032eafaa794446, 0x8e4f09ab1a495f8a89e8c8bdc3948371cdce09200c4ab43e95e1a54222c633adff75706b4f32e96c2675c1314cc739ea8e4f09ab1a495f8a89e8c8bdc3948371b09e34476e602877b735933a0611ce4ad88bea8a8770162721527dc034d96108c84e9ad1a7bbd9e5234a9a1a5c04eab7c4dcd42903284a29b4431fea8763bc8410abe7e5d6ea81799a5f61001073b9608d3e4b0de832c2c160ad80486490d9ce, 0xe9e36909e63e57fc361751d2d80cfdeb, 0xf87009c10405f1432a753b448d655356, 0, NULL, NULL),
(1, 0, 1, '|1', 1543785368, 0xa365b5ac745241caf8032eafaa794446, 0x3f6084d6726a6e12853619f41de8b6fd8e4f09ab1a495f8a89e8c8bdc3948371cdce09200c4ab43e95e1a54222c633adff75706b4f32e96c2675c1314cc739ea8e4f09ab1a495f8a89e8c8bdc394837172861a54b90c3708cd27967b4ff5764db36930caa55f8c6d6ae69c1d445c7c4df632e701b57bc9298e185dc388812b8cc3e9ee3f95acd997d1871572710818c780a7131c2cccaea61cfc93b267cf9c991372adc60ff56a62b43001d2ca64b57e, 0xf87009c10405f1432a753b448d655356, 0xf87009c10405f1432a753b448d655356, 0, NULL, NULL),
(2, 0, 1, '|1', 1543785643, 0xa365b5ac745241caf8032eafaa794446, 0xce347ffeb8d52f7f5f2ef4b62eefb626cdce09200c4ab43e95e1a54222c633adff75706b4f32e96c2675c1314cc739ea8e4f09ab1a495f8a89e8c8bdc3948371cdce09200c4ab43e95e1a54222c633ad93bda86f28437e9e156c0f606c7a2467e424f094782f36a4ffe42e00a11815cecdb9f856b61eaabf1fc0fa5d283c160711511f7e8addc9007f400f9b03bf81eb237e5362ac1382ebcf409bc08dcd9013acc14c166c3e5e87b677e0698b73cab3, 0xe9e36909e63e57fc361751d2d80cfdeb, 0xf87009c10405f1432a753b448d655356, 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `nachrichten_entwurf`
--

CREATE TABLE `nachrichten_entwurf` (
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

--
-- Daten für Tabelle `nachrichten_entwurf`
--

INSERT INTO `nachrichten_entwurf` (`id`, `absender`, `empfaenger`, `zeit`, `betreff`, `nachricht`, `papierkorb`, `papierkorbseit`, `idvon`, `idzeit`) VALUES
(6, 0, '', 1543778442, 0x3f8517e77f8cdb44b7afb6d09dd9b4a7, 0x8e4f09ab1a495f8a89e8c8bdc3948371cdce09200c4ab43e95e1a54222c633adff75706b4f32e96c2675c1314cc739ea8e4f09ab1a495f8a89e8c8bdc39483718798461c46bd1e8e486c6a7e0d2e85553f721dff7588356889afcaf1d610c888c7b2ef154563c7d0aecac14a881966b9f8339cd64a067cbd4f6d40db8a2496262875e5ddc7c141d650a456dd3a775d5280a8eeb19aebd520222938044e393dc5, 0xf87009c10405f1432a753b448d655356, 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `nachrichten_gesendet`
--

CREATE TABLE `nachrichten_gesendet` (
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

--
-- Daten für Tabelle `nachrichten_gesendet`
--

INSERT INTO `nachrichten_gesendet` (`id`, `absender`, `empfaenger`, `zeit`, `betreff`, `nachricht`, `papierkorb`, `papierkorbseit`, `idvon`, `idzeit`) VALUES
(0, 0, '|1', 1535802103, 0xa365b5ac745241caf8032eafaa794446, 0x8e4f09ab1a495f8a89e8c8bdc3948371cdce09200c4ab43e95e1a54222c633adff75706b4f32e96c2675c1314cc739ea8e4f09ab1a495f8a89e8c8bdc3948371b09e34476e602877b735933a0611ce4ad88bea8a8770162721527dc034d96108c84e9ad1a7bbd9e5234a9a1a5c04eab7c4dcd42903284a29b4431fea8763bc8410abe7e5d6ea81799a5f61001073b9608d3e4b0de832c2c160ad80486490d9ce, 0xf87009c10405f1432a753b448d655356, 0, NULL, NULL),
(1, 1, '|0|3', 1536504679, 0xa365b5ac745241caf8032eafaa794446, 0xa27f4791127a4d6085b6cf1055d1a8cc, 0xf87009c10405f1432a753b448d655356, 0, NULL, NULL),
(2, 0, '|1|3', 1536673556, 0x6a85ea1d7fa1312e7372f48cf9cff24f, 0x8e4f09ab1a495f8a89e8c8bdc3948371cdce09200c4ab43e95e1a54222c633adff75706b4f32e96c2675c1314cc739ea8e4f09ab1a495f8a89e8c8bdc3948371b09e34476e602877b735933a0611ce4ad88bea8a8770162721527dc034d96108c84e9ad1a7bbd9e5234a9a1a5c04eab7c4dcd42903284a29b4431fea8763bc8410abe7e5d6ea81799a5f61001073b9608d3e4b0de832c2c160ad80486490d9ce, 0xf87009c10405f1432a753b448d655356, 0, NULL, NULL),
(3, 0, '|1', 1543785368, 0xa365b5ac745241caf8032eafaa794446, 0x3f6084d6726a6e12853619f41de8b6fd8e4f09ab1a495f8a89e8c8bdc3948371cdce09200c4ab43e95e1a54222c633adff75706b4f32e96c2675c1314cc739ea8e4f09ab1a495f8a89e8c8bdc394837172861a54b90c3708cd27967b4ff5764db36930caa55f8c6d6ae69c1d445c7c4df632e701b57bc9298e185dc388812b8cc3e9ee3f95acd997d1871572710818c780a7131c2cccaea61cfc93b267cf9c991372adc60ff56a62b43001d2ca64b57e, 0xf87009c10405f1432a753b448d655356, 0, NULL, NULL),
(4, 0, '|1', 1543785643, 0xa365b5ac745241caf8032eafaa794446, 0xce347ffeb8d52f7f5f2ef4b62eefb626cdce09200c4ab43e95e1a54222c633adff75706b4f32e96c2675c1314cc739ea8e4f09ab1a495f8a89e8c8bdc3948371cdce09200c4ab43e95e1a54222c633ad93bda86f28437e9e156c0f606c7a2467e424f094782f36a4ffe42e00a11815cecdb9f856b61eaabf1fc0fa5d283c160711511f7e8addc9007f400f9b03bf81eb237e5362ac1382ebcf409bc08dcd9013acc14c166c3e5e87b677e0698b73cab3, 0xf87009c10405f1432a753b448d655356, 0, NULL, NULL);

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
  `spalte` bigint(255) DEFAULT NULL,
  `position` bigint(255) DEFAULT NULL,
  `anzeige` varchar(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `styles` text COLLATE utf8_unicode_ci NOT NULL,
  `klassen` text COLLATE utf8_unicode_ci NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `navigationen`
--

INSERT INTO `navigationen` (`id`, `art`, `ebene`, `ebenenzusatz`, `tiefe`, `spalte`, `position`, `anzeige`, `styles`, `klassen`, `idvon`, `idzeit`) VALUES
(0, 'h', 's', 0, 1, NULL, NULL, NULL, '', '', NULL, NULL),
(1, 's', 'e', 2, 4, NULL, NULL, NULL, '', '', NULL, NULL),
(2, 'f', 's', 1, 0, NULL, NULL, NULL, '', '', NULL, NULL);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `notifikationen`
--

CREATE TABLE `notifikationen` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `person` bigint(255) UNSIGNED NOT NULL,
  `gruppe` varbinary(2000) NOT NULL,
  `gruppenid` bigint(255) UNSIGNED NOT NULL,
  `objekt` varbinary(1500) NOT NULL,
  `inhalt` longblob NOT NULL,
  `zeit` bigint(255) UNSIGNED NOT NULL,
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
  `erstellt` bigint(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `nutzerkonten`
--

INSERT INTO `nutzerkonten` (`id`, `benutzername`, `passwort`, `passworttimeout`, `salt`, `sessionid`, `sessiontimeout`, `schuljahr`, `email`, `letzteanmeldung`, `vorletzteanmeldung`, `erstellt`) VALUES
(0, 0xd9d66eece458062bf33632e494e88a04, '2c43676a68729d117aa09e98775e817a62bd838b', 0, 0x1eb0cc3bff185ea3905e8e7077c281138b03186395e5e4a2517ed67c667c0fea, '', 0, 0, 0x2be861887090bb0520b3b253e1eec1600f1713cb179fb4871b5df9d266f603cd, 1543785758, 1543785687, 1518703354),
(1, 0x938b609d8f3e7d07425756d105f083c7, '1806c7d1256a8bbc5992770dd66ca74777e60c42', 0, 0x4dcc0ccfa5e32968b6c6cbf3bb42df11c3a8bcd619ea8ee354c7008df4f104b8, '', 0, 0, 0x88fdabe8cd138e482f1b027f4b714273, 1543785704, 1541328949, 1530340069),
(2, 0x2795e63b5c45ff4cb984e9e6a98538ff, '3722405bb0f6c9ea7e9edf3067f5ceea1a49c8e5', 0, 0xbc3d828910aac0feb1178731bae4c52b8249b35a4de384cbe1aac0ec8b7def06, '', 0, 0, 0x88fdabe8cd138e482f1b027f4b714273, 1543570444, 1536325458, 1536325442),
(8, 0xa52c05c8e0cefa1f9ed6fada3d8cc271, '1aaaae363b20f688a671e148768a366ee2de77ca', 0, 0x4908a09f73176ca1a272ce6505751c0703d4385546fe882f13fd5cf8c83f88f3, '', 0, 0, 0x88fdabe8cd138e482f1b027f4b714273, 1543167567, 1543159062, 1541291377);

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

--
-- Daten für Tabelle `personen`
--

INSERT INTO `personen` (`id`, `art`, `titel`, `nachname`, `vorname`, `geschlecht`, `idvon`, `idzeit`) VALUES
(0, 0x50d71153da3121682e3bdf787e2d93d3, 0x3f8517e77f8cdb44b7afb6d09dd9b4a7, 0x4b06360caf242eef70f313cc5d2bd2cf, 0x346e50e0f3d7514ab83502e30ebaebd1, 0xd33da3ac4df75f376fe4e52f7f44329f, NULL, NULL),
(1, 0x50d71153da3121682e3bdf787e2d93d3, 0x3f8517e77f8cdb44b7afb6d09dd9b4a7, 0xfe1743e46ce2da7524bd0dbdc9523f0f, 0x938b609d8f3e7d07425756d105f083c7, 0xd33da3ac4df75f376fe4e52f7f44329f, NULL, NULL),
(2, 0xdfe0dbd2851296fb4b4374898f5db22a, 0x3f8517e77f8cdb44b7afb6d09dd9b4a7, 0xa1312ce4941d1ccb9fd7fd96448bab3c, 0x147e8850bc0190205d31b5a539f3f5a5, 0x4071abd2bea41fcbcb4e7c63505a5362, NULL, NULL),
(3, 0x50d71153da3121682e3bdf787e2d93d3, 0x3f8517e77f8cdb44b7afb6d09dd9b4a7, 0x2e71431017676253a1a14b55146727b1, 0xf69d48b2c57f66c43ff0b29379d34861, 0xd33da3ac4df75f376fe4e52f7f44329f, NULL, NULL),
(4, 0xcbc058fc92524a9eadb57f7fbd7128a6, 0x3f8517e77f8cdb44b7afb6d09dd9b4a7, 0x268e121bce99b4b72bf7725b17c644b0, 0x666f7bd19b3657acf3ffbc6e48df8205, 0x4071abd2bea41fcbcb4e7c63505a5362, NULL, NULL),
(5, 0x86f8fcbe5bdaa6899db2e39b0b708945, 0x3f8517e77f8cdb44b7afb6d09dd9b4a7, 0x268e121bce99b4b72bf7725b17c644b0, 0x854270d60363a82899c0849f1040068c, 0xd33da3ac4df75f376fe4e52f7f44329f, NULL, NULL),
(7, 0xcbc058fc92524a9eadb57f7fbd7128a6, 0x3f8517e77f8cdb44b7afb6d09dd9b4a7, 0xf54d5c23592298d959f6e5620a3bb456, 0x236bf5c3ebd503ba53a07da169ee066f, 0xd33da3ac4df75f376fe4e52f7f44329f, NULL, NULL),
(8, 0xcbc058fc92524a9eadb57f7fbd7128a6, 0x3f8517e77f8cdb44b7afb6d09dd9b4a7, 0x7f7a1ba6957fd2d556bce36defdec44c, 0x201b4732fd3ad523b4b088d68ef291dc, 0x4071abd2bea41fcbcb4e7c63505a5362, NULL, NULL);

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
  `uebersichtsanzahl` varbinary(2000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `personen_einstellungen`
--

INSERT INTO `personen_einstellungen` (`person`, `notifikationsmail`, `postmail`, `postalletage`, `postpapierkorbtage`, `vertretungsmail`, `uebersichtsanzahl`) VALUES
(0, 0xe9e36909e63e57fc361751d2d80cfdeb, 0xaa2d612c5f9a3bc943fa6868593da18f, 0x7323483c4f0591e56f6b2061008cbae8, 0x23f114f993f180c3169409ea4b104311, 0xaa2d612c5f9a3bc943fa6868593da18f, 0xcd267630596219ee586bb39b3969eb76),
(1, 0xaa2d612c5f9a3bc943fa6868593da18f, 0xe9e36909e63e57fc361751d2d80cfdeb, 0x7323483c4f0591e56f6b2061008cbae8, 0x23f114f993f180c3169409ea4b104311, 0xe9e36909e63e57fc361751d2d80cfdeb, 0xe6c0352ec34a20ff240ca698861339fb),
(2, 0xaa2d612c5f9a3bc943fa6868593da18f, 0xe9e36909e63e57fc361751d2d80cfdeb, 0x7323483c4f0591e56f6b2061008cbae8, 0x3b180ce221e6215f735edc7ae9de3332, 0xe9e36909e63e57fc361751d2d80cfdeb, 0xcd267630596219ee586bb39b3969eb76),
(3, 0xaa2d612c5f9a3bc943fa6868593da18f, 0xe9e36909e63e57fc361751d2d80cfdeb, 0x7323483c4f0591e56f6b2061008cbae8, 0x3b180ce221e6215f735edc7ae9de3332, 0xe9e36909e63e57fc361751d2d80cfdeb, 0xcd267630596219ee586bb39b3969eb76),
(4, 0xaa2d612c5f9a3bc943fa6868593da18f, 0xe9e36909e63e57fc361751d2d80cfdeb, 0x7323483c4f0591e56f6b2061008cbae8, 0x3b180ce221e6215f735edc7ae9de3332, 0xe9e36909e63e57fc361751d2d80cfdeb, 0xcd267630596219ee586bb39b3969eb76),
(5, 0xaa2d612c5f9a3bc943fa6868593da18f, 0xe9e36909e63e57fc361751d2d80cfdeb, 0x7323483c4f0591e56f6b2061008cbae8, 0x3b180ce221e6215f735edc7ae9de3332, 0xe9e36909e63e57fc361751d2d80cfdeb, 0xcd267630596219ee586bb39b3969eb76),
(7, 0xaa2d612c5f9a3bc943fa6868593da18f, 0xe9e36909e63e57fc361751d2d80cfdeb, 0x7323483c4f0591e56f6b2061008cbae8, 0x3b180ce221e6215f735edc7ae9de3332, 0xe9e36909e63e57fc361751d2d80cfdeb, 0xcd267630596219ee586bb39b3969eb76),
(8, 0xaa2d612c5f9a3bc943fa6868593da18f, 0xe9e36909e63e57fc361751d2d80cfdeb, 0x7323483c4f0591e56f6b2061008cbae8, 0x3b180ce221e6215f735edc7ae9de3332, 0xe9e36909e63e57fc361751d2d80cfdeb, 0xcd267630596219ee586bb39b3969eb76);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `postfach_signaturen`
--

CREATE TABLE `postfach_signaturen` (
  `person` bigint(255) UNSIGNED NOT NULL,
  `signatur` blob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `postfach_signaturen`
--

INSERT INTO `postfach_signaturen` (`person`, `signatur`) VALUES
(0, 0x8e4f09ab1a495f8a89e8c8bdc3948371cdce09200c4ab43e95e1a54222c633adff75706b4f32e96c2675c1314cc739ea8e4f09ab1a495f8a89e8c8bdc3948371b09e34476e602877b735933a0611ce4ad88bea8a8770162721527dc034d96108c84e9ad1a7bbd9e5234a9a1a5c04eab7c4dcd42903284a29b4431fea8763bc8410abe7e5d6ea81799a5f61001073b9608d3e4b0de832c2c160ad80486490d9ce),
(1, 0x3f8517e77f8cdb44b7afb6d09dd9b4a7),
(2, 0x3f8517e77f8cdb44b7afb6d09dd9b4a7),
(3, 0x3f8517e77f8cdb44b7afb6d09dd9b4a7),
(4, 0x3f8517e77f8cdb44b7afb6d09dd9b4a7),
(5, 0x3f8517e77f8cdb44b7afb6d09dd9b4a7),
(7, 0x3f8517e77f8cdb44b7afb6d09dd9b4a7),
(8, 0x3f8517e77f8cdb44b7afb6d09dd9b4a7);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `postfach_tags`
--

CREATE TABLE `postfach_tags` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `person` bigint(255) UNSIGNED NOT NULL,
  `titel` varbinary(2000) NOT NULL,
  `farbe` int(2) NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `postfach_tags`
--

INSERT INTO `postfach_tags` (`id`, `person`, `titel`, `farbe`, `idvon`, `idzeit`) VALUES
(0, 0, 0x658cb0ea025888b8670a8bcee2f7070a4a5a265aedd7c5b506ca6d005dd15c78340953793427b3b880b7aca6294a8caf, 11, NULL, NULL),
(1, 0, 0x8f6d88f377d1123e0190c4f92608c61b, 5, NULL, NULL),
(2, 0, 0x8eb065e9d81e95aa5f5f414d3d9dac4a, 2, NULL, NULL),
(3, 0, 0x5655de05455e2992a76bebd2c424b8acdaeb4697dc0d9a7d34254c2b58923fc0, 43, NULL, NULL),
(4, 0, 0xed5b931d5ee45f5774dd79795d8a776b, 22, NULL, NULL),
(5, 0, 0x0ec473a38f4c836a22f20b7f70748272, 38, NULL, NULL),
(6, 0, 0x8161f593860f982ea2b47c73e16670c0, 25, NULL, NULL),
(7, 0, 0x67b45720e44672dfb610541a6a3b31fa, 24, NULL, NULL),
(8, 0, 0x8ca85abf698b3238a46ea398544fe3b8, 16, NULL, NULL),
(9, 0, 0x9578798ebd80fa14dd5273db85a587a8, 31, NULL, NULL),
(10, 0, 0xd91437a246917bf5137861d04304bdc1, 39, NULL, NULL);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `postfach_tagzuordnung`
--

CREATE TABLE `postfach_tagzuordnung` (
  `tag` bigint(255) UNSIGNED NOT NULL,
  `nachricht` bigint(255) UNSIGNED NOT NULL,
  `art` varbinary(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `raeume`
--

CREATE TABLE `raeume` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `bezeichnung` varbinary(2000) NOT NULL,
  `stundenplan` varbinary(3000) NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `raeume`
--

INSERT INTO `raeume` (`id`, `bezeichnung`, `stundenplan`, `idvon`, `idzeit`) VALUES
(0, 0x3552268cf0e2c3b3116a01220a3e0846, '', NULL, NULL),
(1, 0x81fcceba9c20cdf003fcfc462904b8b9, 0x4c831bc185465a7d1f8ebf514196431f3f89a67cb315f28c8deb133e135b0d5cb5ae9ffd8492b2d827a279d75998ae83f7c2df32848fafbb7707ec876a14c6f6, NULL, NULL);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `raumverfuegbar`
--

CREATE TABLE `raumverfuegbar` (
  `schuljahr` bigint(20) UNSIGNED NOT NULL,
  `raum` bigint(20) UNSIGNED NOT NULL,
  `buchbar` varbinary(50) NOT NULL,
  `verfuegbar` varbinary(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `raumverfuegbar`
--

INSERT INTO `raumverfuegbar` (`schuljahr`, `raum`, `buchbar`, `verfuegbar`) VALUES
(0, 1, 0xaa2d612c5f9a3bc943fa6868593da18f, 0xaa2d612c5f9a3bc943fa6868593da18f);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `rechte`
--

CREATE TABLE `rechte` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `kategorie` varbinary(2000) NOT NULL,
  `bezeichnung` varbinary(2000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `rechte`
--

INSERT INTO `rechte` (`id`, `kategorie`, `bezeichnung`) VALUES
(0, 0x4d1dfc54a7c88512764bcd5058eb244d, 0x3e0351da498fa436b34ca40cda11df023f8517e77f8cdb44b7afb6d09dd9b4a7),
(1, 0x4d1dfc54a7c88512764bcd5058eb244d, 0x36035201fde65712915d107fc8d42313ffdfa00a1b832b765f96290007b4b1fc),
(2, 0x4d1dfc54a7c88512764bcd5058eb244d, 0x5c734e051eafd6ea41d2eede77b91263e4affe0817e55cfddd329d1d6cf58e20),
(3, 0x4d1dfc54a7c88512764bcd5058eb244d, 0x4918d68265967d8e0d2e0133b9d95166b1512e105de10d0aaedfa2f545a6a32d),
(4, 0x4d1dfc54a7c88512764bcd5058eb244d, 0x7cbe41bbae1d0c2b017dca7db27a64125d7545df0de328cf985ea5c07d9cfcd0),
(5, 0x4d1dfc54a7c88512764bcd5058eb244d, 0x8c43218c09bad852dd546c9d1d6c169d05d9d4850aaa09ea5e996de1cd76ecec),
(6, 0x4d1dfc54a7c88512764bcd5058eb244d, 0xba267009ee289e6dafafeeaccc51e111bef9976fcbd2d112723dadee2270e761),
(7, 0x4d1dfc54a7c88512764bcd5058eb244d, 0xe787b4d92172f7861c681ed362decdb8227a7d65aa571164ab28a2acc06d3341848b62fe5f23d0eef4ab8697280b7286),
(8, 0x4d1dfc54a7c88512764bcd5058eb244d, 0xc1a1780745dd711d1ebceb52bcf241a2),
(9, 0x4d1dfc54a7c88512764bcd5058eb244d, 0xc3e286a0813d96a5423a9e89a542af11e4affe0817e55cfddd329d1d6cf58e20),
(10, 0x4d1dfc54a7c88512764bcd5058eb244d, 0xb9613fe3ac9b7cfb1f418e748f3caa1c),
(11, 0x4d1dfc54a7c88512764bcd5058eb244d, 0xea27330512cee284810fe7ae98dbb46327680319c1f7fff3d0cc745d5b1ce050),
(12, 0x4d1dfc54a7c88512764bcd5058eb244d, 0x64b0fbf0deb871415fc282562403ac522bc8045f163555e9545efeb329989a5d),
(13, 0x4d1dfc54a7c88512764bcd5058eb244d, 0x068282933a107bdcbe35ee56a5538d409a1a3356c5cb4d4491f7049b606c8616),
(14, 0x4d1dfc54a7c88512764bcd5058eb244d, 0x23a5645d08ebffad8472ca9a92c68ff0c3788a26bb77bb3d2a40a860cec4bc8a),
(15, 0xdb813176262ee7a05f2fefd8322ade93, 0x111b77baec8ea87412a1c099c2eb7d75),
(16, 0xdb813176262ee7a05f2fefd8322ade93, 0x8b0b0065ac67d1d56aebf5ccb6c55dc73738f0bb525096a907b18a2c9d59b3c3),
(17, 0xdb813176262ee7a05f2fefd8322ade93, 0xc05dbe3815d206b71b31cb9a950f1fb13f8517e77f8cdb44b7afb6d09dd9b4a7),
(18, 0xdb813176262ee7a05f2fefd8322ade93, 0x30fd0d3275b3aad83ebeacc4543fd3882bc8045f163555e9545efeb329989a5d),
(19, 0xdb813176262ee7a05f2fefd8322ade93, 0x9abc53bba2217d534749f7c7962b395c9a1a3356c5cb4d4491f7049b606c8616),
(20, 0xdb813176262ee7a05f2fefd8322ade93, 0x7d86da20ee175a33d802b4782135ef30c3788a26bb77bb3d2a40a860cec4bc8a),
(21, 0xdb813176262ee7a05f2fefd8322ade93, 0xe67470a6610a38f1126a240c496bdf0b),
(22, 0xdb813176262ee7a05f2fefd8322ade93, 0x67a4642bf85052bd04a495a1319ec0333738f0bb525096a907b18a2c9d59b3c3),
(23, 0xdb813176262ee7a05f2fefd8322ade93, 0x20809416fd6edffbbfd7568ba4f96ef53f8517e77f8cdb44b7afb6d09dd9b4a7),
(24, 0xdb813176262ee7a05f2fefd8322ade93, 0xba3757ed9becd1f3ac84bdae7ddea62100428dede1d6551ba776f1adc4d9b4e0),
(25, 0xdb813176262ee7a05f2fefd8322ade93, 0x1aa62ffd76284379a3feb8018ea2750a779fb017f6f09e025c037846f8cce3a9),
(26, 0xdb813176262ee7a05f2fefd8322ade93, 0x46bdc6cd3f59b13726ef21100cced59c7af10eeee6a525b7563ee6087cd5c9e2),
(27, 0xdb813176262ee7a05f2fefd8322ade93, 0x428ea8fc6b073a67b2bbd0f32d6b3bbb),
(28, 0xdb813176262ee7a05f2fefd8322ade93, 0xf8816e2c5d22522121e899067b1a1acb3738f0bb525096a907b18a2c9d59b3c3),
(29, 0xdb813176262ee7a05f2fefd8322ade93, 0xa563931d4989ac9ee91d46b60162ee863f8517e77f8cdb44b7afb6d09dd9b4a7),
(30, 0xdb813176262ee7a05f2fefd8322ade93, 0xaf78fb4dc3a5dbbcf02cd482978f951b3f8517e77f8cdb44b7afb6d09dd9b4a7),
(31, 0xdb813176262ee7a05f2fefd8322ade93, 0x636d773693555649a0bb2ebd66cefd7a),
(32, 0xdb813176262ee7a05f2fefd8322ade93, 0x2f6d2e62c7cdfb6759f6d5c546e7e7853f8517e77f8cdb44b7afb6d09dd9b4a7),
(33, 0xdb813176262ee7a05f2fefd8322ade93, 0xbd076ee894d5da801e7c025e863c8173),
(34, 0xdb813176262ee7a05f2fefd8322ade93, 0x322ecb28e323174d084aba209216d17d),
(35, 0xdb813176262ee7a05f2fefd8322ade93, 0xb123718f6559725d5c26b441b87afdbd),
(36, 0xdb813176262ee7a05f2fefd8322ade93, 0xebb63c2109ab7c02ab50238cca255424e4affe0817e55cfddd329d1d6cf58e20),
(37, 0xdb813176262ee7a05f2fefd8322ade93, 0x57e64f504563e3e78aecb677ffa65906),
(38, 0xdb813176262ee7a05f2fefd8322ade93, 0xa1b6acabf7351940cd060967fb91c290680cf21728b6a343df9447cc7a04a072),
(39, 0xdb813176262ee7a05f2fefd8322ade93, 0xf1feda17775d88406e25d3c762fad7635ca4484c163ba46659d3c7aa62758b9f),
(40, 0xdb813176262ee7a05f2fefd8322ade93, 0x01b7060d6db3d406acc0869a4a161a91996a83652a9f6f6c0075f2fecf5ec57a),
(41, 0xdb813176262ee7a05f2fefd8322ade93, 0x58d10d39bfd514388b64a59120e454443738f0bb525096a907b18a2c9d59b3c3),
(42, 0xdb813176262ee7a05f2fefd8322ade93, 0x56df57f9d06fac7747f945206bb5ba3c2e4034597d24ae0c04111bdb872c8894),
(43, 0xdb813176262ee7a05f2fefd8322ade93, 0xc4ca5a3af6612df32d0a08207ae3579f88adda22c18d14df498c5fa3ff2d9dfd),
(44, 0x392d4f3f8cbdccda2009f2973699cb29, 0x3e3ffaef7247e0d95580f96f4304efad0f827375ec0c760c2b20a715583f1c64),
(45, 0x392d4f3f8cbdccda2009f2973699cb29, 0x3e3ffaef7247e0d95580f96f4304efad1ae93dc645182a900852e93b0f1c7e403f8517e77f8cdb44b7afb6d09dd9b4a7),
(46, 0x392d4f3f8cbdccda2009f2973699cb29, 0x3e3ffaef7247e0d95580f96f4304efad12ab378c44a6da41ccbaebe2c13d4981),
(47, 0x392d4f3f8cbdccda2009f2973699cb29, 0x9df3eb5d5c913d220dbfcfdb5af298f6),
(48, 0x392d4f3f8cbdccda2009f2973699cb29, 0xbe6058ef55815a65c6c5896e4a517ae03f8517e77f8cdb44b7afb6d09dd9b4a7),
(49, 0x392d4f3f8cbdccda2009f2973699cb29, 0x5cce5175f71e6b7daf24b5740744e8f59efd3a9b026142b17c6ab42207b95fd9),
(50, 0x392d4f3f8cbdccda2009f2973699cb29, 0x74220da358ecec09ef66d6f3f8303bcf),
(51, 0x392d4f3f8cbdccda2009f2973699cb29, 0xba74b48b194540e507bc75a8b14fcb6c3738f0bb525096a907b18a2c9d59b3c3),
(52, 0x392d4f3f8cbdccda2009f2973699cb29, 0xe0e61320013a261edbb0fbac8354f5af3f8517e77f8cdb44b7afb6d09dd9b4a7),
(53, 0x392d4f3f8cbdccda2009f2973699cb29, 0x6bbd801b7f75a929441eaf027cfebe324163d29cfb5c9814fdf0bd9016c18825),
(54, 0x392d4f3f8cbdccda2009f2973699cb29, 0x05bf42672663603c32f1071244d5388bf5920c790e5c20a63765423127ebf49b),
(55, 0x392d4f3f8cbdccda2009f2973699cb29, 0xabcc28abb39244b158214e80cbef4d13f896f8e3e1dd4366230efc811c410bc1),
(56, 0x392d4f3f8cbdccda2009f2973699cb29, 0xb1cdd5336b8f4184a5c03389ba07b89cbeef1fe5894f674b5ab9d47b17b7dacd),
(57, 0x392d4f3f8cbdccda2009f2973699cb29, 0xb1cdd5336b8f4184a5c03389ba07b89ce821d5df45e162deb9351d09c46a9392),
(58, 0x392d4f3f8cbdccda2009f2973699cb29, 0xb1cdd5336b8f4184a5c03389ba07b89c4cc46bf28ad2ed9c24fb871ff7c9ee36),
(59, 0x392d4f3f8cbdccda2009f2973699cb29, 0xdd785b50d78309192a7c433416f5912900428dede1d6551ba776f1adc4d9b4e0),
(60, 0x392d4f3f8cbdccda2009f2973699cb29, 0x923b936c50c77bbf52a5958f19300b33779fb017f6f09e025c037846f8cce3a9),
(61, 0x392d4f3f8cbdccda2009f2973699cb29, 0xff0ec14a37e9b441d1d8fa4ab80178397af10eeee6a525b7563ee6087cd5c9e2),
(62, 0x392d4f3f8cbdccda2009f2973699cb29, 0x31e970b71f5eec98b7104aa6fc6a67cecacae9ff0339f8c066147039b51db525),
(63, 0x392d4f3f8cbdccda2009f2973699cb29, 0x31e970b71f5eec98b7104aa6fc6a67ce4e13a111a39cbc8ad1e3a0d1c52cbb4a),
(64, 0x392d4f3f8cbdccda2009f2973699cb29, 0x31e970b71f5eec98b7104aa6fc6a67ceafcf75f713b75957df973f53edbace29),
(65, 0x392d4f3f8cbdccda2009f2973699cb29, 0x4339f46ba39337c0735acdfa7f5f25e383062952be6b6894613e816742f91223),
(66, 0x392d4f3f8cbdccda2009f2973699cb29, 0x4339f46ba39337c0735acdfa7f5f25e3c3d245b76a53971f35b12123ebf20d68),
(67, 0x392d4f3f8cbdccda2009f2973699cb29, 0x4339f46ba39337c0735acdfa7f5f25e38281cd4f02e42752a2576ba71f23c7bc),
(68, 0x392d4f3f8cbdccda2009f2973699cb29, 0xc8c4a6abe4143603fbf3e0496638b2d74cc530e94c5059381ae22173fe7714e8),
(69, 0x392d4f3f8cbdccda2009f2973699cb29, 0xc8c4a6abe4143603fbf3e0496638b2d7a7d038d8747203afc9021564491dbd7d),
(70, 0x392d4f3f8cbdccda2009f2973699cb29, 0xc8c4a6abe4143603fbf3e0496638b2d79500833618cd9c7058c34f633d4def15),
(71, 0x392d4f3f8cbdccda2009f2973699cb29, 0xd6d74194b2afb4a150d0d679b313ba1ed172ac1eae0cd8b04c54b993355e1f77),
(72, 0x392d4f3f8cbdccda2009f2973699cb29, 0x8091f3055214fe83108f0e14ad67971a7aef19d5859c6b9367308255a04798d1680cf21728b6a343df9447cc7a04a072),
(73, 0x392d4f3f8cbdccda2009f2973699cb29, 0x655edb489d8f4170bb46914538766241ffb71ef41ba89cc34d8bd251f4ee6b6c),
(74, 0x85e4cec96cf09dc66d58b7a2c5b0b373, 0x6f68f2a1dda90c988c7be7e5804dea90e4affe0817e55cfddd329d1d6cf58e20),
(75, 0x85e4cec96cf09dc66d58b7a2c5b0b373, 0x11bd7050df6ad5ac078b8ef498d2d57c3738f0bb525096a907b18a2c9d59b3c3),
(76, 0x85e4cec96cf09dc66d58b7a2c5b0b373, 0xbc46f2a4bdfa68388bde55c8c2d604993f8517e77f8cdb44b7afb6d09dd9b4a7),
(77, 0x85e4cec96cf09dc66d58b7a2c5b0b373, 0x1cdcbb61f18f799db47f5b072e62555503d89c09ab526bd7e7c4887d791eb885),
(78, 0x85e4cec96cf09dc66d58b7a2c5b0b373, 0x1b938f1a2e0fb832329eeb61233248a4c645e0b3b53d395cce61284dea2bb3ac),
(79, 0x85e4cec96cf09dc66d58b7a2c5b0b373, 0xdacebb304d78f910d311544054f93202996a83652a9f6f6c0075f2fecf5ec57a),
(80, 0x85e4cec96cf09dc66d58b7a2c5b0b373, 0x562d0825a043004a9248dabcc31ae710),
(81, 0x85e4cec96cf09dc66d58b7a2c5b0b373, 0x11723d4932cf5cbd2abcc8ba553a84bde4affe0817e55cfddd329d1d6cf58e20),
(82, 0x85e4cec96cf09dc66d58b7a2c5b0b373, 0x6df29b496d3ead8a39f4ceab99f75f73),
(83, 0x85e4cec96cf09dc66d58b7a2c5b0b373, 0x01da1985655d666f85dc32ba3ee47e66),
(84, 0x85e4cec96cf09dc66d58b7a2c5b0b373, 0x9b1ce690e048ac71c69d29056f6df877e4affe0817e55cfddd329d1d6cf58e20),
(85, 0x85e4cec96cf09dc66d58b7a2c5b0b373, 0x106f46afa3a39d9a41f00d507fe2c0b8),
(86, 0x85e4cec96cf09dc66d58b7a2c5b0b373, 0xa9c97e549a315eed8eeb84ed2b4fca542bc8045f163555e9545efeb329989a5d),
(87, 0x85e4cec96cf09dc66d58b7a2c5b0b373, 0x94a99332df1a88c1ff2413a33cd528e33f8517e77f8cdb44b7afb6d09dd9b4a7),
(88, 0x85e4cec96cf09dc66d58b7a2c5b0b373, 0x05db57715bf883f1a94bb7fc7149ed1affdfa00a1b832b765f96290007b4b1fc),
(89, 0x85e4cec96cf09dc66d58b7a2c5b0b373, 0xe2722bb0744bb3397f769f310ae7fbcfe4affe0817e55cfddd329d1d6cf58e20),
(90, 0x85e4cec96cf09dc66d58b7a2c5b0b373, 0x718fcc0a828b27740425cca401434a6400428dede1d6551ba776f1adc4d9b4e0),
(91, 0x85e4cec96cf09dc66d58b7a2c5b0b373, 0x4ee0bdb923982da9a6683f51d81b2948779fb017f6f09e025c037846f8cce3a9),
(92, 0x85e4cec96cf09dc66d58b7a2c5b0b373, 0xd6d7349b9187cb93c269097b03a7a2aa7af10eeee6a525b7563ee6087cd5c9e2),
(93, 0x85e4cec96cf09dc66d58b7a2c5b0b373, 0x74220da358ecec09ef66d6f3f8303bcf),
(94, 0x85e4cec96cf09dc66d58b7a2c5b0b373, 0xba74b48b194540e507bc75a8b14fcb6c3738f0bb525096a907b18a2c9d59b3c3),
(95, 0x85e4cec96cf09dc66d58b7a2c5b0b373, 0xe0e61320013a261edbb0fbac8354f5af3f8517e77f8cdb44b7afb6d09dd9b4a7),
(96, 0x85e4cec96cf09dc66d58b7a2c5b0b373, 0xdab47a445ec8c137d0918b1db3a51863e4affe0817e55cfddd329d1d6cf58e20),
(97, 0x85e4cec96cf09dc66d58b7a2c5b0b373, 0xd9f33750b74e0e2a0c51e6a31f47f9f4),
(98, 0x85e4cec96cf09dc66d58b7a2c5b0b373, 0x23ccbf2f6d603c2b6d6d1be112dff5fd3738f0bb525096a907b18a2c9d59b3c3),
(99, 0x85e4cec96cf09dc66d58b7a2c5b0b373, 0x8c7387e2d0da459d80480cf42690b8c13f8517e77f8cdb44b7afb6d09dd9b4a7),
(100, 0x85e4cec96cf09dc66d58b7a2c5b0b373, 0xbe8d2758165e756daae396fa9cf09f927dd0099f689da247f4eed9a9e87ef230),
(101, 0x9ce149b140d860e2203b60c3e240535d, 0xbc1a3c53e9c5fe9dc4f8e9ed5895e550e4affe0817e55cfddd329d1d6cf58e20),
(102, 0xd6ec9394918d059173bbdb4e7a1d5dae, 0x73d7d905185cc1978038cbd37757783733ba4fe54db3ec255249eb58dd25d91c),
(103, 0xd6ec9394918d059173bbdb4e7a1d5dae, 0x5f1e45aba2a7d4afe299e1c0664aeb33),
(104, 0xd6ec9394918d059173bbdb4e7a1d5dae, 0xb024749733bee91638f13e613f91aed53bb82bd81317aae1d72963336a5843753738f0bb525096a907b18a2c9d59b3c3),
(105, 0xd6ec9394918d059173bbdb4e7a1d5dae, 0x33a680c5937855f62b7d047e494df61400b05ba41bbc8b477b91f46f7b85607c),
(106, 0xd6ec9394918d059173bbdb4e7a1d5dae, 0x58ff4d3aad8c2b847ddbae84fda9dfd76359b34c7810f20d509dbb1e551c1d1dffdfa00a1b832b765f96290007b4b1fc),
(107, 0xd6ec9394918d059173bbdb4e7a1d5dae, 0xf7053b8a0caa2c9c841fd398a77ca1adaef544bf3f061be08f5af5653c7a15833f8517e77f8cdb44b7afb6d09dd9b4a7);

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

--
-- Daten für Tabelle `rollen`
--

INSERT INTO `rollen` (`id`, `bezeichnung`, `personenart`, `idvon`, `idzeit`) VALUES
(0, 0x59c6cfece2d82672e81458dc920fb24c, 0x50d71153da3121682e3bdf787e2d93d3, NULL, NULL);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `rollenrechte`
--

CREATE TABLE `rollenrechte` (
  `rolle` bigint(255) UNSIGNED NOT NULL,
  `recht` bigint(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `rollenrechte`
--

INSERT INTO `rollenrechte` (`rolle`, `recht`) VALUES
(0, 0),
(0, 1),
(0, 2),
(0, 3),
(0, 4),
(0, 5),
(0, 6),
(0, 7),
(0, 8),
(0, 9),
(0, 10),
(0, 11),
(0, 12),
(0, 13),
(0, 14),
(0, 15),
(0, 16),
(0, 17),
(0, 18),
(0, 19),
(0, 20),
(0, 21),
(0, 22),
(0, 23),
(0, 24),
(0, 25),
(0, 26),
(0, 27),
(0, 28),
(0, 29),
(0, 30),
(0, 31),
(0, 32),
(0, 33),
(0, 34),
(0, 35),
(0, 36),
(0, 37),
(0, 38),
(0, 39),
(0, 40),
(0, 41),
(0, 42),
(0, 43),
(0, 44),
(0, 45),
(0, 46),
(0, 47),
(0, 48),
(0, 49),
(0, 50),
(0, 51),
(0, 52),
(0, 53),
(0, 54),
(0, 55),
(0, 56),
(0, 57),
(0, 58),
(0, 59),
(0, 60),
(0, 61),
(0, 62),
(0, 63),
(0, 64),
(0, 65),
(0, 66),
(0, 67),
(0, 68),
(0, 69),
(0, 70),
(0, 71),
(0, 72),
(0, 73),
(0, 74),
(0, 75),
(0, 76),
(0, 77),
(0, 78),
(0, 79),
(0, 80),
(0, 81),
(0, 82),
(0, 83),
(0, 84),
(0, 85),
(0, 86),
(0, 87),
(0, 88),
(0, 89),
(0, 90),
(0, 91),
(0, 92),
(0, 93),
(0, 94),
(0, 95),
(0, 96),
(0, 97),
(0, 98),
(0, 99),
(0, 100),
(0, 101),
(0, 102),
(0, 103),
(0, 104),
(0, 105),
(0, 106),
(0, 107);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `rollenzuordnung`
--

CREATE TABLE `rollenzuordnung` (
  `person` bigint(255) UNSIGNED NOT NULL,
  `rolle` bigint(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `rollenzuordnung`
--

INSERT INTO `rollenzuordnung` (`person`, `rolle`) VALUES
(0, 0);

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

--
-- Daten für Tabelle `schuelereltern`
--

INSERT INTO `schuelereltern` (`schueler`, `eltern`) VALUES
(4, 5);

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

--
-- Daten für Tabelle `schuljahre`
--

INSERT INTO `schuljahre` (`id`, `bezeichnung`, `beginn`, `ende`, `idvon`, `idzeit`) VALUES
(0, 0x822297c756f00c2e8635ecef108919db, 1535752800, 1567288799, NULL, NULL);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `schulstunden`
--

CREATE TABLE `schulstunden` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `zeitraum` bigint(255) UNSIGNED NOT NULL,
  `bezeichnung` varbinary(5000) NOT NULL,
  `beginnstd` varbinary(50) NOT NULL,
  `beginnmin` varbinary(50) NOT NULL,
  `endestd` varbinary(50) NOT NULL,
  `endemin` varbinary(50) NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `schulstunden`
--

INSERT INTO `schulstunden` (`id`, `zeitraum`, `bezeichnung`, `beginnstd`, `beginnmin`, `endestd`, `endemin`, `idvon`, `idzeit`) VALUES
(0, 0, 0xe9e36909e63e57fc361751d2d80cfdeb, 0x6d1ce1b6543900bf0424fb593a10e075, 0x725b82c30a625a97b60f0426be074f44, 0xae90f7e6917308ee90a03b12212077ba, 0x791b8a1a1ef53d90456ee004a9d2ccdd, NULL, NULL),
(1, 0, 0xe6c0352ec34a20ff240ca698861339fb, 0xae90f7e6917308ee90a03b12212077ba, 0x3b180ce221e6215f735edc7ae9de3332, 0xbbd323f81735fee767b38727bd700aa4, 0xa5c632f14bcc2f62742911353e9288f4, NULL, NULL),
(2, 0, 0x953c7a13085e721f47d318aa6a2161f6, 0xbbd323f81735fee767b38727bd700aa4, 0xa7c3d894e931d0e7b95f2a02b0057410, 0x23f114f993f180c3169409ea4b104311, 0x7791c5c715d198b3ce8e3fae8e8f2d85, NULL, NULL);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `seiten`
--

CREATE TABLE `seiten` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `art` varchar(1) COLLATE utf8_unicode_ci NOT NULL,
  `position` bigint(255) UNSIGNED NOT NULL,
  `zuordnung` text COLLATE utf8_unicode_ci NOT NULL,
  `bezeichnung` varchar(1000) COLLATE utf8_unicode_ci NOT NULL,
  `beschreibung` text COLLATE utf8_unicode_ci NOT NULL,
  `sidebar` varchar(1) COLLATE utf8_unicode_ci NOT NULL,
  `status` varchar(1) COLLATE utf8_unicode_ci NOT NULL,
  `styles` text COLLATE utf8_unicode_ci NOT NULL,
  `klassen` text COLLATE utf8_unicode_ci NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `seiten`
--

INSERT INTO `seiten` (`id`, `art`, `position`, `zuordnung`, `bezeichnung`, `beschreibung`, `sidebar`, `status`, `styles`, `klassen`, `idvon`, `idzeit`) VALUES
(0, 's', 1, '-', 'Startseite', '', '0', 's', '', '', NULL, NULL),
(1, 'm', 2, '-', 'Fußnavigation', '', '1', 'a', '', '', NULL, NULL),
(2, 't', 1, '0', 'Termine', '', '1', 'a', '', '', NULL, NULL),
(3, 'b', 2, '0', 'Blog', '', '1', 'a', '', '', NULL, NULL),
(4, 's', 3, '0', 'LALAAL', '', '1', 'a', '', '', NULL, NULL),
(5, 's', 1, '4', 'WOOPSIE', '', '1', 'a', '', '', NULL, NULL);

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

--
-- Daten für Tabelle `spalten`
--

INSERT INTO `spalten` (`id`, `seite`, `zeile`, `position`, `idvon`, `idzeit`) VALUES
(0, 0, 1, 1, NULL, NULL),
(1, 1, 1, 1, NULL, NULL),
(2, 2, 1, 1, NULL, NULL),
(3, 3, 1, 1, NULL, NULL),
(4, 4, 1, 1, NULL, NULL),
(5, 5, 1, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `stunden`
--

CREATE TABLE `stunden` (
  `lehrkraft` bigint(255) UNSIGNED NOT NULL,
  `raum` bigint(255) UNSIGNED NOT NULL,
  `kurs` bigint(255) UNSIGNED NOT NULL,
  `zeitraum` bigint(255) UNSIGNED NOT NULL,
  `tag` int(5) UNSIGNED NOT NULL,
  `stunde` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `tagebuch_0`
--

CREATE TABLE `tagebuch_0` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `lehrkraft` bigint(255) UNSIGNED DEFAULT NULL,
  `raum` bigint(255) UNSIGNED DEFAULT NULL,
  `kurs` bigint(255) UNSIGNED DEFAULT NULL,
  `zeitraum` bigint(255) UNSIGNED DEFAULT NULL,
  `tag` int(5) UNSIGNED DEFAULT NULL,
  `stunde` bigint(255) UNSIGNED DEFAULT NULL,
  `lehrkraftarchiv` varbinary(3000) NOT NULL,
  `raumarchiv` varbinary(3000) NOT NULL,
  `kursarchiv` varbinary(3000) NOT NULL,
  `beginn` bigint(255) UNSIGNED DEFAULT NULL,
  `ende` bigint(255) UNSIGNED DEFAULT NULL,
  `tbeginn` bigint(255) UNSIGNED DEFAULT NULL,
  `tende` bigint(255) UNSIGNED DEFAULT NULL,
  `tlehrkraft` bigint(255) UNSIGNED DEFAULT NULL,
  `traum` bigint(255) UNSIGNED DEFAULT NULL,
  `tstunde` bigint(255) UNSIGNED DEFAULT NULL,
  `entfall` int(1) UNSIGNED NOT NULL DEFAULT '0',
  `zusatzstunde` int(1) UNSIGNED NOT NULL DEFAULT '0',
  `vertretungsplan` int(1) UNSIGNED NOT NULL DEFAULT '0',
  `vertretungstext` varbinary(3000) NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `termine`
--

CREATE TABLE `termine` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `gruppe` varbinary(257) NOT NULL,
  `gruppenid` bigint(255) UNSIGNED NOT NULL,
  `bezeichnung` varbinary(5000) NOT NULL,
  `ort` varbinary(5000) NOT NULL,
  `beginn` bigint(255) UNSIGNED NOT NULL,
  `ende` bigint(255) UNSIGNED NOT NULL,
  `mehrtaegigt` varbinary(50) NOT NULL,
  `uhrzeitbt` varbinary(50) NOT NULL,
  `uhrzeitet` varbinary(50) NOT NULL,
  `ortt` varbinary(50) NOT NULL,
  `genehmigt` varbinary(50) NOT NULL,
  `oeffentlicht` varbinary(50) NOT NULL,
  `text` longblob NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `termine`
--

INSERT INTO `termine` (`id`, `gruppe`, `gruppenid`, `bezeichnung`, `ort`, `beginn`, `ende`, `mehrtaegigt`, `uhrzeitbt`, `uhrzeitet`, `ortt`, `genehmigt`, `oeffentlicht`, `text`, `idvon`, `idzeit`) VALUES
(0, 0xb279c605fce4d6d846f5b5847e6e841c, 0, 0xe368d796030416945ce234519a6b31dc, 0x3f8517e77f8cdb44b7afb6d09dd9b4a7, 1532556000, 1536530399, 0xe9e36909e63e57fc361751d2d80cfdeb, 0xaa2d612c5f9a3bc943fa6868593da18f, 0xaa2d612c5f9a3bc943fa6868593da18f, 0xaa2d612c5f9a3bc943fa6868593da18f, 0xe9e36909e63e57fc361751d2d80cfdeb, 0xe9e36909e63e57fc361751d2d80cfdeb, '', 0, 1530646385),
(1, 0xb279c605fce4d6d846f5b5847e6e841c, 0, 0xb397d1f2b3919bae406d7822b2ecd228, 0x3f8517e77f8cdb44b7afb6d09dd9b4a7, 1540591200, 1541372399, 0xe9e36909e63e57fc361751d2d80cfdeb, 0xaa2d612c5f9a3bc943fa6868593da18f, 0xaa2d612c5f9a3bc943fa6868593da18f, 0xaa2d612c5f9a3bc943fa6868593da18f, 0xe9e36909e63e57fc361751d2d80cfdeb, 0xe9e36909e63e57fc361751d2d80cfdeb, '', 0, 1530646437),
(2, 0x48cc251aeba24f5620049f115261bba4, 17, 0xdc8fa0413badae030b020237e84aec7cdaeb4697dc0d9a7d34254c2b58923fc0, 0x3f8517e77f8cdb44b7afb6d09dd9b4a7, 1536222600, 1536228059, 0xaa2d612c5f9a3bc943fa6868593da18f, 0xe9e36909e63e57fc361751d2d80cfdeb, 0xe9e36909e63e57fc361751d2d80cfdeb, 0xaa2d612c5f9a3bc943fa6868593da18f, 0xe9e36909e63e57fc361751d2d80cfdeb, 0xaa2d612c5f9a3bc943fa6868593da18f, '', 0, 1534886338),
(3, 0x48cc251aeba24f5620049f115261bba4, 18, 0xec12ab1a1a361b0baebd74daea5592b4, 0x3f8517e77f8cdb44b7afb6d09dd9b4a7, 1534888800, 1534975199, 0xaa2d612c5f9a3bc943fa6868593da18f, 0xaa2d612c5f9a3bc943fa6868593da18f, 0xaa2d612c5f9a3bc943fa6868593da18f, 0xaa2d612c5f9a3bc943fa6868593da18f, 0xe9e36909e63e57fc361751d2d80cfdeb, 0xaa2d612c5f9a3bc943fa6868593da18f, 0x50f3b6c3e21a5a11131cd8f5cf11c7fb, 0, 1534955271),
(4, 0xb279c605fce4d6d846f5b5847e6e841c, 0, 0xa365b5ac745241caf8032eafaa794446, 0x3f8517e77f8cdb44b7afb6d09dd9b4a7, 1567375200, 1567461599, 0xaa2d612c5f9a3bc943fa6868593da18f, 0xaa2d612c5f9a3bc943fa6868593da18f, 0xaa2d612c5f9a3bc943fa6868593da18f, 0xaa2d612c5f9a3bc943fa6868593da18f, 0xe9e36909e63e57fc361751d2d80cfdeb, 0xe9e36909e63e57fc361751d2d80cfdeb, 0xa06b35ede4195e58923e5b70136b18a6, 0, 1535881641),
(7, 0x165bf5753a509fe5f302c2262ce69884, 2, 0xf95e3adaf0be4d5f897cc7440464956c, 0x2f98b3a3f380ee7c293a286f36673052, 1531483200, 1531490459, 0xaa2d612c5f9a3bc943fa6868593da18f, 0xe9e36909e63e57fc361751d2d80cfdeb, 0xe9e36909e63e57fc361751d2d80cfdeb, 0xe9e36909e63e57fc361751d2d80cfdeb, 0xe9e36909e63e57fc361751d2d80cfdeb, 0xaa2d612c5f9a3bc943fa6868593da18f, '', 0, 1530781526),
(9, 0x165bf5753a509fe5f302c2262ce69884, 3, 0x6dc383031c670c12829699fb277135923f8517e77f8cdb44b7afb6d09dd9b4a7, 0x1ca390359dc02302be9ae087b8ea3bd0, 1539954000, 1539964859, 0xaa2d612c5f9a3bc943fa6868593da18f, 0xe9e36909e63e57fc361751d2d80cfdeb, 0xe9e36909e63e57fc361751d2d80cfdeb, 0xe9e36909e63e57fc361751d2d80cfdeb, 0xe9e36909e63e57fc361751d2d80cfdeb, 0xe9e36909e63e57fc361751d2d80cfdeb, '', 12, 1531038910),
(10, 0xc22fd6e1ef1512eaa5c5001c7604acba, 0, 0x15cbcb4e707ef1f777dc5e8227c8dea12edfd8e127829f0824cfa36697519b75, 0x3f8517e77f8cdb44b7afb6d09dd9b4a7, 1532296800, 1532383199, 0xaa2d612c5f9a3bc943fa6868593da18f, 0xaa2d612c5f9a3bc943fa6868593da18f, 0xaa2d612c5f9a3bc943fa6868593da18f, 0xaa2d612c5f9a3bc943fa6868593da18f, 0xe9e36909e63e57fc361751d2d80cfdeb, 0xe9e36909e63e57fc361751d2d80cfdeb, '', 29, 1531764338),
(12, 0x48cc251aeba24f5620049f115261bba4, 2, 0xfe9bdcc0a05119e73d37421b03818805be3bab608c16c6c2de344234a068a609, 0xb7a38631f8d726a5068dae62bbca3ef8, 1536147000, 1536152459, 0xaa2d612c5f9a3bc943fa6868593da18f, 0xe9e36909e63e57fc361751d2d80cfdeb, 0xe9e36909e63e57fc361751d2d80cfdeb, 0xe9e36909e63e57fc361751d2d80cfdeb, 0xe9e36909e63e57fc361751d2d80cfdeb, 0xaa2d612c5f9a3bc943fa6868593da18f, '', 3, 1532597148),
(13, 0x48cc251aeba24f5620049f115261bba4, 8, 0x8ec12df063ee7d7a83a2d1562c563d1f4654279908c5141a61e645b774d9a4c0, 0x3f8517e77f8cdb44b7afb6d09dd9b4a7, 1536217200, 1536222659, 0xaa2d612c5f9a3bc943fa6868593da18f, 0xe9e36909e63e57fc361751d2d80cfdeb, 0xe9e36909e63e57fc361751d2d80cfdeb, 0xaa2d612c5f9a3bc943fa6868593da18f, 0xe9e36909e63e57fc361751d2d80cfdeb, 0xaa2d612c5f9a3bc943fa6868593da18f, '', 12, 1532597199),
(15, 0xc22fd6e1ef1512eaa5c5001c7604acba, 1, 0x452942b849ccbf8bc2bb9292c97a521baec795e49cb01da7655cc95f5a10fbb7, 0x13b5dc4b6fc6d40bc3489bf8a919efb4a29d5ab86bc83c697c3d8f0098f92df3, 1536582600, 1536616799, 0xaa2d612c5f9a3bc943fa6868593da18f, 0xe9e36909e63e57fc361751d2d80cfdeb, 0xaa2d612c5f9a3bc943fa6868593da18f, 0xe9e36909e63e57fc361751d2d80cfdeb, 0xe9e36909e63e57fc361751d2d80cfdeb, 0xe9e36909e63e57fc361751d2d80cfdeb, '', 0, 1532758769),
(16, 0x48cc251aeba24f5620049f115261bba4, 19, 0xdc8fa0413badae030b020237e84aec7c20ef5b4b3d4b5338287f5eb74a488c3c, 0x3f8517e77f8cdb44b7afb6d09dd9b4a7, 1536217200, 1536222659, 0xaa2d612c5f9a3bc943fa6868593da18f, 0xe9e36909e63e57fc361751d2d80cfdeb, 0xe9e36909e63e57fc361751d2d80cfdeb, 0xaa2d612c5f9a3bc943fa6868593da18f, 0xe9e36909e63e57fc361751d2d80cfdeb, 0xaa2d612c5f9a3bc943fa6868593da18f, 0x8e7ffbfdeb94eb874e955d32b9b6682f3d0b81c4a62cddf8f1363e7bc0af4957a1ea40200c140a6f2c63c4760d016783, 0, 1533154978),
(17, 0x165bf5753a509fe5f302c2262ce69884, 2, 0xf95e3adaf0be4d5f897cc7440464956c, 0x3f8517e77f8cdb44b7afb6d09dd9b4a7, 1536130800, 1536141659, 0xaa2d612c5f9a3bc943fa6868593da18f, 0xe9e36909e63e57fc361751d2d80cfdeb, 0xe9e36909e63e57fc361751d2d80cfdeb, 0xaa2d612c5f9a3bc943fa6868593da18f, 0xe9e36909e63e57fc361751d2d80cfdeb, 0xaa2d612c5f9a3bc943fa6868593da18f, '', 0, 1533155046);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `vertretungstexte`
--

CREATE TABLE `vertretungstexte` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `beginn` bigint(255) UNSIGNED NOT NULL,
  `textschueler` longblob NOT NULL,
  `textlehrer` longblob NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `vertretungstexte`
--

INSERT INTO `vertretungstexte` (`id`, `beginn`, `textschueler`, `textlehrer`, `idvon`, `idzeit`) VALUES
(0, 1541372400, 0x1d28c84247544602f038c83503b98816bd2432a451d9bb98c5b596fe2c1fbac9e42fef38b1130b393045bf9bee43d110, 0x3f8517e77f8cdb44b7afb6d09dd9b4a7, NULL, NULL);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `vpn`
--

CREATE TABLE `vpn` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `bezeichnung` varbinary(1000) NOT NULL,
  `inhalt` varbinary(5000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `vpn`
--

INSERT INTO `vpn` (`id`, `bezeichnung`, `inhalt`) VALUES
(0, 0x03fd4fdedfaddede8bf667ed83ad6b72, 0x4202f844d9fd95aea7683707d83589ba4f34cb964f02152d917ea78d4aa5539221f7d1bdc3bd88c6d0a64a8073c4c918),
(1, 0xb529e8d9365c2e09bc581cb94b0a5980, 0xeacdef233dec5d1d64ba3d3c0cf66f37),
(2, 0xbd33bfe381934d973ec46755bdb64d1b, 0x624c44023e9c9d12f188fd8be2130a4f9f5dbbab5c4f1ea5d9e9ce510bab114d);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `zeitraeume`
--

CREATE TABLE `zeitraeume` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `schuljahr` bigint(255) UNSIGNED NOT NULL,
  `beginn` bigint(255) UNSIGNED NOT NULL,
  `ende` bigint(255) UNSIGNED NOT NULL,
  `aktiv` int(1) UNSIGNED NOT NULL,
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

--
-- Daten für Tabelle `zeitraeume`
--

INSERT INTO `zeitraeume` (`id`, `schuljahr`, `beginn`, `ende`, `aktiv`, `mo`, `di`, `mi`, `do`, `fr`, `sa`, `so`, `idvon`, `idzeit`) VALUES
(0, 0, 1541113200, 1541977199, 1, 1, 1, 1, 1, 1, 0, 0, NULL, NULL);

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
-- Daten für Tabelle `zulaessigedateien`
--

INSERT INTO `zulaessigedateien` (`id`, `endung`, `kategorie`, `zulaessig`) VALUES
(0, 0x795d692e8b95ba66cd74df174f74ac8a, 0x21f643dc20749f230a2f8af66c24c8f9, 0xaa2d612c5f9a3bc943fa6868593da18f),
(1, 0x45c56ff6b2288d09ded211153cd8b850, 0xbea894693c19a2f4b535cf5521d7a04e, 0xaa2d612c5f9a3bc943fa6868593da18f),
(2, 0x41ac24d18a00c770ed0338bc792ab9d8, 0x12cdf2414e11fdb145d221a197b1828d, 0xe9e36909e63e57fc361751d2d80cfdeb),
(3, 0x1e678f94df9ff80e27c8d77d06c38681, 0xbea894693c19a2f4b535cf5521d7a04e, 0xaa2d612c5f9a3bc943fa6868593da18f),
(4, 0xaa99ef272414b1a229bb9f58d51d020b, 0x71ff6f776618825ba1fa938c2bf25111, 0xaa2d612c5f9a3bc943fa6868593da18f),
(5, 0xe84e95716454cc418af8776fbc3e5534, 0x9efe28fe47e7334849e460203a99ddb0, 0xaa2d612c5f9a3bc943fa6868593da18f),
(6, 0x5dd423930030f75743f56b42101970ee, 0x71ff6f776618825ba1fa938c2bf25111, 0xaa2d612c5f9a3bc943fa6868593da18f),
(7, 0x3f9f862f9da6f4f344a82692fa64f959, 0xff6a7c6715c9546c8d3abfebb8251366, 0xe9e36909e63e57fc361751d2d80cfdeb),
(8, 0xd59e09895da19138a97ce4283368ce59, 0xff6a7c6715c9546c8d3abfebb8251366, 0xe9e36909e63e57fc361751d2d80cfdeb),
(9, 0x742dca84059143b22dedbecb9b06defd, 0xe166fcd7f51b848cb65c1a1fcfbf2fd2, 0xaa2d612c5f9a3bc943fa6868593da18f),
(10, 0xad6c851422a0ad0ae252f79152f83c73, 0x71ff6f776618825ba1fa938c2bf25111, 0xaa2d612c5f9a3bc943fa6868593da18f),
(11, 0x3ae80a6362f843601b2f8213c2802064, 0xf209d66b44c063e965cf1e44715227a5, 0xaa2d612c5f9a3bc943fa6868593da18f),
(12, 0x1651a2970a46b7bff7aaceec3859b799, 0xf209d66b44c063e965cf1e44715227a5, 0xaa2d612c5f9a3bc943fa6868593da18f),
(13, 0x753dc9f39b22972b0e329ff5583fcc87, 0x12cdf2414e11fdb145d221a197b1828d, 0xe9e36909e63e57fc361751d2d80cfdeb),
(14, 0xd103274dacc5b28837c1475ff1084ed1, 0x21f643dc20749f230a2f8af66c24c8f9, 0xe9e36909e63e57fc361751d2d80cfdeb),
(15, 0x78023ea8c78412f7f644145a154452a3, 0xe166fcd7f51b848cb65c1a1fcfbf2fd2, 0xe9e36909e63e57fc361751d2d80cfdeb),
(16, 0xea652c65a0edcec1d0446615ba22c940, 0xe166fcd7f51b848cb65c1a1fcfbf2fd2, 0xe9e36909e63e57fc361751d2d80cfdeb),
(17, 0xd914793ef0a3a3e69561cb39dc916d2a, 0xbea894693c19a2f4b535cf5521d7a04e, 0xaa2d612c5f9a3bc943fa6868593da18f),
(18, 0x810081bd417544f03ab0a1e0828841ce, 0xe166fcd7f51b848cb65c1a1fcfbf2fd2, 0xaa2d612c5f9a3bc943fa6868593da18f),
(19, 0x65c68d388e6e7c843a5096d7949e7a0a, 0x71ff6f776618825ba1fa938c2bf25111, 0xaa2d612c5f9a3bc943fa6868593da18f),
(20, 0x19da67cc7a06e10c43a6e4e691546cc5, 0x12cdf2414e11fdb145d221a197b1828d, 0xe9e36909e63e57fc361751d2d80cfdeb),
(21, 0x52fe3c8ff7e35d844943e0b92c89b9c6, 0x12cdf2414e11fdb145d221a197b1828d, 0xe9e36909e63e57fc361751d2d80cfdeb),
(22, 0xf2c98fc00ace4eabb2e8de919223d95e, 0x9efe28fe47e7334849e460203a99ddb0, 0xaa2d612c5f9a3bc943fa6868593da18f),
(23, 0xb9d1dcf61f6268a55d6c09559f7c190e, 0x9efe28fe47e7334849e460203a99ddb0, 0xaa2d612c5f9a3bc943fa6868593da18f),
(24, 0x03e3be7afb9f7033253a9f3c22ae1110, 0x9efe28fe47e7334849e460203a99ddb0, 0xe9e36909e63e57fc361751d2d80cfdeb),
(25, 0x5103a4b163e8cdd0daa619a68dd1543e, 0x9efe28fe47e7334849e460203a99ddb0, 0xe9e36909e63e57fc361751d2d80cfdeb),
(26, 0x030b78aa58cf2152cae6d26ff4a4eb86, 0xff6a7c6715c9546c8d3abfebb8251366, 0xe9e36909e63e57fc361751d2d80cfdeb),
(27, 0x001ae7dec8c7ca8b310034e5da356280, 0xff6a7c6715c9546c8d3abfebb8251366, 0xe9e36909e63e57fc361751d2d80cfdeb),
(28, 0x8bbd70cad8a221ac6e75a4f98dbc3c27, 0xff6a7c6715c9546c8d3abfebb8251366, 0xe9e36909e63e57fc361751d2d80cfdeb),
(29, 0x6b6324f11711783360f41b6a2a5fa21f, 0x9efe28fe47e7334849e460203a99ddb0, 0xe9e36909e63e57fc361751d2d80cfdeb),
(30, 0xb4ab113b048e86c81ced981e148095a0, 0xff6a7c6715c9546c8d3abfebb8251366, 0xe9e36909e63e57fc361751d2d80cfdeb),
(31, 0x323f34f46b3d33aacf193d8779200ad8, 0x71ff6f776618825ba1fa938c2bf25111, 0xaa2d612c5f9a3bc943fa6868593da18f),
(32, 0x3ccf17b2624c2f892eec3f424d42933c, 0x12cdf2414e11fdb145d221a197b1828d, 0xe9e36909e63e57fc361751d2d80cfdeb),
(33, 0x7d67eee3c25a12270fa617679174cc30, 0xff6a7c6715c9546c8d3abfebb8251366, 0xe9e36909e63e57fc361751d2d80cfdeb),
(34, 0xa58cf8b8da563e57510c22cebf9a3ff3, 0xff6a7c6715c9546c8d3abfebb8251366, 0xe9e36909e63e57fc361751d2d80cfdeb),
(35, 0x582882732bab659f0e2576e043cda2f7, 0xe166fcd7f51b848cb65c1a1fcfbf2fd2, 0xaa2d612c5f9a3bc943fa6868593da18f),
(36, 0x19cd3d0579bd9bccd362a314044bb48f, 0xbea894693c19a2f4b535cf5521d7a04e, 0xaa2d612c5f9a3bc943fa6868593da18f),
(37, 0x5d1e67aa0d51505ab516424fc4008628, 0xff6a7c6715c9546c8d3abfebb8251366, 0xaa2d612c5f9a3bc943fa6868593da18f),
(38, 0x636de1e6eb854127ee10c12b6c787dd7, 0xff6a7c6715c9546c8d3abfebb8251366, 0xaa2d612c5f9a3bc943fa6868593da18f),
(39, 0x4f49d401fa0ce17a321ba43c50d386f6, 0x9efe28fe47e7334849e460203a99ddb0, 0xaa2d612c5f9a3bc943fa6868593da18f),
(40, 0x48b891ecd6e5eb2ada59f6c91a7c28a5, 0x9efe28fe47e7334849e460203a99ddb0, 0xaa2d612c5f9a3bc943fa6868593da18f),
(41, 0x51035694fa5a229ff0a312eee3402052, 0x21f643dc20749f230a2f8af66c24c8f9, 0xe9e36909e63e57fc361751d2d80cfdeb),
(42, 0xc4a7a56e419f2b5a0e7ccee935427bcd, 0xf209d66b44c063e965cf1e44715227a5, 0xaa2d612c5f9a3bc943fa6868593da18f),
(43, 0x8b9f95c1abf9055e954b54a75733f937, 0x21f643dc20749f230a2f8af66c24c8f9, 0xe9e36909e63e57fc361751d2d80cfdeb),
(44, 0xe83903c9f73abc3dea705baa86570774, 0x12cdf2414e11fdb145d221a197b1828d, 0xe9e36909e63e57fc361751d2d80cfdeb),
(45, 0x5327534f3466296058ecfc4751e8e847, 0xe166fcd7f51b848cb65c1a1fcfbf2fd2, 0xaa2d612c5f9a3bc943fa6868593da18f),
(46, 0x7de8643124a8daa87903c4f9581069c0, 0xe166fcd7f51b848cb65c1a1fcfbf2fd2, 0xaa2d612c5f9a3bc943fa6868593da18f),
(47, 0x25f6ddb9acef1dfd40175b263991c8a1, 0xff6a7c6715c9546c8d3abfebb8251366, 0xe9e36909e63e57fc361751d2d80cfdeb),
(48, 0x8dd46d7cee30b0c722eb112df8b641cc, 0x9efe28fe47e7334849e460203a99ddb0, 0xe9e36909e63e57fc361751d2d80cfdeb),
(49, 0x8ef9e5ff94821334156deea40b95fd9a, 0x9efe28fe47e7334849e460203a99ddb0, 0xe9e36909e63e57fc361751d2d80cfdeb),
(50, 0x80a53945c8dcad2ac38e8c7bf2f1545d, 0x9efe28fe47e7334849e460203a99ddb0, 0xe9e36909e63e57fc361751d2d80cfdeb),
(51, 0x5ce0ce6e8793fb66f63fea00bccd36a7, 0xff6a7c6715c9546c8d3abfebb8251366, 0xe9e36909e63e57fc361751d2d80cfdeb),
(52, 0x822ad79dcb1607b3999e63a9e132cbde, 0xff6a7c6715c9546c8d3abfebb8251366, 0xe9e36909e63e57fc361751d2d80cfdeb),
(53, 0x49916e2a6efb70954666b8ae81bdbcee, 0xff6a7c6715c9546c8d3abfebb8251366, 0xe9e36909e63e57fc361751d2d80cfdeb),
(54, 0x5fce330102b518d8ab999ddf7deef59e, 0x21f643dc20749f230a2f8af66c24c8f9, 0xe9e36909e63e57fc361751d2d80cfdeb),
(55, 0xaf60e7dc5fd8346d3ca65dabab7c061a, 0xada5f31c53627144c79979d5e526b900, 0xe9e36909e63e57fc361751d2d80cfdeb),
(56, 0xd946cd7342c045297b060a8e08236d66, 0x9efe28fe47e7334849e460203a99ddb0, 0xe9e36909e63e57fc361751d2d80cfdeb),
(57, 0xec89375ac0c4fb279f8c26cf5d3ece79, 0x9efe28fe47e7334849e460203a99ddb0, 0xe9e36909e63e57fc361751d2d80cfdeb);

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `allgemeineeinstellungen`
--
ALTER TABLE `allgemeineeinstellungen`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `aufsichten`
--
ALTER TABLE `aufsichten`
  ADD UNIQUE KEY `gruppe` (`gruppe`,`gruppenid`,`person`),
  ADD KEY `aufsichtperson` (`person`);

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
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `eventuebersichten`
--
ALTER TABLE `eventuebersichten`
  ADD PRIMARY KEY (`id`),
  ADD KEY `eventuebersichtenspalten` (`spalte`);

--
-- Indizes für die Tabelle `fachschaften`
--
ALTER TABLE `fachschaften`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `faecher`
--
ALTER TABLE `faecher`
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
-- Indizes für die Tabelle `geraete`
--
ALTER TABLE `geraete`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `gremien`
--
ALTER TABLE `gremien`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `klassen`
--
ALTER TABLE `klassen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `klassenklassenstufe` (`klassenstufe`);

--
-- Indizes für die Tabelle `klassenelternvertreter`
--
ALTER TABLE `klassenelternvertreter`
  ADD UNIQUE KEY `lehrer` (`eltern`,`klasse`),
  ADD KEY `klassenelternvertreterklassen` (`klasse`);

--
-- Indizes für die Tabelle `klassenkurse`
--
ALTER TABLE `klassenkurse`
  ADD UNIQUE KEY `klasse` (`klasse`,`kurs`),
  ADD KEY `klassenkursekurse` (`kurs`);

--
-- Indizes für die Tabelle `klassenleitung`
--
ALTER TABLE `klassenleitung`
  ADD UNIQUE KEY `lehrer` (`lehrer`,`klasse`),
  ADD KEY `klassenleitungklassen` (`klasse`);

--
-- Indizes für die Tabelle `klassenschueler`
--
ALTER TABLE `klassenschueler`
  ADD UNIQUE KEY `lehrer` (`schueler`,`klasse`),
  ADD KEY `klassenschuelerklassen` (`klasse`);

--
-- Indizes für die Tabelle `klassensprecher`
--
ALTER TABLE `klassensprecher`
  ADD UNIQUE KEY `lehrer` (`schueler`,`klasse`),
  ADD KEY `klassensprecherklassen` (`klasse`);

--
-- Indizes für die Tabelle `klassenstellvertretung`
--
ALTER TABLE `klassenstellvertretung`
  ADD UNIQUE KEY `lehrer` (`lehrer`,`klasse`),
  ADD KEY `klassenstellvertretungklassen` (`klasse`);

--
-- Indizes für die Tabelle `klassenstufen`
--
ALTER TABLE `klassenstufen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `klassenstufenschuljahr` (`schuljahr`);

--
-- Indizes für die Tabelle `kurse`
--
ALTER TABLE `kurse`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kurseklassenstufen` (`klassenstufe`),
  ADD KEY `kursefaecher` (`fach`);

--
-- Indizes für die Tabelle `kursklassen`
--
ALTER TABLE `kursklassen`
  ADD UNIQUE KEY `kurs` (`kurs`,`klasse`),
  ADD KEY `kursklassenklassen` (`klasse`);

--
-- Indizes für die Tabelle `kurslehrer`
--
ALTER TABLE `kurslehrer`
  ADD UNIQUE KEY `kurs` (`kurs`,`lehrer`),
  ADD KEY `kurslehrerpersonen` (`lehrer`);

--
-- Indizes für die Tabelle `kursschueler`
--
ALTER TABLE `kursschueler`
  ADD UNIQUE KEY `kurs` (`kurs`,`schueler`),
  ADD KEY `kursschuelerpersonen` (`schueler`);

--
-- Indizes für die Tabelle `lehrer`
--
ALTER TABLE `lehrer`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `leihgeraete`
--
ALTER TABLE `leihgeraete`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `leihgeraetverfuegbar`
--
ALTER TABLE `leihgeraetverfuegbar`
  ADD UNIQUE KEY `schuljahr` (`schuljahr`,`leihgeraet`);

--
-- Indizes für die Tabelle `mitgliedschaften`
--
ALTER TABLE `mitgliedschaften`
  ADD PRIMARY KEY (`gruppe`,`gruppenid`,`person`);

--
-- Indizes für die Tabelle `nachrichten_eingang`
--
ALTER TABLE `nachrichten_eingang`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nachrichteneingangpersonen` (`empfaenger`);

--
-- Indizes für die Tabelle `nachrichten_entwurf`
--
ALTER TABLE `nachrichten_entwurf`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nachrichtenentwurf` (`absender`);

--
-- Indizes für die Tabelle `nachrichten_gesendet`
--
ALTER TABLE `nachrichten_gesendet`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nachrichtengesendetpersonen` (`absender`);

--
-- Indizes für die Tabelle `navigationen`
--
ALTER TABLE `navigationen`
  ADD PRIMARY KEY (`id`);

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
-- Indizes für die Tabelle `postfach_signaturen`
--
ALTER TABLE `postfach_signaturen`
  ADD PRIMARY KEY (`person`);

--
-- Indizes für die Tabelle `postfach_tags`
--
ALTER TABLE `postfach_tags`
  ADD PRIMARY KEY (`id`),
  ADD KEY `postfachtagspersonen` (`person`);

--
-- Indizes für die Tabelle `postfach_tagzuordnung`
--
ALTER TABLE `postfach_tagzuordnung`
  ADD UNIQUE KEY `tag` (`tag`,`nachricht`,`art`);

--
-- Indizes für die Tabelle `raeume`
--
ALTER TABLE `raeume`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `raumverfuegbar`
--
ALTER TABLE `raumverfuegbar`
  ADD UNIQUE KEY `schuljahr` (`schuljahr`,`raum`),
  ADD KEY `raumverfuegbarraeume` (`raum`);

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
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `spalten`
--
ALTER TABLE `spalten`
  ADD PRIMARY KEY (`id`),
  ADD KEY `spaltenseiten` (`seite`);

--
-- Indizes für die Tabelle `stunden`
--
ALTER TABLE `stunden`
  ADD UNIQUE KEY `lehrkraft` (`lehrkraft`,`raum`,`kurs`,`zeitraum`,`tag`,`stunde`),
  ADD KEY `stundenraeume` (`raum`),
  ADD KEY `stundenkurse` (`kurs`),
  ADD KEY `stundenschulstunden` (`stunde`);

--
-- Indizes für die Tabelle `tagebuch_0`
--
ALTER TABLE `tagebuch_0`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tagebuch0lehrer` (`lehrkraft`),
  ADD KEY `tagebuch0raeume` (`raum`),
  ADD KEY `tagebuch0kurse` (`kurs`),
  ADD KEY `tagebuch0zeitraeume` (`zeitraum`),
  ADD KEY `tagebuch0tlehrer` (`tlehrkraft`),
  ADD KEY `tagebuch0traeume` (`traum`);

--
-- Indizes für die Tabelle `termine`
--
ALTER TABLE `termine`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `vertretungstexte`
--
ALTER TABLE `vertretungstexte`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `vpn`
--
ALTER TABLE `vpn`
  ADD PRIMARY KEY (`id`);

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
-- Constraints der Tabelle `aufsichten`
--
ALTER TABLE `aufsichten`
  ADD CONSTRAINT `aufsichtperson` FOREIGN KEY (`person`) REFERENCES `personen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

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
-- Constraints der Tabelle `eventuebersichten`
--
ALTER TABLE `eventuebersichten`
  ADD CONSTRAINT `eventuebersichtenspalten` FOREIGN KEY (`spalte`) REFERENCES `spalten` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `klassen`
--
ALTER TABLE `klassen`
  ADD CONSTRAINT `klassenklassenstufe` FOREIGN KEY (`klassenstufe`) REFERENCES `klassenstufen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `klassenelternvertreter`
--
ALTER TABLE `klassenelternvertreter`
  ADD CONSTRAINT `klassenelternvertreterklassen` FOREIGN KEY (`klasse`) REFERENCES `klassen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `klassenelternvertreterpersonen` FOREIGN KEY (`eltern`) REFERENCES `personen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `klassenkurse`
--
ALTER TABLE `klassenkurse`
  ADD CONSTRAINT `klassenkurseklassen` FOREIGN KEY (`klasse`) REFERENCES `klassen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `klassenkursekurse` FOREIGN KEY (`kurs`) REFERENCES `kurse` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `klassenleitung`
--
ALTER TABLE `klassenleitung`
  ADD CONSTRAINT `klassenleitungklassen` FOREIGN KEY (`klasse`) REFERENCES `klassen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `klassenleitungpersonen` FOREIGN KEY (`lehrer`) REFERENCES `personen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `klassenschueler`
--
ALTER TABLE `klassenschueler`
  ADD CONSTRAINT `klassenschuelerklassen` FOREIGN KEY (`klasse`) REFERENCES `klassen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `klassenschuelerpersonen` FOREIGN KEY (`schueler`) REFERENCES `personen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `klassensprecher`
--
ALTER TABLE `klassensprecher`
  ADD CONSTRAINT `klassensprecherklassen` FOREIGN KEY (`klasse`) REFERENCES `klassen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `klassensprecherpersonen` FOREIGN KEY (`schueler`) REFERENCES `personen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `klassenstellvertretung`
--
ALTER TABLE `klassenstellvertretung`
  ADD CONSTRAINT `klassenstellvertretungklassen` FOREIGN KEY (`klasse`) REFERENCES `klassen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `klassenstellvertretungpersonen` FOREIGN KEY (`lehrer`) REFERENCES `personen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `klassenstufen`
--
ALTER TABLE `klassenstufen`
  ADD CONSTRAINT `klassenstufenschuljahr` FOREIGN KEY (`schuljahr`) REFERENCES `schuljahre` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `kurse`
--
ALTER TABLE `kurse`
  ADD CONSTRAINT `kursefaecher` FOREIGN KEY (`fach`) REFERENCES `faecher` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `kurseklassenstufen` FOREIGN KEY (`klassenstufe`) REFERENCES `klassenstufen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `kursklassen`
--
ALTER TABLE `kursklassen`
  ADD CONSTRAINT `kursklassenklassen` FOREIGN KEY (`klasse`) REFERENCES `klassen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `kursklassenkurse` FOREIGN KEY (`kurs`) REFERENCES `kurse` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `kurslehrer`
--
ALTER TABLE `kurslehrer`
  ADD CONSTRAINT `kurslehrerkurse` FOREIGN KEY (`kurs`) REFERENCES `kurse` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `kurslehrerpersonen` FOREIGN KEY (`lehrer`) REFERENCES `personen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `kursschueler`
--
ALTER TABLE `kursschueler`
  ADD CONSTRAINT `kursschuelerkurse` FOREIGN KEY (`kurs`) REFERENCES `kurse` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `kursschuelerpersonen` FOREIGN KEY (`schueler`) REFERENCES `personen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `lehrer`
--
ALTER TABLE `lehrer`
  ADD CONSTRAINT `lehrerpersonen` FOREIGN KEY (`id`) REFERENCES `personen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `nachrichten_eingang`
--
ALTER TABLE `nachrichten_eingang`
  ADD CONSTRAINT `nachrichteneingangpersonen` FOREIGN KEY (`empfaenger`) REFERENCES `personen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `nachrichten_entwurf`
--
ALTER TABLE `nachrichten_entwurf`
  ADD CONSTRAINT `nachrichtenentwurf` FOREIGN KEY (`absender`) REFERENCES `personen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `nachrichten_gesendet`
--
ALTER TABLE `nachrichten_gesendet`
  ADD CONSTRAINT `nachrichtengesendetpersonen` FOREIGN KEY (`absender`) REFERENCES `personen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

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
-- Constraints der Tabelle `postfach_signaturen`
--
ALTER TABLE `postfach_signaturen`
  ADD CONSTRAINT `postfachsignaturenpersonen` FOREIGN KEY (`person`) REFERENCES `personen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `postfach_tags`
--
ALTER TABLE `postfach_tags`
  ADD CONSTRAINT `postfachtagspersonen` FOREIGN KEY (`person`) REFERENCES `personen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `postfach_tagzuordnung`
--
ALTER TABLE `postfach_tagzuordnung`
  ADD CONSTRAINT `postfachtagzuordnungtags` FOREIGN KEY (`tag`) REFERENCES `postfach_tags` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `raumverfuegbar`
--
ALTER TABLE `raumverfuegbar`
  ADD CONSTRAINT `raumverfuegbarraeume` FOREIGN KEY (`raum`) REFERENCES `raeume` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `raumverfuegbarschuljahre` FOREIGN KEY (`schuljahr`) REFERENCES `schuljahre` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

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
-- Constraints der Tabelle `spalten`
--
ALTER TABLE `spalten`
  ADD CONSTRAINT `spaltenseiten` FOREIGN KEY (`seite`) REFERENCES `seiten` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `stunden`
--
ALTER TABLE `stunden`
  ADD CONSTRAINT `stundenkurse` FOREIGN KEY (`kurs`) REFERENCES `kurse` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `stundenpersonen` FOREIGN KEY (`lehrkraft`) REFERENCES `personen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `stundenraeume` FOREIGN KEY (`raum`) REFERENCES `raeume` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `stundenschulstunden` FOREIGN KEY (`stunde`) REFERENCES `schulstunden` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `tagebuch_0`
--
ALTER TABLE `tagebuch_0`
  ADD CONSTRAINT `tagebuch0kurse` FOREIGN KEY (`kurs`) REFERENCES `kurse` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tagebuch0lehrer` FOREIGN KEY (`lehrkraft`) REFERENCES `personen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tagebuch0raeume` FOREIGN KEY (`raum`) REFERENCES `raeume` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tagebuch0tlehrer` FOREIGN KEY (`tlehrkraft`) REFERENCES `personen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tagebuch0traeume` FOREIGN KEY (`traum`) REFERENCES `raeume` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tagebuch0zeitraeume` FOREIGN KEY (`zeitraum`) REFERENCES `zeitraeume` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `zeitraeume`
--
ALTER TABLE `zeitraeume`
  ADD CONSTRAINT `zeitraumschuljahr` FOREIGN KEY (`schuljahr`) REFERENCES `schuljahre` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
