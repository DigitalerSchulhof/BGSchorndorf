<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['id'])) {$id = $_POST['id'];} else {echo "FEHLER";exit;}
if (isset($_POST['status'])) {$status = $_POST['status'];} else {$status = 0;}
if (isset($_POST['notizen'])) {$notizen = cms_texttrafo_e_db($_POST['notizen']);} else {$notizen = '';}

cms_rechte_laden();

if (cms_angemeldet() && cms_r("technik.fehlermeldungen")) {
	$fehler = false;
  if($id === '')
    $fehler = true;
	if(!cms_check_ganzzahl($id, 0))
		$fehler = true;
	if(!cms_check_ganzzahl($status, -2))
		$fehler = true;
	if (!$fehler) {
		if($status == "" && $notizen == "") {
			$_SESSION["BUGID"] = $id;
			echo "ERFOLG";
			return;
		} elseif($notizen == "") {
			if(!cms_r("technik.fehlermeldungen")) {
				echo "BERECHTIGUNG";
				die();
			}
			$weilreference0 = 0;
			$dbs = cms_verbinden('s');
			if($status == "-1") {
				$sql = $dbs->prepare("DELETE FROM fehlermeldungen WHERE id = ?;");
				$sql->bind_param("i", $id);
			} else {
				$sql = $dbs->prepare("UPDATE fehlermeldungen SET status = ? WHERE id = ?;");
				$sql->bind_param("ii", $status, $id);
			}
			$sql->execute();
			$sql->close();

			echo "ERFOLG";
		} else {
			if(!cms_r("technik.fehlermeldungen")) {
				echo "BERECHTIGUNG";
				die();
			}
			$dbs = cms_verbinden('s');
			$sql = $dbs->prepare("UPDATE fehlermeldungen SET notizen = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') WHERE id = ?;");
			$sql->bind_param("si", $notizen, $id);
			$sql->execute();
			$sql->close();
			echo "ERFOLG";
		}
	}
	else {
		echo "FEHLER";
	}
}
else {
	echo "BERECHTIGUNG";
}
?>
