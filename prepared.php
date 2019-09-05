<div style="white-space: pre">
<?php
  include("php/schulhof/funktionen/generieren.php");
  include("php/schulhof/anfragen/verwaltung/gruppen/initial.php");

  foreach($CMS_GRUPPEN as $g) {
    $gk = cms_textzudb($g);
    echo "ALTER TABLE `".$gk."mitglieder` CHANGE `chatbannvon` `chatbannvon` BIGINT(255) UNSIGNED NOT NULL, CHANGE `chatbannbis` `chatbannbis` BIGINT(255) UNSIGNED NOT NULL, ADD CONSTRAINT `chatbannvon$gk"."mitglieder` FOREIGN KEY (`chatbannvon`) REFERENCES `personen`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;";
  }

?>
</div>
