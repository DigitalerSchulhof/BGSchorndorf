<?php
  include_once(__DIR__."/../php/allgemein/funktionen/sql.php");
  include_once(__DIR__."/../php/schulhof/funktionen/config.php");
  include_once(__DIR__."/../php/schulhof/funktionen/check.php");
  include_once(__DIR__."/../php/schulhof/funktionen/meldungen.php");
  include_once(__DIR__."/../php/schulhof/funktionen/generieren.php");
  include_once(__DIR__."/../php/schulhof/funktionen/dateisystem.php");
  include_once(__DIR__."/../php/schulhof/anfragen/verwaltung/gruppen/initial.php");

  $ins = [];

  $dbs = cms_verbinden("s");
  foreach($CMS_GRUPPEN as $g) {
    $gk = cms_textzudb($g);
    $sql = $dbs->prepare("SELECT id, AES_DECRYPT(pinnwand, '$CMS_SCHLUESSEL') FROM {$gk} WHERE LENGTH(AES_DECRYPT(pinnwand, '$CMS_SCHLUESSEL')) > 0 AND (SELECT COUNT(*) FROM {$gk}links WHERE gruppe = id) = 0");
    $sql->bind_result($gID, $gPW);
    $sql->execute();
    $sql->store_result();
    while($sql->fetch()) {
      if(preg_match("/https:\/\/elearning(\d)\.bg-schorndorf\.de\/(\D)\/([a-zA-Z]{3}-[a-zA-Z]{3}-[a-zA-Z]{3})/", $gPW, $link) === 1) {
        array_shift($link);
        $ins[] = [$gID, ...$link];
      }
    }
    $sql->close();

    $sql = $dbs->prepare("INSERT INTO {$gk}links (id, gruppe, link, titel, beschreibung) VALUES (?, ?, AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), AES_ENCRYPT('', '$CMS_SCHLUESSEL'), AES_ENCRYPT('', '$CMS_SCHLUESSEL'))");
    $sql->bind_param("iis", $lID, $gID, $link);
    foreach($ins as $i) {
      $lID = cms_generiere_kleinste_id("{$gk}links");
      $gID = $i[0];
      $link = "https://elearning{$i[1]}.bg-schorndorf.de/{$i[2]}/{$i[3]}";
      $sql->execute();
    }
    $sql->close();
  }
?>