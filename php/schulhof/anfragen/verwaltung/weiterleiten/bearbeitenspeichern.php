<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
include_once("../../schulhof/funktionen/texttrafo.php");

session_start();

postLesen(array("id", "von", "zu"));

if (cms_angemeldet() && cms_r("website.weiterleiten")) {
	$dbs = cms_verbinden("s");

	$sql = $dbs->prepare("SELECT 1 FROM weiterleiten WHERE von = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') AND id != ?");
	$sql->bind_param("si", $von, $id);
	if(!$sql->execute() || $sql->fetch()) {
		die("DOPPELT");
	}
	$sql->close();

	$sql = $dbs->prepare("UPDATE weiterleiten SET von = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), zu = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') WHERE id = ?");
	$sql->bind_param("ssi", $von, $zu, $id);
	$sql->execute();

	echo "ERFOLG";
}
else {
	echo "BERECHTIGUNG";
}
?>
