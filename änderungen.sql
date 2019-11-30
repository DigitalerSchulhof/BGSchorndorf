-- Änderungen sind in GitHub nachverfolgbar
--
<<<<<<< ours
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

-- <>
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
=======
-- Commit lässt sich mit GitHub "History" herausfinden
-- <SQL Änderungen>

ALTER TABLE `gremienchat` ADD `fertig` INT(1) NOT NULL AFTER `loeschstatus`;ALTER TABLE `fachschaftenchat` ADD `fertig` INT(1) NOT NULL AFTER `loeschstatus`;ALTER TABLE `klassenchat` ADD `fertig` INT(1) NOT NULL AFTER `loeschstatus`;ALTER TABLE `kursechat` ADD `fertig` INT(1) NOT NULL AFTER `loeschstatus`;ALTER TABLE `stufenchat` ADD `fertig` INT(1) NOT NULL AFTER `loeschstatus`;ALTER TABLE `arbeitsgemeinschaftenchat` ADD `fertig` INT(1) NOT NULL AFTER `loeschstatus`;ALTER TABLE `arbeitskreisechat` ADD `fertig` INT(1) NOT NULL AFTER `loeschstatus`;ALTER TABLE `fahrtenchat` ADD `fertig` INT(1) NOT NULL AFTER `loeschstatus`;ALTER TABLE `wettbewerbechat` ADD `fertig` INT(1) NOT NULL AFTER `loeschstatus`;ALTER TABLE `ereignissechat` ADD `fertig` INT(1) NOT NULL AFTER `loeschstatus`;ALTER TABLE `sonstigegruppenchat` ADD `fertig` INT(1) NOT NULL AFTER `loeschstatus`;
>>>>>>> theirs
