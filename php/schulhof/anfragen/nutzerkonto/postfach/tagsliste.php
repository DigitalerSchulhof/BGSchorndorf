<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_SESSION['BENUTZERID'])) {$CMS_BENUTZERID = $_SESSION['BENUTZERID'];} else {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($CMS_BENUTZERID)) {echo "FEHLER"; exit;}

if (cms_angemeldet()) {
	$fehler = false;
	$dbp = cms_verbinden('p');
	$sql = $dbp->prepare("SELECT id, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') FROM posttags_$CMS_BENUTZERID ORDER BY titel ASC;");
	if ($sql->execute()) {
		$sql->bind_result($tid, $ttit);
		while ($sql->fetch()) {
			$tags[] = array($tid, $ttit);
		}
	}
	$sql->close();

	cms_trennen($dbp);
	echo json_encode($tags);
}
else {
	echo "BERECHTIGUNG";
}
?>
