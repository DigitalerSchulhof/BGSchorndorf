<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
include_once("../../schulhof/funktionen/dateisystem.php");
include_once("../../allgemein/funktionen/mail.php");
include_once("../../schulhof/anfragen/nutzerkonto/postfach/vorbereiten.php");
include_once("../../schulhof/anfragen/verwaltung/gruppen/initial.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['empfaenger'])) {$empfaenger = $_POST['empfaenger'];} else {echo "FEHLER";exit;}
if (isset($_POST['betreff'])) {$betreff = $_POST['betreff'];} else {echo "FEHLER";exit;}
if (isset($_POST['nachricht'])) {$nachricht = $_POST['nachricht'];} else {echo "FEHLER";exit;}
if (isset($_POST['offen'])) {$offen = $_POST['offen'];} else {echo "FEHLER";exit;}
if (isset($_SESSION['BENUTZERART'])) {$CMS_BENUTZERART = $_SESSION['BENUTZERART'];} else {echo "FEHLER";exit;}
if (isset($_SESSION['BENUTZERID'])) {$CMS_BENUTZERID = $_SESSION['BENUTZERID'];} else {echo "FEHLER";exit;}
if (isset($_SESSION['BENUTZERSCHULJAHR'])) {$CMS_BENUTZERSCHULJAHR = $_SESSION['BENUTZERSCHULJAHR'];} else {$CMS_BENUTZERSCHULJAHR = null;}
if (!cms_check_ganzzahl($CMS_BENUTZERID,0)) {echo "FEHLER"; exit;}
if (!cms_check_toggle($offen)) {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($CMS_BENUTZERSCHULJAHR,0) && ($CMS_BENUTZERSCHULJAHR !== null)) {echo "FEHLER"; exit;}

$CMS_EINSTELLUNGEN = cms_einstellungen_laden();
$dbs = cms_verbinden('s');
$EMPFAENGERPOOL = cms_postfach_empfaengerpool_generieren($dbs);
cms_trennen($dbs);

if (cms_angemeldet()) {

	$absender = $CMS_BENUTZERID;

	$fehler = false;
	$poolfehler = false;

	if (strlen($betreff) == 0) {$fehler = true;}

	if (strlen($empfaenger) <2) {$fehler = true;}
	else {
		$empfaengereinzeln = explode('|', substr($empfaenger,1));
		foreach ($empfaengereinzeln AS $e) {
			if (!in_array($e, $EMPFAENGERPOOL)) {
				echo "POOL";
				exit;
			}
		}
	}

	if ($poolfehler) {$fehler = true; }

	if (!$fehler) {
		$jetzt = time();
		$dbp = cms_verbinden('p');
		// Nachrichten in die DB eintragen
		$idAUSGANG = cms_generiere_kleinste_id('postausgang_'.$CMS_BENUTZERID, 'p');
		$nachricht = cms_texttrafo_e_db($nachricht);
		$betreff = cms_texttrafo_e_db($betreff);
		$sql = $dbp->prepare("UPDATE postausgang_$CMS_BENUTZERID SET absender = ?, empfaenger = ?, zeit = ?, betreff = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), nachricht = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), papierkorb = AES_ENCRYPT('-', '$CMS_SCHLUESSEL') WHERE id = ?;");
		$sql->bind_param("isissi", $absender, $empfaenger, $jetzt, $betreff, $nachricht, $idAUSGANG);
		$sql->execute();
		$sql->close();

		// Anhänge kopieren
		if (file_exists("../../../dateien/schulhof/personen/$CMS_BENUTZERID/postfach/ausgang/".$idAUSGANG)) {
			cms_dateisystem_ordner_loeschen("../../../dateien/schulhof/personen/$CMS_BENUTZERID/postfach/ausgang/".$idAUSGANG);
		}
		mkdir("../../../dateien/schulhof/personen/$CMS_BENUTZERID/postfach/ausgang/".$idAUSGANG, 0775);
		cms_dateisystem_ordner_kopieren("../../../dateien/schulhof/personen/$CMS_BENUTZERID/postfach/temp", "../../../dateien/schulhof/personen/$CMS_BENUTZERID/postfach/ausgang/".$idAUSGANG);

		// Empfänger
		foreach ($empfaengereinzeln AS $e) {
			if (cms_check_ganzzahl($e)) {
				$idEINGANG = cms_generiere_kleinste_id('posteingang_'.$e, 'p');
				if ($offen == 1) {$alleempf = $empfaenger;} else {$alleempf = "|".$e;}
				$sql = $dbp->prepare("UPDATE posteingang_$e SET absender = ?, empfaenger = ?, alle = ?, zeit = ?, betreff = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), nachricht = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), gelesen = AES_ENCRYPT('-', '$CMS_SCHLUESSEL'), papierkorb = AES_ENCRYPT('-', '$CMS_SCHLUESSEL') WHERE id = ?;");
				$sql->bind_param("iisissi", $absender, $e, $alleempf, $jetzt, $betreff, $nachricht, $idEINGANG);
				$sql->execute();
				$sql->close();
				if (file_exists("../../../dateien/schulhof/personen/$e/postfach/eingang/".$idEINGANG)) {
					cms_dateisystem_ordner_loeschen("../../../dateien/schulhof/personen/$e/postfach/eingang/".$idEINGANG);
				}
				mkdir("../../../dateien/schulhof/personen/$e/postfach/eingang/".$idEINGANG, 0775);
				cms_dateisystem_ordner_kopieren("../../../dateien/schulhof/personen/$CMS_BENUTZERID/postfach/temp", "../../../dateien/schulhof/personen/$e/postfach/eingang/".$idEINGANG);
			}
		}

		cms_trennen($dbp);

		// temp-Ordner leeren
		cms_dateisystem_ordner_loeschen("../../../dateien/schulhof/personen/$CMS_BENUTZERID/postfach/temp");
		mkdir("../../../dateien/schulhof/personen/$CMS_BENUTZERID/postfach/temp", 0775);


		// Benachrichtigungen verschicken
		if (count($empfaengereinzeln) <= 100) {
			$dbs = cms_verbinden('s');
			$sqlempfaenger = '('.implode(',', $empfaengereinzeln).')';
			if (cms_check_idliste($sqlempfaenger)) {
				require_once '../../phpmailer/PHPMailerAutoload.php';

				$sql = "SELECT AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel, AES_DECRYPT(email, '$CMS_SCHLUESSEL') AS email, AES_DECRYPT(geschlecht, '$CMS_SCHLUESSEL') AS geschlecht, AES_DECRYPT(art, '$CMS_SCHLUESSEL') AS art FROM personen";
				$sql .= " JOIN personen_einstellungen ON personen.id = personen_einstellungen.person JOIN nutzerkonten ON personen.id = nutzerkonten.id WHERE AES_DECRYPT(postmail, '$CMS_SCHLUESSEL') = '1' AND (personen.id IN $sqlempfaenger);";

				$sql = $dbs->prepare($sql);
				if ($sql->execute()) {
					$sql->bind_result($vorname, $nachname, $titel, $email, $geschlecht, $art);
					while ($sql->fetch()) {

						// BENACHRICHTIGUNG VERSCHICKEN
						$anrede = cms_mail_anrede($titel, $vorname, $nachname, $art, $geschlecht);

						$text;
						for ($i=0; $i<2; $i++) {
							$text[$i] = $anrede.$CMS_MAILZ[$i].$CMS_MAILZ[$i];
							$text[$i] = $text[$i].'Im Postfach ist eine neue Nachricht eingegangen.'.$CMS_MAILZ[$i];
							$text[$i] = $text[$i].'Das Postfach ist unter: '.$CMS_DOMAIN.'/Schulhof/Nutzerkonto/Postfach zu erreichen.'.$CMS_MAILZ[$i].$CMS_MAILZ[$i];
							$text[$i] = $text[$i].$CMS_MAILSIGNATUR[$i];
						}

						// Mail verschicken:
						if (strlen($titel) > 0) {$empfaenger = $titel." ".$vorname." ".$nachname;}
						else {$empfaenger = $vorname." ".$nachname;}
						$betreff = $CMS_SCHULE.' '.$CMS_ORT.' Schulhof - Neue Nachricht';
						$mailerfolg = cms_mailsenden($empfaenger, $email, $betreff, $text[1], $text[0]);
					}
				}
				$sql->close();
			}

			cms_trennen($dbs);
		}
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
