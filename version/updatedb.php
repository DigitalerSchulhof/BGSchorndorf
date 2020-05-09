/*

	Format der Datei:
	Wird aktualisiert, wird jede Query unter zuvor installierten Version ausgeführt.


	-- Version

	Queries nach der Version

	-- Version

	Queries

	-- Version

	Aufeinander folgende Versionen ohne Änderungen dazwischen können ausgelassen werden.

*/

-- 0.6.4

ALTER TABLE `gremienblogeintraglinks` DROP FOREIGN KEY `blogeintraggremienblogeintraglinksblogeintraege`;
ALTER TABLE `gremienblogeintraglinks` ADD CONSTRAINT `blogeintraggremienblogeintraglinksblogeintraege` FOREIGN KEY (`blogeintrag`) REFERENCES `gremienblogeintraegeintern`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `fachschaftenblogeintraglinks` DROP FOREIGN KEY `blogeintragfachschaftenblogeintraglinksblogeintraege`;
ALTER TABLE `fachschaftenblogeintraglinks` ADD CONSTRAINT `blogeintragfachschaftenblogeintraglinksblogeintraege` FOREIGN KEY (`blogeintrag`) REFERENCES `fachschaftenblogeintraegeintern`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `klassenblogeintraglinks` DROP FOREIGN KEY `blogeintragklassenblogeintraglinksblogeintraege`;
ALTER TABLE `klassenblogeintraglinks` ADD CONSTRAINT `blogeintragklassenblogeintraglinksblogeintraege` FOREIGN KEY (`blogeintrag`) REFERENCES `klassenblogeintraegeintern`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `kurseblogeintraglinks` DROP FOREIGN KEY `blogeintragkurseblogeintraglinksblogeintraege`;
ALTER TABLE `kurseblogeintraglinks` ADD CONSTRAINT `blogeintragkurseblogeintraglinksblogeintraege` FOREIGN KEY (`blogeintrag`) REFERENCES `kurseblogeintraegeintern`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `stufenblogeintraglinks` DROP FOREIGN KEY `blogeintragstufenblogeintraglinksblogeintraege`;
ALTER TABLE `stufenblogeintraglinks` ADD CONSTRAINT `blogeintragstufenblogeintraglinksblogeintraege` FOREIGN KEY (`blogeintrag`) REFERENCES `stufenblogeintraegeintern`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `arbeitsgemeinschaftenblogeintraglinks` DROP FOREIGN KEY `blogeintragarbeitsgemeinschaftenblogeintraglinksblogeintraege`;
ALTER TABLE `arbeitsgemeinschaftenblogeintraglinks` ADD CONSTRAINT `blogeintragarbeitsgemeinschaftenblogeintraglinksblogeintraege` FOREIGN KEY (`blogeintrag`) REFERENCES `arbeitsgemeinschaftenblogeintraegeintern`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `arbeitskreiseblogeintraglinks` DROP FOREIGN KEY `blogeintragarbeitskreiseblogeintraglinksblogeintraege`;
ALTER TABLE `arbeitskreiseblogeintraglinks` ADD CONSTRAINT `blogeintragarbeitskreiseblogeintraglinksblogeintraege` FOREIGN KEY (`blogeintrag`) REFERENCES `arbeitskreiseblogeintraegeintern`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `fahrtenblogeintraglinks` DROP FOREIGN KEY `blogeintragfahrtenblogeintraglinksblogeintraege`;
ALTER TABLE `fahrtenblogeintraglinks` ADD CONSTRAINT `blogeintragfahrtenblogeintraglinksblogeintraege` FOREIGN KEY (`blogeintrag`) REFERENCES `fahrtenblogeintraegeintern`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `wettbewerbeblogeintraglinks` DROP FOREIGN KEY `blogeintragwettbewerbeblogeintraglinksblogeintraege`;
ALTER TABLE `wettbewerbeblogeintraglinks` ADD CONSTRAINT `blogeintragwettbewerbeblogeintraglinksblogeintraege` FOREIGN KEY (`blogeintrag`) REFERENCES `wettbewerbeblogeintraegeintern`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `ereignisseblogeintraglinks` DROP FOREIGN KEY `blogeintragereignisseblogeintraglinksblogeintraege`;
ALTER TABLE `ereignisseblogeintraglinks` ADD CONSTRAINT `blogeintragereignisseblogeintraglinksblogeintraege` FOREIGN KEY (`blogeintrag`) REFERENCES `ereignisseblogeintraegeintern`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `sonstigegruppenblogeintraglinks` DROP FOREIGN KEY `blogeintragsonstigegruppenblogeintraglinksblogeintraege`;
ALTER TABLE `sonstigegruppenblogeintraglinks` ADD CONSTRAINT `blogeintragsonstigegruppenblogeintraglinksblogeintraege` FOREIGN KEY (`blogeintrag`) REFERENCES `sonstigegruppenblogeintraegeintern`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- 0.7

