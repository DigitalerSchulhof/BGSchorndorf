<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");

postLesen(array("name", "mail", "id"));

if(!cms_check_name($name) || !cms_check_mail($mail))
  die("FEHLER");

$dbs = cms_verbinden("s");

// Newsletter prüfen
$sql = "SELECT * FROM newslettertypen WHERE id = ?";
$sql = $dbs->prepare($sql);
$sql->bind_param("i", $id);
if(!$sql->execute() || !$sql->fetch())
  die("FEHLER");

// Doppelt prüfen
$sql = "SELECT * FROM newsletterempfaenger WHERE email = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') AND newsletter = ?";
$sql = $dbs->prepare($sql);
$sql->bind_param("si", $mail, $id);
if(!$sql->execute() || $sql->fetch())
  die("MAIL");

// Einfügen
$eid = cms_generiere_kleinste_id("newsletterempfaenger", "s", '0');
$sql = "UPDATE newsletterempfaenger SET name = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), email = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), newsletter = ? WHERE id = ?";
$sql = $dbs->prepare($sql);
$sql->bind_param("ssii", $name, $mail, $id, $eid);
$sql->execute();

echo "ERFOLG";
?>
