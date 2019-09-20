-- Format:
--
-- Commit lässt sich mit GitHub "History" herausfinden
-- <SQL Änderungen>

ALTER TABLE `gremienchat` ADD `fertig` INT(1) NOT NULL AFTER `loeschstatus`;ALTER TABLE `fachschaftenchat` ADD `fertig` INT(1) NOT NULL AFTER `loeschstatus`;ALTER TABLE `klassenchat` ADD `fertig` INT(1) NOT NULL AFTER `loeschstatus`;ALTER TABLE `kursechat` ADD `fertig` INT(1) NOT NULL AFTER `loeschstatus`;ALTER TABLE `stufenchat` ADD `fertig` INT(1) NOT NULL AFTER `loeschstatus`;ALTER TABLE `arbeitsgemeinschaftenchat` ADD `fertig` INT(1) NOT NULL AFTER `loeschstatus`;ALTER TABLE `arbeitskreisechat` ADD `fertig` INT(1) NOT NULL AFTER `loeschstatus`;ALTER TABLE `fahrtenchat` ADD `fertig` INT(1) NOT NULL AFTER `loeschstatus`;ALTER TABLE `wettbewerbechat` ADD `fertig` INT(1) NOT NULL AFTER `loeschstatus`;ALTER TABLE `ereignissechat` ADD `fertig` INT(1) NOT NULL AFTER `loeschstatus`;ALTER TABLE `sonstigegruppenchat` ADD `fertig` INT(1) NOT NULL AFTER `loeschstatus`;
