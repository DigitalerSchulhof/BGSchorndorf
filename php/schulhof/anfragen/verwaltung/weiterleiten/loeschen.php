<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
include_once("../../schulhof/funktionen/texttrafo.php");

session_start();

postLesen(array("id"));

if (cms_angemeldet() && cms_r("website.weiterleiten")) {
	$dbs = cms_verbinden("s");

	$sql = $dbs->prepare("DELETE FROM weiterleiten WHERE id = ?");
	$sql->bind_param("i", $id);
	$sql->execute();

	echo "ERFOLG";
}
else {
	echo "BERECHTIGUNG";
}
?>
