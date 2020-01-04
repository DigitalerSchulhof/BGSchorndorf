<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
include_once("../../allgemein/funktionen/mail.php");
session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['titel'])) 				{$titel = cms_texttrafo_e_db($_POST['titel']);} 		              else {echo "FEHLER";exit;}
if (isset($_POST['beschreibung'])) 	{$beschreibung = cms_texttrafo_e_db($_POST['beschreibung']);} 		else {echo "FEHLER";exit;}
if (isset($_POST['tag'])) 				  {$tag = $_POST['tag'];} 								                          else {echo "FEHLER";exit;}
if (isset($_POST['monat'])) 				{$monat = $_POST['monat'];} 								                      else {echo "FEHLER";exit;}
if (isset($_POST['jahr'])) 			    {$jahr = $_POST['jahr'];} 							                          else {echo "FEHLER";exit;}
if (isset($_POST['std'])) 					{$std = $_POST['std'];} 								                      		else {echo "FEHLER";exit;}
if (isset($_POST['min'])) 			    {$min = $_POST['min'];} 							                          	else {echo "FEHLER";exit;}
if (isset($_POST['zugehoerig'])) 	 	{$zugehoerig = $_POST['zugehoerig'];} 							                          	else {echo "FEHLER";exit;}
if (isset($_SESSION['BENUTZERID'])) {$CMS_BENUTZERID = $_SESSION['BENUTZERID'];} 	                    else {echo "FEHLER";exit;}

if (!cms_check_ganzzahl($CMS_BENUTZERID,0)) {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($tag,1,31))         {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($monat,1,12))       {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($jahr,0))           {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($std,0,23))       	{echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($min,0,59))         {echo "FEHLER"; exit;}
if (!cms_check_titel($titel))                {echo "FEHLER"; exit;}
$raumgeraet = null;
$leihgeraet = null;
if ($zugehoerig != '') {
	if (!preg_match("/^[lr]\|[0-9]+$/", $zugehoerig)) {echo "FEHLER"; exit;}
	else {
		$zugehoerigdaten = explode('|', $zugehoerig);
		if ($zugehoerigdaten[0] == 'r') {$raumgeraet = $zugehoerigdaten[1];}
		else {$leihgeraet = $zugehoerigdaten[1];}

	}
}

$titel = cms_texttrafo_e_db($titel);
$beschreibung = cms_texttrafo_e_db($beschreibung);

cms_rechte_laden();
$CMS_EINSTELLUNGEN = cms_einstellungen_laden();


if (cms_angemeldet() && r("schulhof.technik.hausmeisteraufträge.erteilen")) {
	$fehler = false;

  $jetzt = time();
	$start = mktime(0, 0, 0, date('m', $jetzt), date('d', $jetzt), date('Y', $jetzt));
	$ziel = mktime($std, $min, 0, $monat, $tag, $jahr);
  if ($jetzt-$ziel > 0) {$fehler = true;}

	$dbs = cms_verbinden('s');

	if (!$fehler) {
    $id = cms_generiere_kleinste_id('hausmeisterauftraege');
    $status = 'n';
		// AUFTRAG EINTRAGEN
		$sql = $dbs->prepare("UPDATE hausmeisterauftraege SET status = ?, titel = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), beschreibung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), start = ?, ziel = ?, raumgeraet = ?, leihgeraet = ?, idvon = ? WHERE id = ?");
		$sql->bind_param("sssiiiiii", $status, $titel, $beschreibung, $start, $ziel, $raumgeraet, $leihgeraet, $CMS_BENUTZERID, $id);
	  $sql->execute();
	  $sql->close();

		$jetzt = time();
		// Hausmeister dieses Schuljahres laden
		require_once '../../phpmailer/PHPMailerAutoload.php';
		$sql = $dbs->prepare("SELECT AES_DECRYPT(vorname, '$CMS_SCHLUESSEL'), AES_DECRYPT(nachname, '$CMS_SCHLUESSEL'), AES_DECRYPT(titel, '$CMS_SCHLUESSEL'), AES_DECRYPT(geschlecht, '$CMS_SCHLUESSEL'), AES_DECRYPT(email, '$CMS_SCHLUESSEL') FROM personen JOIN nutzerkonten ON personen.id = nutzerkonten.id WHERE personen.id IN (SELECT person FROM schluesselposition JOIN schuljahre ON schluesselposition.schuljahr = schuljahre.id WHERE (? BETWEEN beginn AND ende) AND position = AES_ENCRYPT('Hausmeister', '$CMS_SCHLUESSEL'))");
		$sql->bind_param("i", $jetzt);
		if ($sql->execute()) {
			$sql->bind_result($vorname, $nachname, $titel, $geschlecht, $mail);
			while ($sql->fetch()) {
				// Mail verschicken
				$betreff = $CMS_SCHULE.' '.$CMS_ORT.' Schulhof - Hausmeisterauftrag';
				$anrede = cms_mail_anrede($titel, $vorname, $nachname, 'x', $geschlecht);
				$text = array();
				for ($i=0; $i<2; $i++) {
					$text[$i] = $anrede.$CMS_MAILZ[$i].$CMS_MAILZ[$i];
					$text[$i] = $text[$i].'Im Schulhof wurde ein neuer Hausmeisterauftrag hinterlegt.'.$CMS_MAILZ[$i].$CMS_MAILZ[$i];
					$text[$i] = $text[$i].$CMS_MAILSIGNATUR[$i];
				}
				cms_mailsenden($anrede, $mail, $betreff, $text[1], $text[0]);
			}
		}
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
