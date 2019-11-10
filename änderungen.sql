-- Format:
--
-- <Commit Hash, seit dem die SQL notwendig ist>:
-- <SQL Änderungen>

-- <155d38693dea61b63421d896c92c8ace97f2798c>
ALTER TABLE `rythmisierung` CHANGE `jahr` `beginn` BIGINT(255) UNSIGNED NOT NULL;

-- <>
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
