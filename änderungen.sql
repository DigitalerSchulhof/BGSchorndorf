-- Format:
--
-- Commit lässt sich mit GitHub "History" herausfinden
-- <SQL Änderungen>
DROP TABLE `rechtzuordnung`;
CREATE TABLE `cms_schulhof`.`rechtezuordnung` ( `person` BIGINT(255) UNSIGNED NOT NULL , `recht` VARBINARY(5000) NOT NULL) ENGINE = InnoDB;
ALTER TABLE `rechtezuordnung` ADD CONSTRAINT `rechtezuordnungperson` FOREIGN KEY (`person`) REFERENCES `personen`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;

DROP TABLE `rollenrechte`;
CREATE TABLE `cms_schulhof`.`rollenrechte` ( `rolle` BIGINT(255) UNSIGNED NOT NULL , `recht` VARBINARY(5000) NOT NULL) ENGINE = InnoDB;
ALTER TABLE `rechtezuordnung` ADD CONSTRAINT `rollerechterolle` FOREIGN KEY (`rolle`) REFERENCES `rollen`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;
