<div style="white-space: pre">
<?php
  include("php/schulhof/funktionen/generieren.php");
  include("php/schulhof/anfragen/verwaltung/gruppen/initial.php");

  foreach($CMS_GRUPPEN as $g) {
    $gk = cms_textzudb($g);
    // echo "ALTER TABLE `$gk"."mitglieder` DROP `chatloeschen`;";
    // echo "\n";
    // echo "ALTER TABLE `$gk"."mitglieder` DROP `chatbannen`;";
    // echo "\n";
    // echo "ALTER TABLE `$gk"."mitglieder` ADD `nachrichtloeschen` INT(1) UNSIGNED NOT NULL AFTER `chatten`;";
    // echo "\n";
    // echo "ALTER TABLE `$gk"."mitglieder` ADD `nutzerstummschalten` INT(1) UNSIGNED NOT NULL AFTER `nachrichtloeschen`;";
    // echo "\n";
    // echo "ALTER TABLE `$gk"."mitglieder` ADD `chatbannbis` INT(255) UNSIGNED NOT NULL AFTER `nutzerstummschalten`;";
    // echo "\n";
    // echo "ALTER TABLE `$gk"."mitglieder` ADD `chatbannvon` INT(255) UNSIGNED NOT NULL AFTER `chatbannbis`;";
    // echo "\n";
    // echo "ALTER TABLE `$gk"."chat` ADD `loeschstatus` INT(1) UNSIGNED NOT NULL AFTER `meldestatus`;";
    // echo "\n";
  }
?>
</div>
