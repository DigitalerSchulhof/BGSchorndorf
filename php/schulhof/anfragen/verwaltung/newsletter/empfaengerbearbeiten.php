<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
session_start();

// Variablen einlesen, falls übergeben
postLesen(array("name", "mail", "id"));
if(!cms_check_name($name) || !cms_check_mail($mail))
  die("FEHLER");

$CMS_RECHTE = cms_rechte_laden();

// Zugriffssteuerung je nach Gruppe
$zugriff = $CMS_RECHTE['Website']['Newsletter Empfänger bearbeiten'];

if (cms_angemeldet() && $zugriff) {
  $dbs = cms_verbinden("s");;
  $sql = "UPDATE newsletterempfaenger SET name = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), email = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') WHERE id = ?";
  $sql = $dbs->prepare($sql);
  $sql->bind_param("ssi", $name, $mail, $id);
  $sql->execute();
	echo "ERFOLG";
}
else {
	echo "BERECHTIGUNG";
}
?>
