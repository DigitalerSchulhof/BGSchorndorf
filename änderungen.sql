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
