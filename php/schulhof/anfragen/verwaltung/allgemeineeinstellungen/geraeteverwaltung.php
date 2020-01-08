<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");

session_start();

// Variablen einlesen, falls übergeben

if (isset($_POST['extexistiert1'])) 				{$extexistiert1 = $_POST['extexistiert1'];} 									else {echo "FEHLER";exit;}
if (isset($_POST['extgeschlecht1'])) 				{$extgeschlecht1 = $_POST['extgeschlecht1'];} 								else {echo "FEHLER";exit;}
if (isset($_POST['exttitel1'])) 						{$exttitel1 = $_POST['exttitel1'];} 													else {echo "FEHLER";exit;}
if (isset($_POST['extvorname1'])) 					{$extvorname1 = $_POST['extvorname1'];} 											else {echo "FEHLER";exit;}
if (isset($_POST['extnachname1'])) 					{$extnachname1 = $_POST['extnachname1'];} 										else {echo "FEHLER";exit;}
if (isset($_POST['extmail1'])) 							{$extmail1 = $_POST['extmail1'];} 														else {echo "FEHLER";exit;}
if (isset($_POST['extexistiert2'])) 				{$extexistiert2 = $_POST['extexistiert2'];} 									else {echo "FEHLER";exit;}
if (isset($_POST['extgeschlecht2'])) 				{$extgeschlecht2 = $_POST['extgeschlecht2'];} 								else {echo "FEHLER";exit;}
if (isset($_POST['exttitel2'])) 						{$exttitel2 = $_POST['exttitel2'];} 													else {echo "FEHLER";exit;}
if (isset($_POST['extvorname2'])) 					{$extvorname2 = $_POST['extvorname2'];} 											else {echo "FEHLER";exit;}
if (isset($_POST['extnachname2'])) 					{$extnachname2 = $_POST['extnachname2'];} 										else {echo "FEHLER";exit;}
if (isset($_POST['extmail2'])) 							{$extmail2 = $_POST['extmail2'];} 														else {echo "FEHLER";exit;}
if (isset($_POST['kennung'])) 							{$kennung = $_POST['kennung'];} 															else {echo "FEHLER";exit;}

cms_rechte_laden();

