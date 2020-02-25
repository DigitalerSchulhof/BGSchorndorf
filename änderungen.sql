-- Änderungen sind in GitHub nachverfolgbar
-- Format:
CREATE TABLE `favoritseiten` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `person` bigint(255) UNSIGNED DEFAULT NULL,
  `url` varbinary(5000) DEFAULT NULL,
  `bezeichnung` varbinary(5000) DEFAULT NULL,
  `idvon` bigint(255) UNSIGNED NOT NULL,
  `idzeit` bigint(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `favoritseiten`
  ADD PRIMARY KEY (`id`),
  ADD KEY `favoritseitenperson` (`person`);

ALTER TABLE `favoritseiten`
  ADD CONSTRAINT `favoritseitenperson` FOREIGN KEY (`person`) REFERENCES `nutzerkonten` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;


ALTER TABLE `rythmisierung` CHANGE `jahr` `beginn` BIGINT(255) UNSIGNED NOT NULL;

CREATE TABLE `unterricht` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `pkurs` bigint(255) UNSIGNED NOT NULL,
  `pbeginn` bigint(255) UNSIGNED NOT NULL,
  `pende` bigint(255) UNSIGNED NOT NULL,
  `plehrer` bigint(255) UNSIGNED NOT NULL,
  `praum` bigint(255) UNSIGNED NOT NULL,
  `tkurs` bigint(255) UNSIGNED NOT NULL,
  `tbeginn` bigint(255) UNSIGNED NOT NULL,
  `tende` bigint(255) UNSIGNED NOT NULL,
  `tlehrer` bigint(255) UNSIGNED NOT NULL,
  `traum` bigint(255) UNSIGNED NOT NULL,
  `vplananzeigen` tinyint(1) UNSIGNED NOT NULL,
  `vplanart` VARCHAR(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `vplanbemerkung` varbinary(5000) NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
ALTER TABLE `unterricht`
  ADD PRIMARY KEY (`id`),
  ADD KEY `unterrichtkurs` (`tkurs`),
  ADD KEY `unterrichtlehrer` (`tlehrer`),
  ADD KEY `unterrichtraum` (`traum`);
ALTER TABLE `unterricht`
  ADD CONSTRAINT `unterrichtkurs` FOREIGN KEY (`tkurs`) REFERENCES `kurse` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `unterrichtlehrer` FOREIGN KEY (`tlehrer`) REFERENCES `lehrer` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `unterrichtraum` FOREIGN KEY (`traum`) REFERENCES `raeume` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `unterricht` CHANGE `pkurs` `pkurs` BIGINT(255) UNSIGNED NULL, CHANGE `pbeginn` `pbeginn` BIGINT(255) UNSIGNED NULL, CHANGE `pende` `pende` BIGINT(255) UNSIGNED NULL, CHANGE `plehrer` `plehrer` BIGINT(255) UNSIGNED NULL, CHANGE `praum` `praum` BIGINT(255) UNSIGNED NULL;

CREATE TABLE `unterrichtkonflikt` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `pkurs` bigint(255) UNSIGNED NOT NULL,
  `pbeginn` bigint(255) UNSIGNED NOT NULL,
  `pende` bigint(255) UNSIGNED NOT NULL,
  `plehrer` bigint(255) UNSIGNED NOT NULL,
  `praum` bigint(255) UNSIGNED NOT NULL,
  `tkurs` bigint(255) UNSIGNED NOT NULL,
  `tbeginn` bigint(255) UNSIGNED NOT NULL,
  `tende` bigint(255) UNSIGNED NOT NULL,
  `tlehrer` bigint(255) UNSIGNED NOT NULL,
  `traum` bigint(255) UNSIGNED NOT NULL,
  `vplananzeigen` tinyint(1) UNSIGNED NOT NULL,
  `vplanart` VARCHAR(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `vplanbemerkung` varbinary(5000) NOT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
ALTER TABLE `unterrichtkonflikt`
  ADD KEY `unterrichtkonfliktkurs` (`tkurs`),
  ADD KEY `unterrichtkonfliktlehrer` (`tlehrer`),
  ADD KEY `unterrichtkonfliktraum` (`traum`);
ALTER TABLE `unterricht`
  ADD CONSTRAINT `unterrichtkonfliktkurs` FOREIGN KEY (`tkurs`) REFERENCES `kurse` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `unterrichtkonfliktlehrer` FOREIGN KEY (`tlehrer`) REFERENCES `lehrer` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `unterrichtkonfliktraum` FOREIGN KEY (`traum`) REFERENCES `raeume` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

CREATE TABLE `vplantext` (
  `zeit` bigint(255) UNSIGNED NOT NULL,
  `art` varchar(1) COLLATE utf8_unicode_ci NOT NULL,
  `inhalt` blob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
ALTER TABLE `vplantext`
  ADD PRIMARY KEY (`zeit`,`art`);

CREATE TABLE `raeumeklassen` (
  `raum` bigint(255) UNSIGNED NOT NULL,
  `klasse` bigint(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `raeumeklassen`
  ADD PRIMARY KEY (`raum`,`klasse`),
  ADD KEY `raeumeklassenklasse` (`klasse`);

ALTER TABLE `raeumeklassen`
  ADD CONSTRAINT `raeumeklassenklasse` FOREIGN KEY (`klasse`) REFERENCES `klassen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `raeumeklassenraum` FOREIGN KEY (`raum`) REFERENCES `raeume` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

CREATE TABLE `raeumestufen` (
  `raum` bigint(255) UNSIGNED NOT NULL,
  `stufe` bigint(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `raeumestufen`
  ADD PRIMARY KEY (`raum`,`stufe`),
  ADD KEY `raeumestufenstufe` (`stufe`);

ALTER TABLE `raeumestufen`
  ADD CONSTRAINT `raeumestufenraum` FOREIGN KEY (`raum`) REFERENCES `raeume` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `raeumestufenstufe` FOREIGN KEY (`stufe`) REFERENCES `stufen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

CREATE TABLE `stufenlehrer` (
  `stufe` bigint(255) UNSIGNED NOT NULL,
  `lehrer` bigint(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `stufenlehrer`
  ADD PRIMARY KEY (`stufe`,`lehrer`),
  ADD KEY `stufenlehrerlehrer` (`lehrer`);

ALTER TABLE `stufenlehrer`
  ADD CONSTRAINT `stufenlehrerlehrer` FOREIGN KEY (`lehrer`) REFERENCES `lehrer` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `stufenlehrerstufe` FOREIGN KEY (`stufe`) REFERENCES `stufen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

CREATE TABLE `klassenlehrer` (
  `klasse` bigint(255) UNSIGNED NOT NULL,
  `lehrer` bigint(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `klassenlehrer`
  ADD PRIMARY KEY (`klasse`,`lehrer`),
  ADD KEY `klassenlehrerlehrer` (`lehrer`);

ALTER TABLE `klassenlehrer`
  ADD CONSTRAINT `klassenlehrerklassen` FOREIGN KEY (`klasse`) REFERENCES `klassen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `klassenlehrerlehrer` FOREIGN KEY (`lehrer`) REFERENCES `lehrer` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

CREATE TABLE `klassenlehrerstellvertreter` (
  `klasse` bigint(255) UNSIGNED NOT NULL,
  `lehrer` bigint(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `klassenlehrerstellvertreter`
  ADD PRIMARY KEY (`klasse`,`lehrer`),
  ADD KEY `klassenlehrerstellvertreterlehrer` (`lehrer`);

ALTER TABLE `klassenlehrerstellvertreter`
  ADD CONSTRAINT `klassenlehrerstellvertreterklasse` FOREIGN KEY (`klasse`) REFERENCES `klassen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `klassenlehrerstellvertreterlehrer` FOREIGN KEY (`lehrer`) REFERENCES `lehrer` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

CREATE TABLE `stufenlehrerstellvertreter` (
  `stufe` bigint(255) UNSIGNED NOT NULL,
  `lehrer` bigint(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `stufenlehrerstellvertreter`
  ADD PRIMARY KEY (`stufe`,`lehrer`),
  ADD KEY `stufenlehrerstellvertreterlehrer` (`lehrer`);

ALTER TABLE `stufenlehrerstellvertreter`
  ADD CONSTRAINT `stufenlehrerstellvertreterlehrer` FOREIGN KEY (`lehrer`) REFERENCES `lehrer` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `stufenlehrerstellvertreterstufe` FOREIGN KEY (`stufe`) REFERENCES `stufen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `unterrichtkonflikt` CHANGE `idvon` `idvon` BIGINT(255) UNSIGNED NULL, CHANGE `idzeit` `idzeit` BIGINT(255) UNSIGNED NULL;

CREATE TABLE `schienen` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `bezeichnung` varbinary(5000) DEFAULT NULL,
  `zeitraum` bigint(255) UNSIGNED DEFAULT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `schienen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `schienenzeitraum` (`zeitraum`);

ALTER TABLE `schienen`
  ADD CONSTRAINT `schienenzeitraum` FOREIGN KEY (`zeitraum`) REFERENCES `zeitraeume` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

CREATE TABLE `schienenkurse` (
  `schiene` bigint(255) UNSIGNED NOT NULL,
  `kurs` bigint(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `schienenkurse`
  ADD PRIMARY KEY (`schiene`,`kurs`),
  ADD KEY `schienenkursekurs` (`kurs`);

ALTER TABLE `schienenkurse`
  ADD CONSTRAINT `schienenkursekurs` FOREIGN KEY (`kurs`) REFERENCES `kurse` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `schienenkurseschiene` FOREIGN KEY (`schiene`) REFERENCES `schienen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `gremienmitglieder` CHANGE `chatbannbis` `chatbannbis` BIGINT(255) UNSIGNED NULL DEFAULT NULL, CHANGE `chatbannvon` `chatbannvon` BIGINT(255) UNSIGNED NULL DEFAULT NULL;ALTER TABLE `fachschaftenmitglieder` CHANGE `chatbannbis` `chatbannbis` BIGINT(255) UNSIGNED NULL DEFAULT NULL, CHANGE `chatbannvon` `chatbannvon` BIGINT(255) UNSIGNED NULL DEFAULT NULL;ALTER TABLE `klassenmitglieder` CHANGE `chatbannbis` `chatbannbis` BIGINT(255) UNSIGNED NULL DEFAULT NULL, CHANGE `chatbannvon` `chatbannvon` BIGINT(255) UNSIGNED NULL DEFAULT NULL;ALTER TABLE `kursemitglieder` CHANGE `chatbannbis` `chatbannbis` BIGINT(255) UNSIGNED NULL DEFAULT NULL, CHANGE `chatbannvon` `chatbannvon` BIGINT(255) UNSIGNED NULL DEFAULT NULL;ALTER TABLE `stufenmitglieder` CHANGE `chatbannbis` `chatbannbis` BIGINT(255) UNSIGNED NULL DEFAULT NULL, CHANGE `chatbannvon` `chatbannvon` BIGINT(255) UNSIGNED NULL DEFAULT NULL;ALTER TABLE `arbeitsgemeinschaftenmitglieder` CHANGE `chatbannbis` `chatbannbis` BIGINT(255) UNSIGNED NULL DEFAULT NULL, CHANGE `chatbannvon` `chatbannvon` BIGINT(255) UNSIGNED NULL DEFAULT NULL;ALTER TABLE `arbeitskreisemitglieder` CHANGE `chatbannbis` `chatbannbis` BIGINT(255) UNSIGNED NULL DEFAULT NULL, CHANGE `chatbannvon` `chatbannvon` BIGINT(255) UNSIGNED NULL DEFAULT NULL;ALTER TABLE `fahrtenmitglieder` CHANGE `chatbannbis` `chatbannbis` BIGINT(255) UNSIGNED NULL DEFAULT NULL, CHANGE `chatbannvon` `chatbannvon` BIGINT(255) UNSIGNED NULL DEFAULT NULL;ALTER TABLE `wettbewerbemitglieder` CHANGE `chatbannbis` `chatbannbis` BIGINT(255) UNSIGNED NULL DEFAULT NULL, CHANGE `chatbannvon` `chatbannvon` BIGINT(255) UNSIGNED NULL DEFAULT NULL;ALTER TABLE `ereignissemitglieder` CHANGE `chatbannbis` `chatbannbis` BIGINT(255) UNSIGNED NULL DEFAULT NULL, CHANGE `chatbannvon` `chatbannvon` BIGINT(255) UNSIGNED NULL DEFAULT NULL;ALTER TABLE `sonstigegruppenmitglieder` CHANGE `chatbannbis` `chatbannbis` BIGINT(255) UNSIGNED NULL DEFAULT NULL, CHANGE `chatbannvon` `chatbannvon` BIGINT(255) UNSIGNED NULL DEFAULT NULL;

ALTER TABLE `personen` ADD `zweitid` BIGINT(255) UNSIGNED NULL DEFAULT NULL AFTER `geschlecht`, ADD `drittid` BIGINT(255) UNSIGNED NULL DEFAULT NULL AFTER `zweitid`, ADD `viertid` BIGINT(255) UNSIGNED NULL DEFAULT NULL AFTER `drittid`;

CREATE TABLE `tutorenwesen` (
  `lehrer` bigint(255) UNSIGNED NOT NULL,
  `schueler` bigint(255) UNSIGNED NOT NULL,
  `schuljahr` bigint(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `tutorenwesen`
  ADD PRIMARY KEY (`lehrer`,`schueler`,`schuljahr`),
  ADD KEY `tutorenwesenschueler` (`schueler`),
  ADD KEY `tutorenwesenschuljahr` (`schuljahr`);

ALTER TABLE `tutorenwesen`
  ADD CONSTRAINT `tutorenwesenlehrer` FOREIGN KEY (`lehrer`) REFERENCES `lehrer` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tutorenwesenschueler` FOREIGN KEY (`schueler`) REFERENCES `personen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tutorenwesenschuljahr` FOREIGN KEY (`schuljahr`) REFERENCES `schuljahre` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `hausmeisterauftraege`  ADD `raumgeraet` BIGINT(255) NULL DEFAULT NULL  AFTER `erledigtvon`;
ALTER TABLE `hausmeisterauftraege`  ADD `leihgeraet` BIGINT(255) NULL DEFAULT NULL  AFTER `raumgeraet`;
ALTER TABLE `hausmeisterauftraege` CHANGE `raumgeraet` `raumgeraet` BIGINT(255) UNSIGNED NULL DEFAULT NULL, CHANGE `leihgeraet` `leihgeraet` BIGINT(255) UNSIGNED NULL DEFAULT NULL;
ALTER TABLE `hausmeisterauftraege` ADD CONSTRAINT `hausmeisterauftraegeraeumegeraete` FOREIGN KEY (`raumgeraet`) REFERENCES `raeumegeraete`(`id`) ON DELETE CASCADE ON UPDATE CASCADE; ALTER TABLE `hausmeisterauftraege` ADD CONSTRAINT `hausmeisterauftraegeleihgeraete` FOREIGN KEY (`leihgeraet`) REFERENCES `leihengeraete`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;



CREATE TABLE `newslettertypen` (
  `id` bigint(255) unsigned NOT NULL,
  `bezeichnung` varbinary(5000) DEFAULT NULL,
  `idvon` bigint(255) DEFAULT NULL,
  `idzeit` bigint(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `wnewsletter` (
 `id` bigint(255) unsigned NOT NULL,
 `spalte` bigint(255) unsigned DEFAULT NULL,
 `position` bigint(255) unsigned DEFAULT NULL,
 `aktiv` varchar(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
 `bezeichnungalt` text CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
 `bezeichnungaktuell` text CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
 `bezeichnungneu` text CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
 `beschreibungalt` text CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
 `beschreibungaktuell` text CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
 `beschreibungneu` text CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
 `typalt` bigint(255) unsigned DEFAULT NULL,
 `typaktuell` bigint(255) unsigned DEFAULT NULL,
 `typneu` bigint(255) unsigned DEFAULT NULL,
 `idvon` bigint(255) unsigned DEFAULT NULL,
 `idzeit` bigint(255) unsigned DEFAULT NULL,
 PRIMARY KEY (`id`),
 KEY `newsletterspalten` (`spalte`),
 KEY `newslettertypenalt` (`typalt`),
 KEY `newslettertypenaktuell` (`typaktuell`),
 KEY `newslettertypenneu` (`typneu`),
 CONSTRAINT `newsletterspalten` FOREIGN KEY (`spalte`) REFERENCES `spalten` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
 CONSTRAINT `newslettertypenaktuell` FOREIGN KEY (`typaktuell`) REFERENCES `newslettertypen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
 CONSTRAINT `newslettertypenalt` FOREIGN KEY (`typalt`) REFERENCES `newslettertypen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
 CONSTRAINT `newslettertypenneu` FOREIGN KEY (`typneu`) REFERENCES `newslettertypen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `newsletterempfaenger` (
  `id` bigint(255) unsigned NOT NULL,
  `name` varbinary(5000) DEFAULT NULL,
  `email` varbinary(5000) DEFAULT NULL,
  `newsletter` bigint(255) UNSIGNED DEFAULT NULL,
  `token` varbinary(5000) DEFAULT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `newsletterempfaenger`
  ADD PRIMARY KEY (`ìd`),
  ADD KEY `newsletterempfaengernewsletter` (`newsletter`);

ALTER TABLE `newsletterempfaenger`
  ADD CONSTRAINT `newsletterempfaengernewsletter` FOREIGN KEY (`newsletter`) REFERENCES `newslettertypen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

CREATE TABLE `gremiennewsletter` (
 `gruppe` bigint(255) unsigned DEFAULT NULL,
 `newsletter` bigint(255) unsigned DEFAULT NULL,
 KEY `gruppengremiennewsletter` (`gruppe`),
 KEY `newslettergremiennewsletter` (`newsletter`),
 CONSTRAINT `newslettergremiennewsletter` FOREIGN KEY (`newsletter`) REFERENCES `newslettertypen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
 CONSTRAINT `gruppengremiennewsletter` FOREIGN KEY (`gruppe`) REFERENCES `gremien` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `fachschaftennewsletter` (
 `gruppe` bigint(255) unsigned DEFAULT NULL,
 `newsletter` bigint(255) unsigned DEFAULT NULL,
 KEY `gruppenfachschaftennewsletter` (`gruppe`),
 KEY `newsletterfachschaftennewsletter` (`newsletter`),
 CONSTRAINT `newsletterfachschaftennewsletter` FOREIGN KEY (`newsletter`) REFERENCES `newslettertypen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
 CONSTRAINT `gruppenfachschaftennewsletter` FOREIGN KEY (`gruppe`) REFERENCES `fachschaften` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `klassennewsletter` (
 `gruppe` bigint(255) unsigned DEFAULT NULL,
 `newsletter` bigint(255) unsigned DEFAULT NULL,
 KEY `gruppenklassennewsletter` (`gruppe`),
 KEY `newsletterklassennewsletter` (`newsletter`),
 CONSTRAINT `newsletterklassennewsletter` FOREIGN KEY (`newsletter`) REFERENCES `newslettertypen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
 CONSTRAINT `gruppenklassennewsletter` FOREIGN KEY (`gruppe`) REFERENCES `klassen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `kursenewsletter` (
 `gruppe` bigint(255) unsigned DEFAULT NULL,
 `newsletter` bigint(255) unsigned DEFAULT NULL,
 KEY `gruppenkursenewsletter` (`gruppe`),
 KEY `newsletterkursenewsletter` (`newsletter`),
 CONSTRAINT `newsletterkursenewsletter` FOREIGN KEY (`newsletter`) REFERENCES `newslettertypen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
 CONSTRAINT `gruppenkursenewsletter` FOREIGN KEY (`gruppe`) REFERENCES `kurse` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `stufennewsletter` (
 `gruppe` bigint(255) unsigned DEFAULT NULL,
 `newsletter` bigint(255) unsigned DEFAULT NULL,
 KEY `gruppenstufennewsletter` (`gruppe`),
 KEY `newsletterstufennewsletter` (`newsletter`),
 CONSTRAINT `newsletterstufennewsletter` FOREIGN KEY (`newsletter`) REFERENCES `newslettertypen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
 CONSTRAINT `gruppenstufennewsletter` FOREIGN KEY (`gruppe`) REFERENCES `stufen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `arbeitsgemeinschaftennewsletter` (
 `gruppe` bigint(255) unsigned DEFAULT NULL,
 `newsletter` bigint(255) unsigned DEFAULT NULL,
 KEY `gruppenarbeitsgemeinschaftennewsletter` (`gruppe`),
 KEY `newsletterarbeitsgemeinschaftennewsletter` (`newsletter`),
 CONSTRAINT `newsletterarbeitsgemeinschaftennewsletter` FOREIGN KEY (`newsletter`) REFERENCES `newslettertypen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
 CONSTRAINT `gruppenarbeitsgemeinschaftennewsletter` FOREIGN KEY (`gruppe`) REFERENCES `arbeitsgemeinschaften` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `arbeitskreisenewsletter` (
 `gruppe` bigint(255) unsigned DEFAULT NULL,
 `newsletter` bigint(255) unsigned DEFAULT NULL,
 KEY `gruppenarbeitskreisenewsletter` (`gruppe`),
 KEY `newsletterarbeitskreisenewsletter` (`newsletter`),
 CONSTRAINT `newsletterarbeitskreisenewsletter` FOREIGN KEY (`newsletter`) REFERENCES `newslettertypen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
 CONSTRAINT `gruppenarbeitskreisenewsletter` FOREIGN KEY (`gruppe`) REFERENCES `arbeitskreise` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `fahrtennewsletter` (
 `gruppe` bigint(255) unsigned DEFAULT NULL,
 `newsletter` bigint(255) unsigned DEFAULT NULL,
 KEY `gruppenfahrtennewsletter` (`gruppe`),
 KEY `newsletterfahrtennewsletter` (`newsletter`),
 CONSTRAINT `newsletterfahrtennewsletter` FOREIGN KEY (`newsletter`) REFERENCES `newslettertypen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
 CONSTRAINT `gruppenfahrtennewsletter` FOREIGN KEY (`gruppe`) REFERENCES `fahrten` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `wettbewerbenewsletter` (
 `gruppe` bigint(255) unsigned DEFAULT NULL,
 `newsletter` bigint(255) unsigned DEFAULT NULL,
 KEY `gruppenwettbewerbenewsletter` (`gruppe`),
 KEY `newsletterwettbewerbenewsletter` (`newsletter`),
 CONSTRAINT `newsletterwettbewerbenewsletter` FOREIGN KEY (`newsletter`) REFERENCES `newslettertypen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
 CONSTRAINT `gruppenwettbewerbenewsletter` FOREIGN KEY (`gruppe`) REFERENCES `wettbewerbe` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `ereignissenewsletter` (
 `gruppe` bigint(255) unsigned DEFAULT NULL,
 `newsletter` bigint(255) unsigned DEFAULT NULL,
 KEY `gruppenereignissenewsletter` (`gruppe`),
 KEY `newsletterereignissenewsletter` (`newsletter`),
 CONSTRAINT `newsletterereignissenewsletter` FOREIGN KEY (`newsletter`) REFERENCES `newslettertypen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
 CONSTRAINT `gruppenereignissenewsletter` FOREIGN KEY (`gruppe`) REFERENCES `ereignisse` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `sonstigegruppennewsletter` (
 `gruppe` bigint(255) unsigned DEFAULT NULL,
 `newsletter` bigint(255) unsigned DEFAULT NULL,
 KEY `gruppensonstigegruppennewsletter` (`gruppe`),
 KEY `newslettersonstigegruppennewsletter` (`newsletter`),
 CONSTRAINT `newslettersonstigegruppennewsletter` FOREIGN KEY (`newsletter`) REFERENCES `newslettertypen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
 CONSTRAINT `gruppensonstigegruppennewsletter` FOREIGN KEY (`gruppe`) REFERENCES `sonstigegruppen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE `auszeichnungen` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `bild` varchar(3000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bezeichnung` varchar(5000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `link` varchar(3000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ziel` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `reihenfolge` bigint(255) UNSIGNED DEFAULT NULL,
  `idvon` bigint(255) UNSIGNED NOT NULL,
  `idzeit` bigint(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `auszeichnungen` ADD `aktiv` TINYINT(1) UNSIGNED DEFAULT NULL AFTER `reihenfolge`;

ALTER TABLE `auszeichnungen`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `galerienbilder` CHANGE `id` `id` BIGINT(255) UNSIGNED NOT NULL, CHANGE `idvon` `idvon` BIGINT(255) UNSIGNED NULL, CHANGE `idzeit` `idzeit` BIGINT(255) UNSIGNED NULL;

ALTER TABLE `galerien` CHANGE `idvon` `idvon` BIGINT(255) UNSIGNED NULL, CHANGE `idzeit` `idzeit` BIGINT(255) UNSIGNED NULL;
ALTER TABLE `voranmeldung_eltern` CHANGE `idvon` `idvon` BIGINT(255) NULL, CHANGE `idzeit` `idzeit` BIGINT(255) NULL;

ALTER TABLE `auffaelliges` CHANGE `id` `id` BIGINT(11) UNSIGNED NOT NULL, CHANGE `typ` `typ` INT(1) UNSIGNED NULL DEFAULT NULL, CHANGE `aktion` `aktion` VARBINARY(5000) NULL DEFAULT NULL, CHANGE `eingaben` `eingaben` VARBINARY(5000) NULL DEFAULT NULL, CHANGE `details` `details` VARBINARY(5000) NULL DEFAULT NULL, CHANGE `notizen` `notizen` VARBINARY(5000) NULL DEFAULT NULL, CHANGE `zeitstempel` `zeitstempel` BIGINT(255) UNSIGNED NULL DEFAULT NULL, CHANGE `status` `status` INT(11) UNSIGNED NULL DEFAULT NULL, CHANGE `idvon` `idvon` BIGINT(255) UNSIGNED NULL, CHANGE `idzeit` `idzeit` BIGINT(255) UNSIGNED NULL;
ALTER TABLE `auffaelliges` CHANGE `ursacher` `ursacher` BIGINT(255) UNSIGNED NULL DEFAULT NULL;






DROP TABLE emoticons;
DROP TABLE umarmungen;

CREATE TABLE `gfs` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `person` bigint(255) UNSIGNED DEFAULT NULL,
  `kurs` bigint(255) UNSIGNED DEFAULT NULL,
  `status` tinyint(1) UNSIGNED DEFAULT NULL,
  `gehalten` bigint(255) UNSIGNED NOT NULL,
  `idvon` bigint(255) UNSIGNED NOT NULL,
  `idzeit` bigint(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `gfs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gfspersonpersonen` (`person`),
  ADD KEY `gfskurskurse` (`kurs`);

ALTER TABLE `gfs`
  ADD CONSTRAINT `gfskurskurse` FOREIGN KEY (`kurs`) REFERENCES `kurse` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `gfspersonpersonen` FOREIGN KEY (`person`) REFERENCES `personen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `notifikationen` CHANGE `idvon` `idvon` BIGINT(255) UNSIGNED NULL, CHANGE `idzeit` `idzeit` BIGINT(255) UNSIGNED NULL;

CREATE TABLE `notfallzustand` (
  `lehrer` bigint(255) UNSIGNED NOT NULL,
  `schueler` bigint(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `notfallzustand`
  ADD PRIMARY KEY (`lehrer`,`schueler`),
  ADD KEY `notfallzustandschueler` (`schueler`);

ALTER TABLE `notfallzustand`
  ADD CONSTRAINT `notfallzustandlehrer` FOREIGN KEY (`lehrer`) REFERENCES `lehrer` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `notfallzustandschueler` FOREIGN KEY (`schueler`) REFERENCES `personen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `kontaktformulare` ADD `ansichtalt` VARCHAR(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL AFTER `anhangneu`, ADD `ansichtaktuell` VARCHAR(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL AFTER `ansichtalt`, ADD `ansichtneu` VARCHAR(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL AFTER `ansichtaktuell`;

ALTER TABLE `voranmeldung_schueler` ADD `geimpft` TINYINT(1) UNSIGNED NULL AFTER `kuenftigesprofil`;
ALTER TABLE `voranmeldung_schueler` CHANGE `geimpft` `geimpft` VARBINARY(50) NULL DEFAULT NULL;

ALTER TABLE `gremienblogeintraegeintern` CHANGE `datum` `datum` BIGINT(255) UNSIGNED NULL DEFAULT NULL;
ALTER TABLE `fachschaftenblogeintraegeintern` CHANGE `datum` `datum` BIGINT(255) UNSIGNED NULL DEFAULT NULL;
ALTER TABLE `klassenblogeintraegeintern` CHANGE `datum` `datum` BIGINT(255) UNSIGNED NULL DEFAULT NULL;
ALTER TABLE `kurseblogeintraegeintern` CHANGE `datum` `datum` BIGINT(255) UNSIGNED NULL DEFAULT NULL;
ALTER TABLE `stufenblogeintraegeintern` CHANGE `datum` `datum` BIGINT(255) UNSIGNED NULL DEFAULT NULL;
ALTER TABLE `arbeitsgemeinschaftenblogeintraegeintern` CHANGE `datum` `datum` BIGINT(255) UNSIGNED NULL DEFAULT NULL;
ALTER TABLE `arbeitskreiseblogeintraegeintern` CHANGE `datum` `datum` BIGINT(255) UNSIGNED NULL DEFAULT NULL;
ALTER TABLE `fahrtenblogeintraegeintern` CHANGE `datum` `datum` BIGINT(255) UNSIGNED NULL DEFAULT NULL;
ALTER TABLE `wettbewerbeblogeintraegeintern` CHANGE `datum` `datum` BIGINT(255) UNSIGNED NULL DEFAULT NULL;
ALTER TABLE `ereignisseblogeintraegeintern` CHANGE `datum` `datum` BIGINT(255) UNSIGNED NULL DEFAULT NULL;
ALTER TABLE `sonstigegruppenblogeintraegeintern` CHANGE `datum` `datum` BIGINT(255) UNSIGNED NULL DEFAULT NULL;

ALTER TABLE `gremienchat` CHANGE `datum` `datum` BIGINT(255) UNSIGNED NULL DEFAULT NULL;
ALTER TABLE `fachschaftenchat` CHANGE `datum` `datum` BIGINT(255) UNSIGNED NULL DEFAULT NULL;
ALTER TABLE `klassenchat` CHANGE `datum` `datum` BIGINT(255) UNSIGNED NULL DEFAULT NULL;
ALTER TABLE `kursechat` CHANGE `datum` `datum` BIGINT(255) UNSIGNED NULL DEFAULT NULL;
ALTER TABLE `stufenchat` CHANGE `datum` `datum` BIGINT(255) UNSIGNED NULL DEFAULT NULL;
ALTER TABLE `arbeitsgemeinschaftenchat` CHANGE `datum` `datum` BIGINT(255) UNSIGNED NULL DEFAULT NULL;
ALTER TABLE `arbeitskreisechat` CHANGE `datum` `datum` BIGINT(255) UNSIGNED NULL DEFAULT NULL;
ALTER TABLE `fahrtenchat` CHANGE `datum` `datum` BIGINT(255) UNSIGNED NULL DEFAULT NULL;
ALTER TABLE `wettbewerbechat` CHANGE `datum` `datum` BIGINT(255) UNSIGNED NULL DEFAULT NULL;
ALTER TABLE `ereignissechat` CHANGE `datum` `datum` BIGINT(255) UNSIGNED NULL DEFAULT NULL;
ALTER TABLE `sonstigegruppenchat` CHANGE `datum` `datum` BIGINT(255) UNSIGNED NULL DEFAULT NULL;

ALTER TABLE `gremientermineintern` CHANGE `beginn` `beginn` BIGINT(255) UNSIGNED NULL DEFAULT NULL;
ALTER TABLE `gremientermineintern` CHANGE `ende` `ende` BIGINT(255) UNSIGNED NULL DEFAULT NULL;
ALTER TABLE `fachschaftentermineintern` CHANGE `beginn` `beginn` BIGINT(255) UNSIGNED NULL DEFAULT NULL;
ALTER TABLE `fachschaftentermineintern` CHANGE `ende` `ende` BIGINT(255) UNSIGNED NULL DEFAULT NULL;
ALTER TABLE `klassentermineintern` CHANGE `beginn` `beginn` BIGINT(255) UNSIGNED NULL DEFAULT NULL;
ALTER TABLE `klassentermineintern` CHANGE `ende` `ende` BIGINT(255) UNSIGNED NULL DEFAULT NULL;
ALTER TABLE `kursetermineintern` CHANGE `beginn` `beginn` BIGINT(255) UNSIGNED NULL DEFAULT NULL;
ALTER TABLE `kursetermineintern` CHANGE `ende` `ende` BIGINT(255) UNSIGNED NULL DEFAULT NULL;
ALTER TABLE `stufentermineintern` CHANGE `beginn` `beginn` BIGINT(255) UNSIGNED NULL DEFAULT NULL;
ALTER TABLE `stufentermineintern` CHANGE `ende` `ende` BIGINT(255) UNSIGNED NULL DEFAULT NULL;
ALTER TABLE `arbeitsgemeinschaftentermineintern` CHANGE `beginn` `beginn` BIGINT(255) UNSIGNED NULL DEFAULT NULL;
ALTER TABLE `arbeitsgemeinschaftentermineintern` CHANGE `ende` `ende` BIGINT(255) UNSIGNED NULL DEFAULT NULL;
ALTER TABLE `arbeitskreisetermineintern` CHANGE `beginn` `beginn` BIGINT(255) UNSIGNED NULL DEFAULT NULL;
ALTER TABLE `arbeitskreisetermineintern` CHANGE `ende` `ende` BIGINT(255) UNSIGNED NULL DEFAULT NULL;
ALTER TABLE `fahrtentermineintern` CHANGE `beginn` `beginn` BIGINT(255) UNSIGNED NULL DEFAULT NULL;
ALTER TABLE `fahrtentermineintern` CHANGE `ende` `ende` BIGINT(255) UNSIGNED NULL DEFAULT NULL;
ALTER TABLE `wettbewerbetermineintern` CHANGE `beginn` `beginn` BIGINT(255) UNSIGNED NULL DEFAULT NULL;
ALTER TABLE `wettbewerbetermineintern` CHANGE `ende` `ende` BIGINT(255) UNSIGNED NULL DEFAULT NULL;
ALTER TABLE `ereignissetermineintern` CHANGE `beginn` `beginn` BIGINT(255) UNSIGNED NULL DEFAULT NULL;
ALTER TABLE `ereignissetermineintern` CHANGE `ende` `ende` BIGINT(255) UNSIGNED NULL DEFAULT NULL;
ALTER TABLE `sonstigegruppentermineintern` CHANGE `beginn` `beginn` BIGINT(255) UNSIGNED NULL DEFAULT NULL;
ALTER TABLE `sonstigegruppentermineintern` CHANGE `ende` `ende` BIGINT(255) UNSIGNED NULL DEFAULT NULL;

ALTER TABLE `blogeintraege` CHANGE `datum` `datum` BIGINT(255) UNSIGNED NULL DEFAULT NULL;
ALTER TABLE `termine` CHANGE `beginn` `beginn` BIGINT(255) UNSIGNED NULL DEFAULT NULL, CHANGE `ende` `ende` BIGINT(255) UNSIGNED NULL DEFAULT NULL;
ALTER TABLE `ferien` CHANGE `beginn` `beginn` BIGINT(255) UNSIGNED NULL DEFAULT NULL, CHANGE `ende` `ende` BIGINT(255) UNSIGNED NULL DEFAULT NULL;
ALTER TABLE `galerien` CHANGE `datum` `datum` BIGINT(255) UNSIGNED NULL DEFAULT NULL;
ALTER TABLE `leihenbuchen` CHANGE `beginn` `beginn` BIGINT(255) UNSIGNED NULL DEFAULT NULL, CHANGE `ende` `ende` BIGINT(255) UNSIGNED NULL DEFAULT NULL;
ALTER TABLE `raeumebuchen` CHANGE `beginn` `beginn` BIGINT(255) UNSIGNED NULL DEFAULT NULL, CHANGE `ende` `ende` BIGINT(255) UNSIGNED NULL DEFAULT NULL;
ALTER TABLE `notifikationen` CHANGE `zeit` `zeit` BIGINT(255) UNSIGNED NULL DEFAULT NULL;
ALTER TABLE `pinnwandanschlag` CHANGE `beginn` `beginn` BIGINT(255) UNSIGNED NULL DEFAULT NULL, CHANGE `ende` `ende` BIGINT(255) UNSIGNED NULL DEFAULT NULL;
ALTER TABLE `schuljahre` CHANGE `beginn` `beginn` BIGINT(255) UNSIGNED NULL DEFAULT NULL, CHANGE `ende` `ende` BIGINT(255) UNSIGNED NULL DEFAULT NULL;
ALTER TABLE `zeitraeume` CHANGE `beginn` `beginn` BIGINT(255) UNSIGNED NULL DEFAULT NULL, CHANGE `ende` `ende` BIGINT(255) UNSIGNED NULL DEFAULT NULL;

ALTER TABLE `kontaktformulareempfaenger` DROP `namealt`;
ALTER TABLE `kontaktformulareempfaenger` DROP `nameneu`;
ALTER TABLE `kontaktformulareempfaenger` DROP `beschreibungalt`;
ALTER TABLE `kontaktformulareempfaenger` DROP `beschreibungneu`;
ALTER TABLE `kontaktformulareempfaenger` DROP `mailalt`;
ALTER TABLE `kontaktformulareempfaenger` DROP `mailneu`;
ALTER TABLE `kontaktformulareempfaenger` CHANGE `nameaktuell` `name` VARCHAR(2000) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `kontaktformulareempfaenger` CHANGE `beschreibungaktuell` `beschreibung` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `kontaktformulareempfaenger` CHANGE `mailaktuell` `mail` VARCHAR(2000) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;

CREATE TABLE `cms_schulhof`.`diashows` ( `id` BIGINT(255) UNSIGNED NOT NULL , `spalte` BIGINT(255) UNSIGNED NOT NULL , `position` BIGINT(255) UNSIGNED NOT NULL , `aktiv` VARCHAR(1) NOT NULL , `titelalt` VARCHAR(5000) NOT NULL , `titelaktuell` VARCHAR(5000) NOT NULL , `titelneu` VARCHAR(5000) NOT NULL , `idvon` BIGINT(255) UNSIGNED NULL DEFAULT NULL , `idzeit` BIGINT(255) UNSIGNED NULL DEFAULT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;
ALTER TABLE `diashows` CHANGE `spalte` `spalte` BIGINT(255) UNSIGNED NULL, CHANGE `position` `position` BIGINT(255) UNSIGNED NULL, CHANGE `aktiv` `aktiv` VARCHAR(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL, CHANGE `titelalt` `titelalt` VARCHAR(5000) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL, CHANGE `titelaktuell` `titelaktuell` VARCHAR(5000) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL, CHANGE `titelneu` `titelneu` VARCHAR(5000) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL;
CREATE TABLE `cms_schulhof`.`diashowbilder` ( `id` BIGINT(255) UNSIGNED NOT NULL , `diashow` BIGINT(255) UNSIGNED NOT NULL , `pfadalt` TEXT NOT NULL , `pfadaktuell` TEXT NOT NULL , `pfadneu` TEXT NOT NULL , `beschreibungalt` TEXT NOT NULL , `beschreibungaktuell` TEXT NOT NULL , `beschreibungneu` TEXT NOT NULL , `idvon` BIGINT(255) UNSIGNED NULL DEFAULT NULL , `idzeit` BIGINT(255) UNSIGNED NULL DEFAULT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;

ALTER TABLE `diashows` ADD CONSTRAINT `diashowsspalten` FOREIGN KEY (`spalte`) REFERENCES `spalten`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `diashowbilder` ADD CONSTRAINT `diashowbilderdiashow` FOREIGN KEY (`diashow`) REFERENCES `diashows`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `diashows` CHANGE `spalte` `spalte` BIGINT(255) UNSIGNED NULL, CHANGE `position` `position` BIGINT(255) UNSIGNED NULL, CHANGE `aktiv` `aktiv` VARCHAR(5000) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL, CHANGE `titelalt` `titelalt` VARCHAR(5000) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL, CHANGE `titelaktuell` `titelaktuell` VARCHAR(5000) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL, CHANGE `titelneu` `titelneu` VARCHAR(5000) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL;

ALTER TABLE `diashowbilder` CHANGE `diashow` `diashow` BIGINT(255) UNSIGNED NULL, CHANGE `pfadalt` `pfadalt` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL, CHANGE `pfadaktuell` `pfadaktuell` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL, CHANGE `pfadneu` `pfadneu` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL, CHANGE `beschreibungalt` `beschreibungalt` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL, CHANGE `beschreibungaktuell` `beschreibungaktuell` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL, CHANGE `beschreibungneu` `beschreibungneu` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL;






-- LEHRERDATENBANK

CREATE TABLE `ausplanungstufen` (
  `id` bigint(20) NOT NULL,
  `stufe` bigint(20) DEFAULT NULL,
  `grund` varchar(2) COLLATE utf8_unicode_ci DEFAULT NULL,
  `von` bigint(20) DEFAULT NULL,
  `bis` bigint(20) DEFAULT NULL,
  `idvon` bigint(20) DEFAULT NULL,
  `idzeit` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `ausplanungstufen`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `ausplanungklassen` ADD `zusatz` VARBINARY(2000) NULL DEFAULT NULL AFTER `bis`;
ALTER TABLE `ausplanungstufen` ADD `zusatz` VARBINARY(2000) NULL DEFAULT NULL AFTER `bis`;
ALTER TABLE `ausplanungraeume` ADD `zusatz` VARBINARY(2000) NULL DEFAULT NULL AFTER `bis`;
ALTER TABLE `ausplanunglehrer` ADD `zusatz` VARBINARY(2000) NULL DEFAULT NULL AFTER `bis`;

CREATE TABLE `fehlzeiten` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `eintrag` bigint(255) UNSIGNED DEFAULT NULL,
  `person` bigint(255) UNSIGNED DEFAULT NULL,
  `von` bigint(255) UNSIGNED DEFAULT NULL,
  `bis` bigint(255) UNSIGNED DEFAULT NULL,
  `bemerkung` blob DEFAULT NULL,
  `entschuldigt` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `idvon` bigint(255) UNSIGNED NULL,
  `idzeit` bigint(255) UNSIGNED NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `lobtadel` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `eintrag` bigint(255) UNSIGNED DEFAULT NULL,
  `person` bigint(255) UNSIGNED DEFAULT NULL,
  `art` varchar(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `charakter` varchar(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bemerkung` blob DEFAULT NULL,
  `idvon` bigint(255) UNSIGNED NULL,
  `idzeit` bigint(255) UNSIGNED NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `diashows` ADD CONSTRAINT `diashowsspalten` FOREIGN KEY (`spalte`) REFERENCES `spalten`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `diashowbilder` ADD CONSTRAINT `diashowbilderdiashow` FOREIGN KEY (`diashow`) REFERENCES `diashows`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `gremienchat` ADD `fertig` INT(1) NOT NULL AFTER `loeschstatus`;ALTER TABLE `fachschaftenchat` ADD `fertig` INT(1) NOT NULL AFTER `loeschstatus`;ALTER TABLE `klassenchat` ADD `fertig` INT(1) NOT NULL AFTER `loeschstatus`;ALTER TABLE `kursechat` ADD `fertig` INT(1) NOT NULL AFTER `loeschstatus`;ALTER TABLE `stufenchat` ADD `fertig` INT(1) NOT NULL AFTER `loeschstatus`;ALTER TABLE `arbeitsgemeinschaftenchat` ADD `fertig` INT(1) NOT NULL AFTER `loeschstatus`;ALTER TABLE `arbeitskreisechat` ADD `fertig` INT(1) NOT NULL AFTER `loeschstatus`;ALTER TABLE `fahrtenchat` ADD `fertig` INT(1) NOT NULL AFTER `loeschstatus`;ALTER TABLE `wettbewerbechat` ADD `fertig` INT(1) NOT NULL AFTER `loeschstatus`;ALTER TABLE `ereignissechat` ADD `fertig` INT(1) NOT NULL AFTER `loeschstatus`;ALTER TABLE `sonstigegruppenchat` ADD `fertig` INT(1) NOT NULL AFTER `loeschstatus`;
DROP TABLE `rechtzuordnung`;
CREATE TABLE `cms_schulhof`.`rechtezuordnung` ( `person` BIGINT(255) UNSIGNED NOT NULL , `recht` VARBINARY(5000) NOT NULL) ENGINE = InnoDB;
ALTER TABLE `rechtezuordnung` ADD CONSTRAINT `rechtezuordnungperson` FOREIGN KEY (`person`) REFERENCES `personen`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;

DROP TABLE `rollenrechte`;
CREATE TABLE `cms_schulhof`.`rollenrechte` ( `rolle` BIGINT(255) UNSIGNED NOT NULL , `recht` VARBINARY(5000) NOT NULL) ENGINE = InnoDB;
ALTER TABLE `rollenrechte` ADD CONSTRAINT `rollerechterolle` FOREIGN KEY (`rolle`) REFERENCES `rollen`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `rollen` DROP `personenart`;

CREATE TABLE `cms_schulhof`.`bedingterechte` ( `recht` VARBINARY(5000) NOT NULL , `bedingung` VARBINARY(5000) NOT NULL ) ENGINE = InnoDB;

CREATE TABLE `cms_schulhof`.`bedingterollen` ( `rolle` BIGINT(255) UNSIGNED NOT NULL, `bedingung` VARBINARY(5000) NOT NULL ) ENGINE = InnoDB;
ALTER TABLE `bedingterollen` ADD CONSTRAINT `bedingterollenrolle` FOREIGN KEY (`rolle`) REFERENCES `rollen`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;
CREATE TABLE `tagebuch` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `inhalt` longblob DEFAULT NULL,
  `hausaufgabe` longblob DEFAULT NULL,
  `freigabe` tinyint(1) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `fehlzeiten`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fehlzeiteneintragtagebuch` (`eintrag`);

ALTER TABLE `lobtadel`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lobtadeleintragtagebuch` (`eintrag`);

ALTER TABLE `tagebuch`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `fehlzeiten`
  ADD CONSTRAINT `fehlzeiteneintragtagebuch` FOREIGN KEY (`eintrag`) REFERENCES `tagebuch` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `lobtadel`
  ADD CONSTRAINT `lobtadeleintragtagebuch` FOREIGN KEY (`eintrag`) REFERENCES `tagebuch` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
