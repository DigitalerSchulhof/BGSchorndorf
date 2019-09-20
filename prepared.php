<div style="white-space: pre">
<?php
  include("php/schulhof/funktionen/generieren.php");
  include("php/schulhof/anfragen/verwaltung/gruppen/initial.php");

  foreach($CMS_GRUPPEN as $g) {
    $gk = cms_textzudb($g);
    echo "ALTER TABLE `$gk"."chat` ADD `fertig` INT(1) NOT NULL AFTER `loeschstatus`;";
  }

?>
</div>
