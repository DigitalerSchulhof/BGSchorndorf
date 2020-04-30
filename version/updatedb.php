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

-- 0.7.2

-- 0.7.3
