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
ALTER TABLE `tagebuch` ADD `leistungsmessung` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0' AFTER `freigabe`;
ALTER TABLE `tagebuch` ADD `urheber` BIGINT(255) UNSIGNED NULL DEFAULT NULL AFTER `leistungsmessung`;
ALTER TABLE `lobtadel` DROP `charakter`;

ALTER TABLE `fehlzeiten` DROP FOREIGN KEY `fehlzeiteneintragtagebuch`;
ALTER TABLE `fehlzeiten` DROP INDEX `fehlzeiteneintragtagebuch`;
ALTER TABLE `fehlzeiten` DROP `eintrag`;