CREATE TABLE `updatenews` ( `person` BIGINT(255) UNSIGNED NULL DEFAULT NULL , `gesehen` TINYINT NULL DEFAULT NULL ) ENGINE = InnoDB;
ALTER TABLE `updatenews` ADD PRIMARY KEY (`person`);
ALTER TABLE `updatenews` ADD CONSTRAINT `updatenewsperson` FOREIGN KEY (`person`) REFERENCES `nutzerkonten`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- 0.7.1

-- 0.7.3

CREATE TABLE `ebestellung` (`id` BIGINT(255) UNSIGNED NOT NULL ,`bedarf` TINYINT(1) UNSIGNED NULL DEFAULT NULL ,`leihe` BIGINT(255) UNSIGNED NULL DEFAULT NULL ,`laptopubuntu` BIGINT(255) UNSIGNED NULL DEFAULT NULL ,`laptopwindows` BIGINT(255) UNSIGNED NULL DEFAULT NULL ,`kombimittel` BIGINT(255) UNSIGNED NULL DEFAULT NULL ,`kombigut` BIGINT(255) UNSIGNED NULL DEFAULT NULL ,`anrede` VARBINARY(500) NULL DEFAULT NULL ,`vorname` VARBINARY(1000) NULL DEFAULT NULL ,`nachname` VARBINARY(1000) NULL DEFAULT NULL ,`strasse` VARBINARY(1000) NULL DEFAULT NULL ,`hausnr` VARBINARY(1000) NULL DEFAULT NULL ,`plz` VARBINARY(1000) NULL DEFAULT NULL ,`ort` VARBINARY(1000) NULL DEFAULT NULL ,`telefon` VARBINARY(500) NULL DEFAULT NULL,`email` VARBINARY(500) NULL DEFAULT NULL,`bedingungen` TINYINT(1) UNSIGNED NULL , `bestellnr` VARBINARY(500) NULL DEFAULT NULL, `eingegangen` BIGINT(255) UNSIGNED NULL DEFAULT NULL, PRIMARY KEY (`id`)) ENGINE = InnoDB;

ALTER TABLE `ebestellung` ADD CONSTRAINT `ebestellung` FOREIGN KEY (`id`) REFERENCES `personen`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;

