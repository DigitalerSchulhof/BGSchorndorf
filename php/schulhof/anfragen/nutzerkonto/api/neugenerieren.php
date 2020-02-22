<?php
  include_once("../../schulhof/funktionen/texttrafo.php");
  include_once("../../allgemein/funktionen/sql.php");
  include_once("../../schulhof/funktionen/config.php");
  include_once("../../schulhof/funktionen/check.php");
  include_once("../../schulhof/funktionen/generieren.php");
  include_once("../../allgemein/funktionen/brotkrumen.php");
  session_start();

  if (isset($_SESSION['BENUTZERID'])) {$CMS_BENUTZERID = $_SESSION['BENUTZERID'];} else {echo "FEHLER";exit;}

  $auswahlzeichen = "1234567890abcdef";
  $schluessel = array();
  while(count($schluessel) < 32) {
    $stelle = rand(1,strlen($auswahlzeichen));
    array_push($schluessel, substr($auswahlzeichen,$stelle-1,1));
  }
  $schluessel = implode('', $schluessel);

  $dbs = cms_verbinden("s");
  $sql = $dbs->prepare("UPDATE nutzerkonten SET apischluessel = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') WHERE id = ?");
  $sql->bind_param("si", $schluessel, $CMS_BENUTZERID);
  if(!$sql->execute()) {
    die("FEHLER");
  }

  echo $schluessel;
?>
