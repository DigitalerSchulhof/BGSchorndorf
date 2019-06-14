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
-- Datenbank: `cms_lehrerzimmer`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `beschluesse`
--

CREATE TABLE `beschluesse` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `gruppe` varbinary(2000) NOT NULL,
  `gruppenid` bigint(255) UNSIGNED NOT NULL,
  `blogeintrag` bigint(255) UNSIGNED NOT NULL,
  `titel` varbinary(5000) NOT NULL,
  `datum` bigint(255) UNSIGNED NOT NULL,
  `langfristig` varbinary(50) NOT NULL,
  `beschreibung` longblob NOT NULL,
  `status` varbinary(200) NOT NULL,
  `pro` bigint(255) UNSIGNED NOT NULL,
  `contra` bigint(255) UNSIGNED NOT NULL,
  `enthaltung` bigint(255) UNSIGNED NOT NULL,
  `ersteller` bigint(255) UNSIGNED NOT NULL,
  `erstellzeit` bigint(255) UNSIGNED NOT NULL,
  `idvon` bigint(255) NOT NULL,
  `idzeit` bigint(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `beschluesse`
--

INSERT INTO `beschluesse` (`id`, `gruppe`, `gruppenid`, `blogeintrag`, `titel`, `datum`, `langfristig`, `beschreibung`, `status`, `pro`, `contra`, `enthaltung`, `ersteller`, `erstellzeit`, `idvon`, `idzeit`) VALUES
(0, 0x68bf6fb62015a4ca84c444ea31c83a51, 19, 0, 0x65210d2e82296bce68e516b9f4cdfe406fc836e9b4422d830601445a8531ba82, 1532901600, 0xae9676d2e54f7e4eb1ca6eaa8f513677, 0x5f9a938ea84ae1c16eece09dad6ce14ab6f8d1b823f65c2f695df4b8867c9df4cc7bc8db92798d3a1cce763818e5affc, 0x28e705c87f9e373b6c0c9515ae88e0a9, 2, 0, 0, 0, 1534254186, 0, 0),
(1, 0xd36a4da5592cef21054e029c992e9916, 2, 1, 0x2d97d772076d4004539cfef87d407cb6, 1535925600, 0x46bf34e1bff5ed2b0cf5ecea8fd9c7ec, 0x335a3f3e98bf96fdf7116d524c58a59e, 0x28e705c87f9e373b6c0c9515ae88e0a9, 7, 5, 3, 0, 1535994189, 0, 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `blog`
--

CREATE TABLE `blog` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `gruppe` varbinary(2000) NOT NULL,
  `gruppenid` bigint(255) UNSIGNED NOT NULL,
  `ersteller` bigint(255) UNSIGNED NOT NULL,
  `erstellzeit` bigint(255) UNSIGNED NOT NULL,
  `titel` varbinary(2000) NOT NULL,
  `inhalt` longblob NOT NULL,
  `datum` bigint(255) UNSIGNED NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `blog`
--

INSERT INTO `blog` (`id`, `gruppe`, `gruppenid`, `ersteller`, `erstellzeit`, `titel`, `inhalt`, `datum`, `idvon`, `idzeit`) VALUES
(0, 0x68bf6fb62015a4ca84c444ea31c83a51, 19, 0, 1534254186, 0x8770345fae7a1a584390269024b1aa4d89f6a27ab30b6f738ee6fa77975a826c, 0xae0eefc49ccb47ae69f86c1787741cbb0b301b88f0aeb33a40cbaf65414bb8eeb781fbed11f7377986a0aafec2e79465949edbb8e92412c69a96b702c2686645fa4a630dd2fcc2b3b1ed59de5a7b81f68c54768e6a043f1b342351d21cc87d58fc91a3492ab08abee4f2ae25bd57b5ebf9a4c42e1e04d6831ea0303d0fd438f2323b3037d3f19a1f719446ef557add6cf934b0007efecf31a294f8ae2dfdc34dfa3feb9fb1005ec9406ee2a995e770688d398b7ca5d81ac500eeb2febfeeb300e96f045e7b0229cedc4ada8dee107c5190ab3322a117b3c09ac6d39026760f56803c9765c98e4c5d7202894f256af8d13fbcc427a61b410dfdfce71f63f4def0a0dfe8ea54f5b191c5727684c0320ee8109779863083e4824dc756659a67c2457cfed970993d430272e6f4df7c1c37f2fafab274970e0946086ca8769f659fe781d33a752e092197cf9d892f4b6005156cc1f39c2c6bc47940b638c0a6c790e23900e861f36787c4fa426b4109922feecb8c7d4759ece6124582b65f851ab759fb7f4bd7441a8c65bb7339d873f75330d1edacb2770bdb9e2d474d26811be43453859c2c4926d2383df666476c516cb0afb17881c4287bfd669b8eea1fcc26f4a54f78071063d68c33ef38628966d8ec0576db5b8b728652a5b6d938086682d24471e77c0de7d1697cdf8ff0203bee666ab01b950f6e3d8b2658f685b21804d0c88089a5e26ebcea59db84654f3448e4edbf773fcce1da3c98f24c1da6c719f13375322bc8f12de7acac485e3c153a92285f8448640340ff657bb3d20428d077d0626b2831fe28ea07c5aab3dd3359c1d3940733a844b4cf6fb18e7e8b12410341e9d3433b784fc8001b00d035fcfcc1a2b0f90dbd8472bdcd19aa5c5358f964d7c9e23ca9bd82173094729078f51a21f98c3134b20e7516e158513112cee66c99f4447b9d79f8a056931fe06743c361c255cc6052b93719bffa2abd02a927d44f255cfcd5e89d0fcd6860d49a51b601af2e80e23c410dbda47c32256844dcb0c06155a7b448e22ffc7716e8ba15754bfa1be98eb8968f14ed8ab73f4027a5a7c6ee60747320aab54b78c4cb43e74db27af87bd437017e75c22a6e9556898be37c17378bb8ed5d5d91026ace665bac88801f4e0c430b6c9b1a031ea9999aad24e38a856cca818f12fbb0a0419220642cb14c66ba69e11150698c7a18ad54819c03420629c53fe9eb0853d765ee7e8c5df6890ea1ed11dd9c77c863adfecf75bf4b1bbea67627fad479539d7e593f9045f3014d93f40462978f0cbe77e765cca09c5fac0d4940626f0bd376c0ecd5fd9d2d514f7e20665af9a2615769161f7d89e77380fd1e85b261634ad98d1f3f61ec4f35481c63cf9271ac8cb737d1e0108771adf334162d5b629dce57854d03050d43b573a3599e8798bd3f6ebeb687a184a8ace1fb6a0aa31b7c1f01a57ebeb50127b0932d0df28b02e51a5a3cead33003ac1b21b0a1d4db636f455fb0cc293b19337f102d389d6541826e3e89cbb9cf50f9493b145a6c29830bc831fd726b1fce5937117dc99fe41ba188dfc3ec8af5c9cfbcdfe33c419d53e2e5ec048271d83fb41991d3b995fd4d639b997e3f14e78aa6960396b059438ab4b6ac29ca47fceb1904c0535970431a41a45e3f80818f7b8215c424e5b41d36b261233d947a712c68f04713c640bef7b5492622f14a68e8c861cec954623dfd19a27c1e6d13bcd03714794fd42c980545f4c033cba0097903dad4775ad13cc314692a7ddb79d6aeefccc20aad3110d9361defa52023ed9019fb9deeedd84ffdb6c599247e7c149d784f1db294593b78b17a1c36250dea2c9dffc37b85fb91b261eba23a6abc0c0a00ac85aede71d9bb310b4298f574cc3d72194e72e2a57300d249572ff0e6c8928aae8d74876c56589cc7f62f7029132e46bdf81d1a8fa189de5b9aeaac6918aa053b238a6120d93fd64bbf07d9c0af7d6ca03c6ed62919074310028115cea5458f0707e8cbde13cba065cfad722e03f9e22c3a2c733478d6a673ce1a23969e9cdfec1c9f007b8a0937c87ed06bfd7642a2d3465f8fa0d52a0babcbcb4e4df1356308587850f42e1ef548529ff9f748d6d59ec6f17ded8c53d3ec241811d556efb3ef822dc08e66be5541b930d3b52c627f865575614ba51563840a531b884a08a25edcfea2cd5f2a58c118f51de6af33345f9f72b0a79c2a470bcb92dbe78cc7e18089e4b85ecb13a1a425bfbe87b370aea8e2769c779dba018ccf7c93b62fc17216467cd380ea6f4ab85931d85f7dc3feffcbc5834d01d511d996625d345e973d9baed9be2525a46f9d745c06d45f48f52620f7d46736b1bec6ea9c77168a559e9b1fb19489af818ea1c0c946928b531a7bfda385b413005d426446bc23ce4b3aba05cb19147e3963a0f28472655ee630e3d6a707b3df9bc8751a514f23911bcc0ce9e74c0ef2cca3dc41830d67031d0accf6f9ec5a34911d32845b04275123584077a354c615be5b4cf6ce70d9886683a93aa3e446b6e2f5ba3630e2bb14bfb015cf2ffe7d56987ab7d5e1edabc109117404bd4be090d94b4099ea5f0b8231b709f9318b2bf651e51f3ad79aa9ce09d7ec9e00d4048b8ca063cfc30daf9666d33d5afad62a8ac533e881ac5e62f4e47504c17962919a89321d046c2120ccf53082a0df9426885c4f03440b96480537576e811a514dd4e165e0313f69805bf731af8ce8fbce2e0abf43adefdc2fafe56244c22cd443fad59ee49b72f254b0cd9e97da8e90b6194c52c029dac16074501384c887c82c7f71c8fe9363129234d53be4dd8424268f09590328883ea3c467d56028b201d692ec2181eb4d8716c5951a19c7f995da8bb3506835b3e06a16cd2c8e4d518b269437bdaf9598b6720e84, 1532901600, NULL, NULL),
(1, 0xd36a4da5592cef21054e029c992e9916, 2, 0, 1535994189, 0x72f0a0289efdc2ee437dc51333613f02, 0xec7d0a09484460cbdc32ca038fed0940, 1535925600, NULL, NULL),
(2, 0xd36a4da5592cef21054e029c992e9916, 2, 0, 1535997733, 0xab713f10e5503bf8b341dc54388c5fb9, 0xec7d0a09484460cbdc32ca038fed0940, 1535925600, NULL, NULL);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `blogeintragdownloads`
--

CREATE TABLE `blogeintragdownloads` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `blogeintrag` bigint(255) UNSIGNED NOT NULL,
  `pfad` blob NOT NULL,
  `titel` varbinary(5000) NOT NULL,
  `beschreibung` blob NOT NULL,
  `dateiname` varbinary(50) NOT NULL,
  `dateigroesse` varbinary(50) NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `beschluesse`
--
ALTER TABLE `beschluesse`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `blog`
--
ALTER TABLE `blog`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `blogeintragdownloads`
--
ALTER TABLE `blogeintragdownloads`
  ADD PRIMARY KEY (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;