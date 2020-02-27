<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");

session_start();
$zuordnung = array(1 => "lehrer", 2 => "schueler", 3 => "verwaltung", 4 => "eltern", 5 => "externe");

foreach($zuordnung as $php) {
	postLesen("rechte".$php);
}

if (cms_angemeldet() && cms_r("schulhof.verwaltung.einstellungen")) {
	$fehler = false;

	$dbs = cms_verbinden('s');

	foreach($zuordnung as $db => $php) {
		$sql = $dbs->prepare("DELETE FROM rollenrechte WHERE rolle = ?;");
		$sql->bind_param("i", $db);
		$sql->execute();
		$sql->close();

		$sql = "INSERT INTO rollenrechte (`rolle`, `recht`) VALUES (?, AES_ENCRYPT(?, '$CMS_SCHLUESSEL'))";
		$sql = $dbs->prepare($sql);
		$sql->bind_param("is", $db, $recht);
		foreach(explode(",", ${"rechte".$php}) as $recht) {
			if($recht != "") {
				$sql->execute();
			}
		}
	}

	if (!$fehler) {
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
