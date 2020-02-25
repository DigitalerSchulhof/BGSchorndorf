<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['signatur'])) {$signatur = $_POST['signatur'];} else {$signatur = '';}

if (cms_angemeldet()) {

	$id = $_SESSION['BENUTZERID'];

	$dbs = cms_verbinden('s');
	$sql = $dbs->prepare("UPDATE personen_signaturen SET signatur = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') WHERE person = ?");
	$sql->bind_param("si", $signatur, $id);
	$sql->execute();
	cms_trennen($dbs);
	echo "ERFOLG";
}
else {
	echo "BERECHTIGUNG";
}
?>
