<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
include_once("../../schulhof/anfragen/verwaltung/gruppen/initial.php");
include_once("../../schulhof/anfragen/notifikationen/notifikationen.php");
include_once("../../allgemein/funktionen/mail.php");
require_once '../../phpmailer/PHPMailerAutoload.php';
session_start();

// Variablen einlesen, falls übergeben
$zugeordnet = array();
if (isset($_POST['pinnwand']))  {$pinnwand = $_POST['pinnwand'];}   else {echo "FEHLER";exit;}
if (isset($_POST['gruppe'])) 		{$gruppe = $_POST['gruppe'];} 			else {echo "FEHLER";exit;}
if (isset($_POST['gruppenid'])) {$gruppenid = $_POST['gruppenid'];} else {echo "FEHLER";exit;}
if (!cms_check_ganzzahl($gruppenid,0)) {echo "FEHLER"; exit;}
if (!cms_valide_gruppe($gruppe)) {echo "FEHLER";exit;}

$gk = cms_textzudb($gruppe);
$pinnwand = cms_texttrafo_e_db($pinnwand);

$CMS_EINSTELLUNGEN = cms_einstellungen_laden();

$dbs = cms_verbinden('s');
$CMS_GRUPPENRECHTE = cms_gruppenrechte_laden($dbs, $gruppe, $gruppenid);

if (cms_angemeldet() && $CMS_GRUPPENRECHTE['blogeintraege']) {

	// Pinnwand ändern
  $sql = $dbs->prepare("UPDATE $gk SET pinnwand = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') WHERE id = ?");
  $sql->bind_param("si", $pinnwand, $gruppenid);
  $sql->execute();
  $sql->close();

	echo "ERFOLG";
}
else {
	echo "BERECHTIGUNG";
}
cms_trennen($dbs);
?>
