<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['absender'])) 			{$absender = cms_texttrafo_e_db($_POST['absender']);} 						else {echo "FEHLER"; exit;}
if (isset($_POST['host'])) 					{$host = cms_texttrafo_e_db($_POST['host']);} 										else {echo "FEHLER"; exit;}
if (isset($_POST['benutzer'])) 			{$benutzer = cms_texttrafo_e_db($_POST['benutzer']);} 						else {echo "FEHLER"; exit;}
if (isset($_POST['passwort'])) 			{$passwort = cms_texttrafo_e_db($_POST['passwort']);} 						else {echo "FEHLER"; exit;}
if (isset($_POST['smtpauth'])) 			{$smtpauth = cms_texttrafo_e_db($_POST['smtpauth']);} 						else {echo "FEHLER"; exit;}
if (isset($_POST['signaturtext']))	{$signaturtext = cms_texttrafo_e_db($_POST['signaturtext']);} 		else {echo "FEHLER"; exit;}
if (isset($_POST['signaturhtml'])) 	{$signaturhtml = cms_texttrafo_e_db($_POST['signaturhtml']);} 		else {echo "FEHLER"; exit;}
if (!cms_check_mail($absender)) {echo "FEHLER"; exit;}
if (strlen($host) < 3) {echo "FEHLER"; exit;}
if (!cms_check_toggle($smtpauth)) {echo "FEHLER"; exit;}


if (cms_angemeldet() && cms_r("schulhof.verwaltung.schule.mail")) {
	if ($smtpauth == 1) {$smtpauth = 'true';}
	else {$smtpauth = 'false';}

	$dbs = cms_verbinden('s');
	$sql = $dbs->prepare("UPDATE maileinstellungen SET wert = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') WHERE inhalt = AES_ENCRYPT(?, '$CMS_SCHLUESSEL')");
	$inhalt = 'Absender';
	$sql->bind_param('ss', $absender, $inhalt); $sql->execute();
	$inhalt = 'SMTP-Host';
	$sql->bind_param('ss', $host, $inhalt);	$sql->execute();
	$inhalt = 'SMTP-Authentifizierung';
	$sql->bind_param('ss', $smtpauth, $inhalt);	$sql->execute();
	$inhalt = 'Benutzername';
	$sql->bind_param('ss', $benutzer, $inhalt);	$sql->execute();
	$inhalt = 'Passwort';
	$sql->bind_param('ss', $passwort, $inhalt);	$sql->execute();
	$inhalt = 'Signatur Text';
	$sql->bind_param('ss', $signaturtext, $inhalt);	$sql->execute();
	$inhalt = 'Signatur HTML';
	$sql->bind_param('ss', $signaturhtml, $inhalt);	$sql->execute();
	$sql->close();
	cms_trennen($dbs);
	echo "ERFOLG";
}
else {
	echo "BERECHTIGUNG";
}
?>
