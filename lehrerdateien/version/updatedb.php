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

-- 0.8.4

-- 0.10.8
CREATE TABLE `personen` (
  `id` bigint(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `tagebuch` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `inhalt` longblob DEFAULT NULL,
  `hausaufgabe` longblob DEFAULT NULL,
  `freigabe` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `leistungsmessung` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `urheber` bigint(255) UNSIGNED DEFAULT NULL,
  `eintragsdatum` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `fehlzeiten` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `person` bigint(255) UNSIGNED DEFAULT NULL,
  `von` bigint(255) UNSIGNED DEFAULT NULL,
  `bis` bigint(255) UNSIGNED DEFAULT NULL,
  `bemerkung` blob DEFAULT NULL,
  `entschuldigt` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `urheber` bigint(20) UNSIGNED DEFAULT NULL,
  `eintragszeit` bigint(20) UNSIGNED DEFAULT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `lobtadel` (
  `id` bigint(255) UNSIGNED NOT NULL,
  `eintrag` bigint(255) UNSIGNED DEFAULT NULL,
  `person` bigint(255) UNSIGNED DEFAULT NULL,
  `art` varchar(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bemerkung` blob DEFAULT NULL,
  `urheber` bigint(20) UNSIGNED DEFAULT NULL,
  `eintragszeit` bigint(20) UNSIGNED DEFAULT NULL,
  `idvon` bigint(255) UNSIGNED DEFAULT NULL,
  `idzeit` bigint(255) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `personen`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `tagebuch`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tagebuchurheber` (`urheber`);

ALTER TABLE `fehlzeiten`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fzurheberperson` (`urheber`),
  ADD KEY `fzpersonpersonen` (`person`);

ALTER TABLE `lobtadel`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lobtadeleintragtagebuch` (`eintrag`),
  ADD KEY `lturheberperson` (`urheber`),
  ADD KEY `ltpersonpersonen` (`person`);


ALTER TABLE `tagebuch`
  ADD CONSTRAINT `tagebuchurheber` FOREIGN KEY (`urheber`) REFERENCES `personen` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE `lobtadel`
  ADD CONSTRAINT `lobtadeleintragtagebuch` FOREIGN KEY (`eintrag`) REFERENCES `tagebuch` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ltpersonpersonen` FOREIGN KEY (`person`) REFERENCES `personen` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `lturheberperson` FOREIGN KEY (`urheber`) REFERENCES `personen` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE `fehlzeiten`
  ADD CONSTRAINT `fzpersonpersonen` FOREIGN KEY (`person`) REFERENCES `personen` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fzurheberperson` FOREIGN KEY (`urheber`) REFERENCES `personen` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
