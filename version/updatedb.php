/*

Format der Datei:
Wird aktualisiert, wird jede Query unter der zuvor installierten Version ausgeführt.


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

CREATE TABLE `wichtigeeinstellungen` (`id` bigint(255) UNSIGNED NOT NULL, `inhalt` varbinary(2000) NOT NULL, `wert` varbinary(2000) NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
ALTER TABLE `wichtigeeinstellungen` ADD PRIMARY KEY (`id`);

INSERT INTO wichtigeeinstellungen (id, inhalt, wert) VALUES (0, AES_ENCRYPT('Schulname', '{cms_schluessel}'), AES_ENCRYPT('<?php 						// echo $CMS_SCHULE;?>', '{cms_schluessel}'));
INSERT INTO wichtigeeinstellungen (id, inhalt, wert) VALUES (1, AES_ENCRYPT('Schulname Genitiv', '{cms_schluessel}'), AES_ENCRYPT('<?php 		// echo $CMS_SCHULE_GENITIV;
																																																																		?>', '{cms_schluessel}'));
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

CREATE TABLE `maileinstellungen` (`id` bigint(255) UNSIGNED NOT NULL, `inhalt` varbinary(2000) NOT NULL, `wert` blob NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
ALTER TABLE `maileinstellungen` ADD PRIMARY KEY (`id`);

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

CREATE TABLE `master` ( `id` bigint(255) UNSIGNED NOT NULL, `inhalt` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL, `wert` longtext COLLATE utf8_unicode_ci DEFAULT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
ALTER TABLE `master` ADD PRIMARY KEY (`id`);

INSERT INTO master (id, inhalt, wert) VALUES (0, 'Fußzeile', '');
INSERT INTO master (id, inhalt, wert) VALUES (1, 'Anmelden', '');

-- 0.8

-- 0.8.2

DROP TABLE ebestellung;

CREATE TABLE `ebestellung` ( `id` bigint(255) UNSIGNED NOT NULL, `bedarf` tinyint(1) UNSIGNED DEFAULT NULL, `anrede` varbinary(500) DEFAULT NULL, `vorname` varbinary(1000) DEFAULT NULL, `nachname` varbinary(1000) DEFAULT NULL, `strasse` varbinary(1000) DEFAULT NULL, `hausnr` varbinary(1000) DEFAULT NULL, `plz` varbinary(1000) DEFAULT NULL, `ort` varbinary(1000) DEFAULT NULL, `telefon` varbinary(500) DEFAULT NULL, `email` varbinary(500) DEFAULT NULL, `bedingungen` tinyint(1) UNSIGNED DEFAULT NULL, `eingegangen` bigint(255) UNSIGNED DEFAULT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
ALTER TABLE `ebestellung` ADD PRIMARY KEY (`id`);
ALTER TABLE `ebestellung` ADD CONSTRAINT `ebestellung` FOREIGN KEY (`id`) REFERENCES `personen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

CREATE TABLE `etoken` ( `id` bigint(255) UNSIGNED NOT NULL, `token` varbinary(5000) DEFAULT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
ALTER TABLE `etoken` ADD PRIMARY KEY (`id`);
ALTER TABLE `etoken` ADD CONSTRAINT `etokenperson` FOREIGN KEY (`id`) REFERENCES `personen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

DELETE FROM allgemeineeinstellungen WHERE id = 240;
INSERT INTO allgemeineeinstellungen (id, inhalt, wert) VALUES (240, AES_ENCRYPT('Netze Offizielle Version', '{cms_schluessel}'), AES_ENCRYPT('1', '{cms_schluessel}'));
INSERT INTO allgemeineeinstellungen (id, inhalt, wert) VALUES (241, AES_ENCRYPT('Netze GitHub Benutzer', '{cms_schluessel}'), AES_ENCRYPT('', '{cms_schluessel}'));
INSERT INTO allgemeineeinstellungen (id, inhalt, wert) VALUES (242, AES_ENCRYPT('Netze GitHub Repository', '{cms_schluessel}'), AES_ENCRYPT('', '{cms_schluessel}'));
INSERT INTO allgemeineeinstellungen (id, inhalt, wert) VALUES (243, AES_ENCRYPT('Netze GitHub OAuth', '{cms_schluessel}'), AES_ENCRYPT('', '{cms_schluessel}'));

-- 0.8.6

CREATE TABLE `todo` ( `id` BIGINT(255) UNSIGNED NOT NULL , `person` BIGINT(255) UNSIGNED NULL DEFAULT NULL , `bezeichnung` VARBINARY(5000) NULL DEFAULT NULL, `beschreibung` BLOB NULL DEFAULT NULL , `idvon` BIGINT(255) UNSIGNED NULL DEFAULT NULL , `idzeit` BIGINT(255) UNSIGNED NULL DEFAULT NULL ) ENGINE = InnoDB;
ALTER TABLE `todo` ADD PRIMARY KEY (`id`);
ALTER TABLE `todo` ADD CONSTRAINT `todopersonpersonen` FOREIGN KEY (`person`) REFERENCES `personen`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;

<?php
foreach ($CMS_GRUPPEN as $g) {
	$gk = cms_textzudb($g);
	echo "ALTER TABLE `{$gk}todoartikel` ADD CONSTRAINT `{$gk}todoartikelpersonpersonen` FOREIGN KEY (`person`) REFERENCES `personen`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;";
	echo "ALTER TABLE `{$gk}todoartikel` ADD `bezeichnung` VARBINARY(5000) NULL DEFAULT NULL AFTER `termin`;";
	echo "ALTER TABLE `{$gk}todoartikel` ADD `beschreibung` BLOB NULL DEFAULT NULL AFTER `bezeichnung`;";
	// echo "ALTER TABLE `{$gk}todoartikel` ADD `id` BIGINT(255) UNSIGNED NOT NULL FIRST;";
	// echo "SET @n = 0; UPDATE {$gk}todoartikel SET id = (@n := @n + 1);";
	// echo "ALTER TABLE `{$gk}todoartikel` ADD PRIMARY KEY( `id`);";
	//
	// echo "ALTER TABLE `{$gk}todoartikel` DROP FOREIGN KEY `{$gk}todoartikelblogblogeintrag`;";
	// echo "ALTER TABLE `{$gk}todoartikel` DROP FOREIGN KEY `{$gk}todoartikelpersonpersonen`;";
	// echo "ALTER TABLE `{$gk}todoartikel` DROP FOREIGN KEY `{$gk}todoartikeltermintermin`;";
	//
	// echo "ALTER TABLE `{$gk}todoartikel` DROP INDEX `{$gk}todoartikelpersonpersonen`;";
	// echo "ALTER TABLE `{$gk}todoartikel` DROP INDEX `{$gk}todoartikelblogblogeintrag`;";
	// echo "ALTER TABLE `{$gk}todoartikel` DROP INDEX `{$gk}todoartikeltermintermin`;";
	// echo "ALTER TABLE `{$gk}todoartikel` DROP INDEX `{$gk}todoartikelindex`;";
	//
	// echo "ALTER TABLE `{$gk}todoartikel` ADD CONSTRAINT `{$gk}todoartikelpersonpersonen` FOREIGN KEY (`person`) REFERENCES `personen`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;";
	// echo "ALTER TABLE `{$gk}todoartikel` ADD CONSTRAINT `{$gk}todoartikelblogblogeintrag` FOREIGN KEY (`blogeintrag`) REFERENCES `{$gk}blogeintraegeintern`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;";
	// echo "ALTER TABLE `{$gk}todoartikel` ADD CONSTRAINT `{$gk}todoartikeltermintermin` FOREIGN KEY (`termin`) REFERENCES `{$gk}termineintern`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;";
	//
	// echo "ALTER TABLE `{$gk}todoartikel` ADD `idvon` BIGINT(255) UNSIGNED NULL DEFAULT NULL AFTER `termin`, ADD `idzeit` BIGINT(255) UNSIGNED NULL DEFAULT NULL AFTER `idvon`;";
}
?>

-- 0.9

DROP TABLE ebestellung;
CREATE TABLE `ebestellung` ( `id` bigint(255) UNSIGNED NOT NULL, `bedarf` tinyint(1) UNSIGNED DEFAULT NULL, `status` tinyint(1) UNSIGNED DEFAULT NULL, `anrede` varbinary(500) DEFAULT NULL, `vorname` varbinary(1000) DEFAULT NULL, `nachname` varbinary(1000) DEFAULT NULL, `strasse` varbinary(1000) DEFAULT NULL, `hausnr` varbinary(1000) DEFAULT NULL, `plz` varbinary(1000) DEFAULT NULL, `ort` varbinary(1000) DEFAULT NULL, `telefon` varbinary(500) DEFAULT NULL, `email` varbinary(500) DEFAULT NULL, `bedingungen` tinyint(1) UNSIGNED DEFAULT NULL, `eingegangen` bigint(255) UNSIGNED DEFAULT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `ebestellung` ADD PRIMARY KEY (`id`);
ALTER TABLE `ebestellung` ADD CONSTRAINT `ebestellung` FOREIGN KEY (`id`) REFERENCES `personen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

CREATE TABLE `egeraete` ( `id` bigint(255) UNSIGNED NOT NULL, `titel` varbinary(1000) DEFAULT NULL, `bild` varbinary(1000) DEFAULT NULL, `beschreibung` blob DEFAULT NULL, `preis` bigint(255) DEFAULT NULL, `stk` int(255) UNSIGNED DEFAULT NULL, `lieferzeit` varbinary(1000) DEFAULT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `egeraete` ADD PRIMARY KEY (`id`);

CREATE TABLE `eposten` ( `bestellung` bigint(255) UNSIGNED NOT NULL, `geraet` bigint(255) UNSIGNED NOT NULL, `stueck` bigint(255) UNSIGNED NOT NULL DEFAULT 0) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
ALTER TABLE `eposten` ADD PRIMARY KEY (`bestellung`,`geraet`), ADD KEY `bestellunggeraet` (`geraet`);
ALTER TABLE `eposten` ADD CONSTRAINT `bestellunggeraet` FOREIGN KEY (`geraet`) REFERENCES `egeraete` (`id`) ON DELETE CASCADE ON UPDATE CASCADE, ADD CONSTRAINT `bestellungperson` FOREIGN KEY (`bestellung`) REFERENCES `ebestellung` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- 0.9.3

ALTER TABLE `personen_einstellungen` ADD `wikiknopf` VARBINARY(50) NOT NULL AFTER `inaktivitaetszeit`;
UPDATE `personen_einstellungen` SET wikiknopf = AES_ENCRYPT('1', '{cms_schluessel}');

CREATE TABLE `cms_schulhof`.`vplanwuensche` ( `id` BIGINT(255) UNSIGNED NOT NULL , `datum` BIGINT(255) UNSIGNED NULL DEFAULT NULL , `wunsch` LONGBLOB NULL DEFAULT NULL , `status` VARCHAR(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL , `idvon` BIGINT(255) UNSIGNED NULL , `idzeit` BIGINT(255) UNSIGNED NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB CHARSET=utf8 COLLATE utf8_unicode_ci;

-- 0.9.5

ALTER TABLE `personen_einstellungen` ADD `dateiaenderung` VARBINARY(50) NOT NULL AFTER `wikiknopf`;
UPDATE `personen_einstellungen` SET dateiaenderung = AES_ENCRYPT('1', '{cms_schluessel}');

-- 0.9.6

ALTER TABLE `vplanwuensche` ADD `ersteller` BIGINT(255) UNSIGNED NULL DEFAULT NULL AFTER `status`, ADD `erstellzeit` BIGINT(255) UNSIGNED NULL DEFAULT NULL AFTER `ersteller`;
ALTER TABLE `vplanwuensche` ADD CONSTRAINT `erstellerperson` FOREIGN KEY (`ersteller`) REFERENCES `personen`(`id`) ON DELETE SET NULL ON UPDATE CASCADE;
ALTER TABLE `egeraete` ADD `idvon` BIGINT(255) UNSIGNED NULL DEFAULT NULL AFTER `lieferzeit`, ADD `idzeit` BIGINT(255) UNSIGNED NULL DEFAULT NULL AFTER `idvon`;

-- 0.10

-- 0.10.1

ALTER TABLE `eventuebersichten` ADD `breakingalt` VARCHAR(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL AFTER `galerieanzahlneu`, ADD `breakingaktuell` VARCHAR(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL AFTER `breakingalt`, ADD `breakingneu` VARCHAR(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL AFTER `breakingaktuell`, ADD `breakinglink1alt` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL AFTER `breakingneu`, ADD `breakinglink1aktuell` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL AFTER `breakinglink1alt`, ADD `breakinglink1neu` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL AFTER `breakinglink1aktuell`, ADD `breakinglink2alt` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL AFTER `breakinglink1neu`, ADD `breakinglink2aktuell` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL AFTER `breakinglink2alt`, ADD `breakinglink2neu` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL AFTER `breakinglink2aktuell`, ADD `breakinglink3alt` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL AFTER `breakinglink2neu`, ADD `breakinglink3aktuell` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL AFTER `breakinglink3alt`, ADD `breakinglink3neu` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL AFTER `breakinglink3aktuell`, ADD `breakinglink4alt` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL AFTER `breakinglink3neu`, ADD `breakinglink4aktuell` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL AFTER `breakinglink4alt`, ADD `breakinglink4neu` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL AFTER `breakinglink4aktuell`, ADD `breakinglink5alt` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL AFTER `breakinglink4neu`, ADD `breakinglink5aktuell` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL AFTER `breakinglink5alt`, ADD `breakinglink5neu` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL AFTER `breakinglink5aktuell`, ADD `breakingtext1alt` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL AFTER `breakinglink5neu`, ADD `breakingtext1aktuell` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL AFTER `breakingtext1alt`, ADD `breakingtext1neu` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL AFTER `breakingtext1aktuell`, ADD `breakingtext2alt` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL AFTER `breakingtext1neu`, ADD `breakingtext2aktuell` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL AFTER `breakingtext2alt`, ADD `breakingtext2neu` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL AFTER `breakingtext2aktuell`, ADD `breakingtext3alt` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL AFTER `breakingtext2neu`, ADD `breakingtext3aktuell` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL AFTER `breakingtext3alt`, ADD `breakingtext3neu` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL AFTER `breakingtext3aktuell`, ADD `breakingtext4alt` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL AFTER `breakingtext3neu`, ADD `breakingtext4aktuell` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL AFTER `breakingtext4alt`, ADD `breakingtext4neu` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL AFTER `breakingtext4aktuell`, ADD `breakingtext5alt` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL AFTER `breakingtext4neu`, ADD `breakingtext5aktuell` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL AFTER `breakingtext5alt`, ADD `breakingtext5neu` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL AFTER `breakingtext5aktuell`;

-- 0.10.4

ALTER TABLE `personen_einstellungen` ADD `blogtodo` VARBINARY(50) NOT NULL AFTER `dateiaenderung`, ADD `termintodo` VARBINARY(50) NOT NULL AFTER `blogtodo`;
UPDATE `personen_einstellungen` SET `blogtodo` = AES_ENCRYPT('1', '{cms_schluessel}'), `termintodo` = AES_ENCRYPT('1', '{cms_schluessel}');

-- 0.10.7

<?php
foreach ($CMS_GRUPPEN as $g) {
	$gk = cms_textzudb($g);
	echo "CREATE TABLE `{$gk}links` ( `id` bigint(255) unsigned NOT NULL, `gruppe` bigint(255) unsigned NOT NULL, `link` varbinary(5000) NOT NULL, `titel` varbinary(5000) NOT NULL, `beschreibung` blob NOT NULL, `idvon` bigint(255) unsigned DEFAULT NULL, `idzeit` bigint(255) unsigned DEFAULT NULL, PRIMARY KEY (`id`), KEY `{$gk}linksgruppegruppe` (`gruppe`), CONSTRAINT `{$gk}}linksgruppegruppe` FOREIGN KEY (`gruppe`) REFERENCES `$gk` (`id`) ON DELETE CASCADE ON UPDATE CASCADE) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
}
?>

-- 0.10.8

ALTER TABLE `tagebuch` ADD `leistungsmessung` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0' AFTER `freigabe`;
ALTER TABLE `tagebuch` ADD `urheber` BIGINT(255) UNSIGNED NULL DEFAULT NULL AFTER `leistungsmessung`;
ALTER TABLE `tagebuch` ADD PRIMARY KEY(`id`);
ALTER TABLE `tagebuch` ADD CONSTRAINT `tagebuchurheber` FOREIGN KEY (`urheber`) REFERENCES `personen`(`id`) ON DELETE SET NULL ON UPDATE CASCADE;

CREATE TABLE `lobtadel` (`id` bigint(255) UNSIGNED NOT NULL, `eintrag` bigint(255) UNSIGNED DEFAULT NULL, `person` bigint(255) UNSIGNED DEFAULT NULL, `art` varchar(1) COLLATE utf8_unicode_ci DEFAULT NULL, `charakter` varchar(1) COLLATE utf8_unicode_ci DEFAULT NULL, `bemerkung` blob DEFAULT NULL, `idvon` bigint(255) UNSIGNED DEFAULT NULL, `idzeit` bigint(255) UNSIGNED DEFAULT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `lobtadel` ADD PRIMARY KEY (`id`), ADD KEY `lobtadeleintragtagebuch` (`eintrag`);
ALTER TABLE `lobtadel` ADD CONSTRAINT `lobtadeleintragtagebuch` FOREIGN KEY (`eintrag`) REFERENCES `tagebuch` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `lobtadel` DROP `charakter`;

CREATE TABLE `fehlzeiten` ( `id` bigint(255) UNSIGNED NOT NULL, `person` bigint(255) UNSIGNED DEFAULT NULL, `von` bigint(255) UNSIGNED DEFAULT NULL, `bis` bigint(255) UNSIGNED DEFAULT NULL, `bemerkung` blob DEFAULT NULL, `entschuldigt` tinyint(1) UNSIGNED NOT NULL DEFAULT 0, `idvon` bigint(255) UNSIGNED DEFAULT NULL, `idzeit` bigint(255) UNSIGNED DEFAULT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `fehlzeiten` ADD PRIMARY KEY (`id`);

-- 0.10.9
ALTER TABLE `tagebuch` ADD `eintragsdatum` BIGINT(255) UNSIGNED NOT NULL AFTER `urheber`;

ALTER TABLE `lobtadel` ADD `urheber` BIGINT UNSIGNED NULL AFTER `bemerkung`, ADD `eintragszeit` BIGINT UNSIGNED NULL AFTER `urheber`;
ALTER TABLE `fehlzeiten` ADD `urheber` BIGINT UNSIGNED NULL AFTER `entschuldigt`, ADD `eintragszeit` BIGINT UNSIGNED NULL AFTER `urheber`;
ALTER TABLE `fehlzeiten` ADD CONSTRAINT `fzurheberperson` FOREIGN KEY (`urheber`) REFERENCES `personen`(`id`) ON DELETE SET NULL ON UPDATE CASCADE;
ALTER TABLE `fehlzeiten` ADD CONSTRAINT `fzpersonpersonen` FOREIGN KEY (`person`) REFERENCES `personen`(`id`) ON DELETE SET NULL ON UPDATE CASCADE;
ALTER TABLE `lobtadel` ADD CONSTRAINT `lturheberperson` FOREIGN KEY (`urheber`) REFERENCES `personen`(`id`) ON DELETE SET NULL ON UPDATE CASCADE;
ALTER TABLE `lobtadel` ADD CONSTRAINT `ltpersonpersonen` FOREIGN KEY (`person`) REFERENCES `personen`(`id`) ON DELETE SET NULL ON UPDATE CASCADE;

-- 0.10.15
INSERT INTO schulanmeldung (id, inhalt, wert) VALUES (10, AES_ENCRYPT('Persönlich nötig', '{cms_schluessel}'), AES_ENCRYPT('1', '{cms_schluessel}'));
ALTER TABLE `voranmeldung_schueler` ADD `empfehlung` VARBINARY(50) NULL DEFAULT NULL AFTER `geimpft`, ADD `wunschschueler` VARBINARY(2000) NULL DEFAULT NULL AFTER `empfehlung`;

-- 0.10.16
ALTER TABLE `voranmeldung_schueler` ADD `staat` VARBINARY(2000) NULL AFTER `ort`;
ALTER TABLE `voranmeldung_eltern` ADD `haupt` VARBINARY(50) NULL AFTER `briefe`;
ALTER TABLE `voranmeldung_eltern` ADD `rolle` VARBINARY(50) NULL AFTER `haupt`;

-- 0.10.18

CREATE TABLE `pushendpoints` (
 `id` bigint(255) unsigned NOT NULL,
 `nutzer` bigint(255) unsigned NOT NULL,
 `endpoint` varbinary(5000) NOT NULL,
 `p256dh` varbinary(5000) NOT NULL,
 `auth` varbinary(5000) NOT NULL,
 `idvon` bigint(255) unsigned DEFAULT NULL,
 `idzeit` bigint(255) unsigned DEFAULT NULL,
 PRIMARY KEY (`id`),
 KEY `pushendpointsnutzer` (`nutzer`),
 CONSTRAINT `pushendpointsnutzer` FOREIGN KEY (`nutzer`) REFERENCES `nutzerkonten` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci


-- 0.11

CREATE TABLE `coronatest` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `tester` bigint(255) UNSIGNED DEFAULT NULL,
  `zeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `coronatest`
  ADD PRIMARY KEY (`id`),
  ADD KEY `coronatesttester` (`tester`);

ALTER TABLE `coronatest`
  ADD CONSTRAINT `coronatesttester` FOREIGN KEY (`tester`) REFERENCES `personen` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE `coronatest` ADD `idzeit` BIGINT(255) UNSIGNED NULL DEFAULT NULL AFTER `zeit`, ADD `idvon` BIGINT(255) UNSIGNED NULL DEFAULT NULL AFTER `idzeit`;

CREATE TABLE `coronagetestet` (
  `person` bigint(255) UNSIGNED NOT NULL,
  `test` bigint(255) UNSIGNED NOT NULL,
  `art` varbinary(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `coronagetestet`
  ADD PRIMARY KEY (`person`,`test`),
  ADD KEY `coronagetestettest` (`test`);


ALTER TABLE `coronagetestet`
  ADD CONSTRAINT `coronagetestetperson` FOREIGN KEY (`person`) REFERENCES `personen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `coronagetestettest` FOREIGN KEY (`test`) REFERENCES `coronatest` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- 0.11.3

CREATE TABLE `coronafrei` (
  `person` bigint(255) UNSIGNED NOT NULL,
  `freibis` bigint(255) UNSIGNED DEFAULT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `coronafrei`
  ADD PRIMARY KEY (`person`);

ALTER TABLE `coronafrei`
  ADD CONSTRAINT `coronafreiperson` FOREIGN KEY (`person`) REFERENCES `personen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
