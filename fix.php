<?php
include_once('php/allgemein/funktionen/sql.php');
include_once("php/schulhof/funktionen/config.php");
include_once("php/schulhof/funktionen/generieren.php");
include_once("php/schulhof/funktionen/texttrafo.php");
include_once("php/schulhof/anfragen/verwaltung/gruppen/initial.php");

foreach ($CMS_GRUPPEN AS $g) {
  $gk = cms_textzudb($g);
  echo "CREATE TABLE `{$gk}todoartikel` ( `person` BIGINT(255) UNSIGNED NULL DEFAULT NULL , `blogid` BIGINT(255) UNSIGNED NULL DEFAULT NULL , `terminid` BIGINT(255) UNSIGNED NULL DEFAULT NULL ) ENGINE = InnoDB;
  ALTER TABLE `{$gk}todoartikel` ADD UNIQUE `{$gk}todoartikelindex` (`person`, `blogid`, `terminid`);
  ALTER TABLE `{$gk}todoartikel` ADD CONSTRAINT `{$gk}todoartikelblogblogeintrag` FOREIGN KEY (`blogid`) REFERENCES `{$gk}blogeintraegeintern`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;
  ALTER TABLE `{$gk}todoartikel` ADD CONSTRAINT `{$gk}todoartikeltermintermin` FOREIGN KEY (`terminid`) REFERENCES `{$gk}termineintern`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;<br>";
}
?>
