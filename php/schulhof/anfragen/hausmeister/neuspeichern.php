<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['titel'])) 				{$titel = cms_texttrafo_e_db($_POST['titel']);} 		              else {echo "FEHLER";exit;}
if (isset($_POST['beschreibung'])) 	{$beschreibung = cms_texttrafo_e_db($_POST['beschreibung']);} 		else {echo "FEHLER";exit;}
if (isset($_POST['tag'])) 				  {$tag = $_POST['tag'];} 								                          else {echo "FEHLER";exit;}
if (isset($_POST['monat'])) 				{$monat = $_POST['monat'];} 								                      else {echo "FEHLER";exit;}
if (isset($_POST['jahr'])) 			    {$jahr = $_POST['jahr'];} 							                          else {echo "FEHLER";exit;}
if (isset($_SESSION['BENUTZERID'])) {$CMS_BENUTZERID = $_SESSION['BENUTZERID'];} 	                    else {echo "FEHLER";exit;}

if (!cms_check_ganzzahl($CMS_BENUTZERID,0)) {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($tag,1,31))         {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($monat,1,12))       {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($jahr,0))           {echo "FEHLER"; exit;}
if (!cms_check_name($titel))                {echo "FEHLER"; exit;}

$titel = cms_texttrafo_e_db($titel);
$beschreibung = cms_texttrafo_e_db($beschreibung);

$CMS_RECHTE = cms_rechte_laden();
$CMS_EINSTELLUNGEN = cms_einstellungen_laden();

$zugriff = $CMS_RECHTE['Technik']['Hausmeisteraufträge erteilen'];

if (cms_angemeldet() && $zugriff) {
	$fehler = false;

  $jetzt = time();
	$start = mktime(0, 0, 0, date('m', $jetzt), date('d', $jetzt), date('Y', $jetzt));
	$ziel = mktime(0, 0, 0, $monat, $tag, $jahr);
  if ($start-$ziel > 0) {$fehler = true;}

	$dbs = cms_verbinden('s');

	if (!$fehler) {
    $id = cms_generiere_kleinste_id('hausmeisterauftraege');
    $status = 'n';
		// AUFTRAG EINTRAGEN
		$sql = $dbs->prepare("UPDATE hausmeisterauftraege SET status = ?, titel = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), beschreibung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), start = ?, ziel = ?, idvon = ? WHERE id = ?");
		$sql->bind_param("sssiiii", $status, $titel, $beschreibung, $start, $ziel, $CMS_BENUTZERID, $id);
	  $sql->execute();
	  $sql->close();
		echo "ERFOLG";
	}
	else {
		echo "FEHLER";
	}
	cms_trennen($dbs);
}
else {
	echo "BERECHTIGUNG";
}
?>