if (cms_angemeldet() && cms_r("schulhof.verwaltung.einstellungen"))) {
	$fehler = false;

	if (!cms_check_toggle($extexistiert1)) {$fehler = true;}
	if (!cms_check_toggle($extexistiert2)) {$fehler = true;}
	if (!cms_check_titel($kennung)) {$fehler = true;}

	if ($extexistiert1 == 1) {
		if (($extgeschlecht1 != 'm') && ($extgeschlecht1 != 'w') && ($extgeschlecht1 != 'u')) {$fehler = true;}
		if (!cms_check_nametitel($exttitel1)) {$fehler = true;}
		if (!cms_check_name($extvorname1)) {$fehler = true;}
		if (!cms_check_name($extnachname1)) {$fehler = true;}
		if (!cms_check_mail($extmail1)) {$fehler = true;}
	}
	else {
		$extgeschlecht1 = '-';
		$extvorname1 = '';
		$extnachname1 = '';
		$exttitel1 = '';
		$extmail1 = '';
	}

	if ($extexistiert2 == 1) {
		if (($extgeschlecht2 != 'm') && ($extgeschlecht2 != 'w') && ($extgeschlecht2 != 'u')) {$fehler = true;}
		if (!cms_check_nametitel($exttitel2)) {$fehler = true;}
		if (!cms_check_name($extvorname2)) {$fehler = true;}
		if (!cms_check_name($extnachname2)) {$fehler = true;}
		if (!cms_check_mail($extmail2)) {$fehler = true;}
	}
	else {
		$extgeschlecht2 = '-';
		$extvorname2 = '';
		$extnachname2 = '';
		$exttitel2 = '';
		$extmail2 = '';
	}

	if (!$fehler) {
		$dbs = cms_verbinden('s');
		$sql = $dbs->prepare("UPDATE allgemeineeinstellungen SET wert = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') WHERE inhalt = AES_ENCRYPT('Externe Geräteverwaltung1 existiert', '$CMS_SCHLUESSEL')");
	  $sql->bind_param("s", $extexistiert1);
	  $sql->execute();
	  $sql->close();

		$sql = $dbs->prepare("UPDATE allgemeineeinstellungen SET wert = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') WHERE inhalt = AES_ENCRYPT('Externe Geräteverwaltung1 Geschlecht', '$CMS_SCHLUESSEL')");
	  $sql->bind_param("s", $extgeschlecht1);
	  $sql->execute();
	  $sql->close();

		$sql = $dbs->prepare("UPDATE allgemeineeinstellungen SET wert = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') WHERE inhalt = AES_ENCRYPT('Externe Geräteverwaltung1 Vorname', '$CMS_SCHLUESSEL')");
	  $sql->bind_param("s", $extvorname1);
	  $sql->execute();
	  $sql->close();

		$sql = $dbs->prepare("UPDATE allgemeineeinstellungen SET wert = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') WHERE inhalt = AES_ENCRYPT('Externe Geräteverwaltung1 Nachname', '$CMS_SCHLUESSEL')");
	  $sql->bind_param("s", $extnachname1);
	  $sql->execute();
	  $sql->close();

		$sql = $dbs->prepare("UPDATE allgemeineeinstellungen SET wert = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') WHERE inhalt = AES_ENCRYPT('Externe Geräteverwaltung1 Titel', '$CMS_SCHLUESSEL')");
	  $sql->bind_param("s", $exttitel1);
	  $sql->execute();
	  $sql->close();

		$sql = $dbs->prepare("UPDATE allgemeineeinstellungen SET wert = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') WHERE inhalt = AES_ENCRYPT('Externe Geräteverwaltung1 Mail', '$CMS_SCHLUESSEL')");
	  $sql->bind_param("s", $extmail1);
	  $sql->execute();
	  $sql->close();

		$sql = $dbs->prepare("UPDATE allgemeineeinstellungen SET wert = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') WHERE inhalt = AES_ENCRYPT('Externe Geräteverwaltung2 existiert', '$CMS_SCHLUESSEL')");
	  $sql->bind_param("s", $extexistiert2);
	  $sql->execute();
	  $sql->close();

		$sql = $dbs->prepare("UPDATE allgemeineeinstellungen SET wert = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') WHERE inhalt = AES_ENCRYPT('Externe Geräteverwaltung2 Geschlecht', '$CMS_SCHLUESSEL')");
	  $sql->bind_param("s", $extgeschlecht2);
	  $sql->execute();
	  $sql->close();

		$sql = $dbs->prepare("UPDATE allgemeineeinstellungen SET wert = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') WHERE inhalt = AES_ENCRYPT('Externe Geräteverwaltung2 Vorname', '$CMS_SCHLUESSEL')");
	  $sql->bind_param("s", $extvorname2);
	  $sql->execute();
	  $sql->close();

		$sql = $dbs->prepare("UPDATE allgemeineeinstellungen SET wert = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') WHERE inhalt = AES_ENCRYPT('Externe Geräteverwaltung2 Nachname', '$CMS_SCHLUESSEL')");
	  $sql->bind_param("s", $extnachname2);
	  $sql->execute();
	  $sql->close();

		$sql = $dbs->prepare("UPDATE allgemeineeinstellungen SET wert = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') WHERE inhalt = AES_ENCRYPT('Externe Geräteverwaltung2 Titel', '$CMS_SCHLUESSEL')");
	  $sql->bind_param("s", $exttitel2);
	  $sql->execute();
	  $sql->close();

		$sql = $dbs->prepare("UPDATE allgemeineeinstellungen SET wert = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') WHERE inhalt = AES_ENCRYPT('Externe Geräteverwaltung2 Mail', '$CMS_SCHLUESSEL')");
	  $sql->bind_param("s", $extmail2);
	  $sql->execute();
	  $sql->close();

		$sql = $dbs->prepare("UPDATE internedienste SET wert = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') WHERE inhalt = AES_ENCRYPT('Gerätekennung', '$CMS_SCHLUESSEL')");
	  $sql->bind_param("s", $kennung);
	  $sql->execute();
	  $sql->close();

		cms_trennen($dbs);
		echo "ERFOLG";
	}
	else {
		echo "FEHLER";
	}
}
else {
	echo "BERECHTIGUNG";
}
?>
