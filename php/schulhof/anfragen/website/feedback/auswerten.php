<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['id'])) {$id = $_POST['id'];} else {echo "FEHLER";exit;}
if (isset($_POST['status'])) {$status = $_POST['status'];} else {$status = 0;}

$CMS_RECHTE = cms_rechte_laden();
$zugriff = $CMS_RECHTE['Website']['Feedback sehen'];

if (cms_angemeldet() && $zugriff) {
	$fehler = false;
  if($id === '')
    $fehler = true;
	if(!cms_check_ganzzahl($id, 0))
		$fehler = true;
	if(!cms_check_ganzzahl($status, -1))
		$fehler = true;
	if (!$fehler) {
		if($status == "") {
			$_SESSION["FEEDBACKID"] = $id;
			echo "ERFOLG";
			return;
		} else {
			if(!$CMS_RECHTE['Website']['Feedback verwalten']) {
				echo "BERECHTIGUNG";
				die();
			}
			$weilreference0 = 0;
			$dbs = cms_verbinden('s');
			if($status == "-1") {
				$sql = $dbs->prepare("DELETE FROM feedback WHERE id = ?;");
				$sql->bind_param("i", $id);
			} else {
				$sql = $dbs->prepare("UPDATE feedback SET sichtbar = ? WHERE id = ?;");
				$weilreference0 = 0;
				$sql->bind_param("ii", $weilreference0, $id);
			}
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
