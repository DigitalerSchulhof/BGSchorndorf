<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
session_start();

postLesen(array("name", "mail", "id", "uid", "code"));
if(!cms_check_name($name) || !cms_check_mail($mail))
  die("FEHLER");
$dbs = cms_verbinden("s");

if (isset($_SESSION['SPAMSCHUTZ_'.$uid])) {$codevergleich = $_SESSION['SPAMSCHUTZ_'.$uid];} else {echo "FEHLER"; exit;}
unset($_SESSION['SPAMSCHUTZ_'.$uid]);

if ($code != $codevergleich) {echo "CODE"; exit;}

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

$sql = "SELECT COUNT(*) FROM newsletterempfaenger WHERE token = AES_ENCRYPT(?, '$CMS_SCHLUESSEL')";
$sql = $dbs->prepare($sql);
$sql->bind_param("s", $token);
$sql->bind_result($anz);

$pool = "abcdefghijklmnopqrstuvwxyz1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ";
do {
  $token = "";
  srand ((double)microtime()*1000000);
  for($i = 0; $i < 64; $i++) {
    $token .= substr($pool,(rand()%(strlen ($pool))), 1);
  }
  $sql->execute();
} while(!$sql->fetch() || $anz);

// Einfügen
$eid = cms_generiere_kleinste_id("newsletterempfaenger", "s", '0');
$sql = "UPDATE newsletterempfaenger SET name = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), email = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), newsletter = ?, token = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') WHERE id = ?";
$sql = $dbs->prepare($sql);
$sql->bind_param("ssisi", $name, $mail, $id, $token, $eid);
$sql->execute();

echo "ERFOLG";
?>
