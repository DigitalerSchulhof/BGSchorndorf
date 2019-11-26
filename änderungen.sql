-- Format:
--
-- Commit lässt sich mit GitHub "History" herausfinden
-- <SQL Änderungen>
CREATE TABLE `favoritseiten` (
 `person` bigint(255) unsigned NOT NULL,
 `url` varchar(5000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1
ALTER TABLE `favoritseiten` ADD CONSTRAINT `favoritseitenperson` FOREIGN KEY (`person`) REFERENCES `nutzerkonten`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;
