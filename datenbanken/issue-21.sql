ALTER TABLE `termine` ADD `notifikationen` INT(1) NOT NULL AFTER `oeffentlichkeit`;
ALTER TABLE `galerien` ADD `notifikationen` INT(1) NOT NULL AFTER `oeffentlichkeit`;
ALTER TABLE `blogeintraege` ADD `notifikationen` INT(1) NOT NULL AFTER `oeffentlichkeit`;
