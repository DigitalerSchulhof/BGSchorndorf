<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['id'])) {$id = $_POST['id'];} else {echo "FEHLER";exit;}
if (isset($_POST['status'])) {$status = $_POST['status'];} else {$status = 0;}



if (cms_angemeldet() && cms_r("technik.feedback")) {
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
			if(!cms_r("technik.feedback")) {
				echo "BERECHTIGUNG";
				die();
			}
			$weilreference0 = 0;
			$dbs = cms_verbinden('s');
			if($status == "0") {
				$sql = $dbs->prepare("DELETE FROM feedback WHERE id = ?;");
				$sql->bind_param("i", $id);
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
