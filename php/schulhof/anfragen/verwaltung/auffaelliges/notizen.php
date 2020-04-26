<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['id'])) {$id = $_POST['id'];} else {echo "FEHLER";exit;}
if (isset($_POST['notizen'])) {$notizen = cms_texttrafo_e_event($_POST['notizen']);} else {$notizen = '';}



if (cms_angemeldet()) {
	$fehler = false;
  if($id === '')
    $fehler = true;
	if(!cms_check_ganzzahl($id, 0))
		$fehler = true;
	if (!$fehler) {
		if(!cms_r("schulhof.verwaltung.nutzerkonten.verstöße.auffälliges")) {
			echo "BERECHTIGUNG";
			die();
		}
		$dbs = cms_verbinden('s');
		$sql = $dbs->prepare("UPDATE auffaelliges SET notizen = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') WHERE id = ?;");
		$sql->bind_param("si", $notizen, $id);
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
