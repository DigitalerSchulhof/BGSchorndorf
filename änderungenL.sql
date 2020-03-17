-- Änderungen sind in GitHub nachverfolgbar
-- Format:
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
