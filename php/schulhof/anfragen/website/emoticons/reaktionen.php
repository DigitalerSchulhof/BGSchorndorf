<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");

session_start();

// Variablen einlesen, falls übergeben
postLesen("b");
postLesen("t");
postLesen("g");

$CMS_RECHTE = cms_rechte_laden();
$zugriff = $CMS_RECHTE['Website']['Emoticons verwalten'];

if (cms_angemeldet() && $zugriff) {
	$fehler = false;
  if(!cms_check_toggle($b))
		$fehler = true;
  if(!cms_check_toggle($t))
		$fehler = true;
  if(!cms_check_toggle($g))
		$fehler = true;

	if (!$fehler) {
			$weilreference0 = 0;
			$dbs = cms_verbinden('s');
      $sql = $dbs->prepare("UPDATE allgemeineeinstellungen SET wert = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') WHERE inhalt = AES_ENCRYPT(?, '$CMS_SCHLUESSEL')");
  		$einstellungsname = "Reaktionen auf Blogeinträge";
  		$sql->bind_param("ss", $b, $einstellungsname);
      $sql->execute();
      $einstellungsname = "Reaktionen auf Termine";
  		$sql->bind_param("ss", $t, $einstellungsname);
      $sql->execute();
      $einstellungsname = "Reaktionen auf Galerien";
  		$sql->bind_param("ss", $g, $einstellungsname);
      $sql->execute();
			$sql->close();
			echo "ERFOLG";
	}
	else {
		echo "FEHLER";
	}
}
else {
	echo "BERECHTIGUNG";
}
?>
