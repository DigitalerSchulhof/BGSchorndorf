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

ALTER TABLE `tagebuch` ADD `urheber` BIGINT(255) UNSIGNED NULL DEFAULT NULL AFTER `leistungsmessung`; 