CREATE TABLE `wichtigeeinstellungen` (`id` bigint(255) UNSIGNED NOT NULL,  `inhalt` varbinary(2000) NOT NULL,  `wert` varbinary(2000) NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
ALTER TABLE `wichtigeeinstellungen`  ADD PRIMARY KEY (`id`);

INSERT INTO wichtigeeinstellungen (id, inhalt, wert) VALUES (0, AES_ENCRYPT('Schulname', '{cms_schluessel}'), AES_ENCRYPT('<?php 						// echo $CMS_SCHULE;?>', '{cms_schluessel}'));
INSERT INTO wichtigeeinstellungen (id, inhalt, wert) VALUES (1, AES_ENCRYPT('Schulname Genitiv', '{cms_schluessel}'), AES_ENCRYPT('<?php 		// echo $CMS_SCHULE_GENITIV;?>', '{cms_schluessel}'));
INSERT INTO wichtigeeinstellungen (id, inhalt, wert) VALUES (2, AES_ENCRYPT('Schule Ort', '{cms_schluessel}'), AES_ENCRYPT('<?php 					// echo $CMS_ORT;?>', '{cms_schluessel}'));
INSERT INTO wichtigeeinstellungen (id, inhalt, wert) VALUES (3, AES_ENCRYPT('Schule Straße', '{cms_schluessel}'), AES_ENCRYPT('<?php 				// echo $CMS_STRASSE;?>', '{cms_schluessel}'));
INSERT INTO wichtigeeinstellungen (id, inhalt, wert) VALUES (4, AES_ENCRYPT('Schule PLZOrt', '{cms_schluessel}'), AES_ENCRYPT('<?php 				// echo $CMS_PLZORT;?>', '{cms_schluessel}'));
INSERT INTO wichtigeeinstellungen (id, inhalt, wert) VALUES (5, AES_ENCRYPT('Schule Telefon', '{cms_schluessel}'), AES_ENCRYPT('<?php 			// echo $CMS_TELEFON;?>', '{cms_schluessel}'));
INSERT INTO wichtigeeinstellungen (id, inhalt, wert) VALUES (6, AES_ENCRYPT('Schule Fax', '{cms_schluessel}'), AES_ENCRYPT('<?php 					// echo $CMS_TELEFAX;?>', '{cms_schluessel}'));
INSERT INTO wichtigeeinstellungen (id, inhalt, wert) VALUES (7, AES_ENCRYPT('Schule Mail', '{cms_schluessel}'), AES_ENCRYPT('<?php 					// echo $CMS_MAILSCHULE;?>', '{cms_schluessel}'));
INSERT INTO wichtigeeinstellungen (id, inhalt, wert) VALUES (8, AES_ENCRYPT('Schule Domain', '{cms_schluessel}'), AES_ENCRYPT('<?php 				// echo $CMS_DOMAIN;?>', '{cms_schluessel}'));
INSERT INTO wichtigeeinstellungen (id, inhalt, wert) VALUES (9, AES_ENCRYPT('Schulleitung Name', '{cms_schluessel}'), AES_ENCRYPT('<?php 		// echo $CMS_NAMESCHULLEITER;?>', '{cms_schluessel}'));
INSERT INTO wichtigeeinstellungen (id, inhalt, wert) VALUES (10, AES_ENCRYPT('Schulleitung Mail', '{cms_schluessel}'), AES_ENCRYPT('', '{cms_schluessel}'));
INSERT INTO wichtigeeinstellungen (id, inhalt, wert) VALUES (11, AES_ENCRYPT('Datenschutz Name', '{cms_schluessel}'), AES_ENCRYPT('<?php 		//echo $CMS_NAMEDATENSCHUTZ;?>', '{cms_schluessel}'));
INSERT INTO wichtigeeinstellungen (id, inhalt, wert) VALUES (12, AES_ENCRYPT('Datenschutz Mail', '{cms_schluessel}'), AES_ENCRYPT('<?php 		//echo $CMS_MAILDATENSCHUTZ;?>', '{cms_schluessel}'));
INSERT INTO wichtigeeinstellungen (id, inhalt, wert) VALUES (13, AES_ENCRYPT('Presse Name', '{cms_schluessel}'), AES_ENCRYPT('<?php 				//echo $CMS_NAMEPRESSERECHT;?>', '{cms_schluessel}'));
INSERT INTO wichtigeeinstellungen (id, inhalt, wert) VALUES (14, AES_ENCRYPT('Presse Mail', '{cms_schluessel}'), AES_ENCRYPT('<?php 				//echo $CMS_MAILPRESSERECHT;?>', '{cms_schluessel}'));
INSERT INTO wichtigeeinstellungen (id, inhalt, wert) VALUES (15, AES_ENCRYPT('Webmaster Name', '{cms_schluessel}'), AES_ENCRYPT('', '{cms_schluessel}'));
INSERT INTO wichtigeeinstellungen (id, inhalt, wert) VALUES (16, AES_ENCRYPT('Webmaster Mail', '{cms_schluessel}'), AES_ENCRYPT('<?php 			//echo $CMS_WEBMASTER;?>', '{cms_schluessel}'));
INSERT INTO wichtigeeinstellungen (id, inhalt, wert) VALUES (17, AES_ENCRYPT('Administration Name', '{cms_schluessel}'), AES_ENCRYPT('<?php //echo $CMS_NAMEADMINISTRATION;?>', '{cms_schluessel}'));
INSERT INTO wichtigeeinstellungen (id, inhalt, wert) VALUES (18, AES_ENCRYPT('Administration Mail', '{cms_schluessel}'), AES_ENCRYPT('<?php //echo $CMS_MAILADMINISTRATION;?>', '{cms_schluessel}'));

INSERT INTO allgemeineeinstellungen (id, inhalt, wert) VALUES (232, AES_ENCRYPT('Maximale Dateigröße', '{cms_schluessel}'), AES_ENCRYPT('20971520', '{cms_schluessel}'));

CREATE TABLE `maileinstellungen` (`id` bigint(255) UNSIGNED NOT NULL,  `inhalt` varbinary(2000) NOT NULL,  `wert` blob NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
ALTER TABLE `maileinstellungen`  ADD PRIMARY KEY (`id`);

INSERT INTO maileinstellungen (id, inhalt, wert) VALUES (0, AES_ENCRYPT('Absender', '{cms_schluessel}'), AES_ENCRYPT('<?php 							//echo $CMS_MAILABSENDER;?>', '{cms_schluessel}'));
INSERT INTO maileinstellungen (id, inhalt, wert) VALUES (1, AES_ENCRYPT('SMTP-Host', '{cms_schluessel}'), AES_ENCRYPT('<?php 							//echo $CMS_MAILHOST;?>', '{cms_schluessel}'));
INSERT INTO maileinstellungen (id, inhalt, wert) VALUES (2, AES_ENCRYPT('SMTP-Authentifizierung', '{cms_schluessel}'), AES_ENCRYPT('<?php //echo boolval($CMS_MAILSMTPAUTH);?>', '{cms_schluessel}'));
INSERT INTO maileinstellungen (id, inhalt, wert) VALUES (3, AES_ENCRYPT('Benutzername', '{cms_schluessel}'), AES_ENCRYPT('<?php 					//echo $CMS_MAILUSERNAME;?>', '{cms_schluessel}'));
INSERT INTO maileinstellungen (id, inhalt, wert) VALUES (4, AES_ENCRYPT('Passwort', '{cms_schluessel}'), AES_ENCRYPT('<?php 							//echo $CMS_MAILPASSWORT;?>', '{cms_schluessel}'));
INSERT INTO maileinstellungen (id, inhalt, wert) VALUES (5, AES_ENCRYPT('Signatur Text', '{cms_schluessel}'), AES_ENCRYPT('', '{cms_schluessel}'));
INSERT INTO maileinstellungen (id, inhalt, wert) VALUES (6, AES_ENCRYPT('Signatur HTML', '{cms_schluessel}'), AES_ENCRYPT('', '{cms_schluessel}'));

INSERT INTO allgemeineeinstellungen (id, inhalt, wert) VALUES (233, AES_ENCRYPT('Hosting Schülernetz', '{cms_schluessel}'), AES_ENCRYPT('<?php 		//echo $CMS_HOSTINGPARTNEREX;?>', '{cms_schluessel}'));
INSERT INTO allgemeineeinstellungen (id, inhalt, wert) VALUES (234, AES_ENCRYPT('Hosting Lehrernetz', '{cms_schluessel}'), AES_ENCRYPT('<?php 		//echo $CMS_HOSTINGPARTNERIN;?>', '{cms_schluessel}'));
INSERT INTO allgemeineeinstellungen (id, inhalt, wert) VALUES (235, AES_ENCRYPT('Netze Basisverzeichnis', '{cms_schluessel}'), AES_ENCRYPT('<?php //echo $CMS_BASE;?>', '{cms_schluessel}'));
INSERT INTO allgemeineeinstellungen (id, inhalt, wert) VALUES (236, AES_ENCRYPT('Netze Lehrerserver', '{cms_schluessel}'), AES_ENCRYPT('<?php 		//echo $CMS_LN_DA;?>', '{cms_schluessel}'));
INSERT INTO allgemeineeinstellungen (id, inhalt, wert) VALUES (237, AES_ENCRYPT('Netze VPN-Anleitung', '{cms_schluessel}'), AES_ENCRYPT('<?php 		//echo $CMS_LN_ZB_VPN;?>', '{cms_schluessel}'));
INSERT INTO allgemeineeinstellungen (id, inhalt, wert) VALUES (238, AES_ENCRYPT('Netze Socket-IP', '{cms_schluessel}'), AES_ENCRYPT('<?php 				//echo $CMS_SOCKET_IP;?>', '{cms_schluessel}'));
INSERT INTO allgemeineeinstellungen (id, inhalt, wert) VALUES (239, AES_ENCRYPT('Netze Socket-Port', '{cms_schluessel}'), AES_ENCRYPT('<?php 			//echo $CMS_SOCKET_PORT;?>', '{cms_schluessel}'));
INSERT INTO allgemeineeinstellungen (id, inhalt, wert) VALUES (240, AES_ENCRYPT('Netze GitHub', '{cms_schluessel}'), AES_ENCRYPT('<?php 					//echo $GITHUB_OAUTH;?>', '{cms_schluessel}'));

CREATE TABLE `master` (  `id` bigint(255) UNSIGNED NOT NULL,  `inhalt` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL,  `wert` longtext COLLATE utf8_unicode_ci DEFAULT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
ALTER TABLE `master`  ADD PRIMARY KEY (`id`);

INSERT INTO master (id, inhalt, wert) VALUES (0, 'Fußzeile', '');
INSERT INTO master (id, inhalt, wert) VALUES (1, 'Anmelden', '');

-- 0.8

-- 0.8.2

DROP TABLE ebestellung;

CREATE TABLE `ebestellung` (  `id` bigint(255) UNSIGNED NOT NULL,  `bedarf` tinyint(1) UNSIGNED DEFAULT NULL,  `anrede` varbinary(500) DEFAULT NULL,  `vorname` varbinary(1000) DEFAULT NULL,  `nachname` varbinary(1000) DEFAULT NULL,  `strasse` varbinary(1000) DEFAULT NULL,  `hausnr` varbinary(1000) DEFAULT NULL,  `plz` varbinary(1000) DEFAULT NULL,  `ort` varbinary(1000) DEFAULT NULL,  `telefon` varbinary(500) DEFAULT NULL,  `email` varbinary(500) DEFAULT NULL,  `bedingungen` tinyint(1) UNSIGNED DEFAULT NULL,  `eingegangen` bigint(255) UNSIGNED DEFAULT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
ALTER TABLE `ebestellung`  ADD PRIMARY KEY (`id`);
ALTER TABLE `ebestellung`  ADD CONSTRAINT `ebestellung` FOREIGN KEY (`id`) REFERENCES `personen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

CREATE TABLE `etoken` (  `id` bigint(255) UNSIGNED NOT NULL,  `token` varbinary(5000) DEFAULT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
ALTER TABLE `etoken`  ADD PRIMARY KEY (`id`);
ALTER TABLE `etoken`  ADD CONSTRAINT `etokenperson` FOREIGN KEY (`id`) REFERENCES `personen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

DELETE FROM allgemeineeinstellungen WHERE id = 240;
INSERT INTO allgemeineeinstellungen (id, inhalt, wert) VALUES (240, AES_ENCRYPT('Netze Offizielle Version', '{cms_schluessel}'), AES_ENCRYPT('1', '{cms_schluessel}'));
INSERT INTO allgemeineeinstellungen (id, inhalt, wert) VALUES (241, AES_ENCRYPT('Netze GitHub Benutzer', '{cms_schluessel}'), AES_ENCRYPT('', '{cms_schluessel}'));
INSERT INTO allgemeineeinstellungen (id, inhalt, wert) VALUES (242, AES_ENCRYPT('Netze GitHub Repository', '{cms_schluessel}'), AES_ENCRYPT('', '{cms_schluessel}'));
INSERT INTO allgemeineeinstellungen (id, inhalt, wert) VALUES (243, AES_ENCRYPT('Netze GitHub OAuth', '{cms_schluessel}'), AES_ENCRYPT('', '{cms_schluessel}'));

-- 0.8.4
