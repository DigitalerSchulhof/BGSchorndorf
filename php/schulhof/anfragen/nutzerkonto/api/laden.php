<?php
  include_once("../../schulhof/funktionen/texttrafo.php");
  include_once("../../allgemein/funktionen/sql.php");
  include_once("../../schulhof/funktionen/config.php");
  include_once("../../schulhof/funktionen/check.php");
  include_once("../../schulhof/funktionen/generieren.php");
  include_once("../../allgemein/funktionen/brotkrumen.php");
  session_start();

  if (isset($_SESSION['BENUTZERID'])) {$CMS_BENUTZERID = $_SESSION['BENUTZERID'];} else {echo "FEHLER";exit;}

  $dbs = cms_verbinden("s");
  $sql = $dbs->prepare("SELECT AES_DECRYPT(apischluessel, '$CMS_SCHLUESSEL') FROM nutzerkonten WHERE id = ?");
  $sql->bind_param("i", $CMS_BENUTZERID);
  $sql->bind_result($schluessel);
  if(!($sql->execute() && $sql->fetch())) {
    $schluessel = "Ein Fehler ist aufgetreten.";
  }

  echo $schluessel;
?>
