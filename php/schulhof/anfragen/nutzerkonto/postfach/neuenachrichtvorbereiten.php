<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/dateisystem.php");
include_once("../../schulhof/funktionen/generieren.php");
include_once("../../schulhof/anfragen/verwaltung/gruppen/initial.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['aktion'])) {$aktion = $_POST['aktion'];} else {echo "FEHLER"; exit;}
if (isset($_POST['id'])) {$id = $_POST['id'];} else {echo "FEHLER"; exit;}
if (isset($_POST['modus'])) {$modus = $_POST['modus'];} else {echo "FEHLER"; exit;}
if (isset($_POST['empfaenger'])) {$empf = $_POST['empfaenger'];} else {echo "FEHLER"; exit;}
if (isset($_POST['gruppe'])) {$gruppe = $_POST['gruppe'];} else {echo "FEHLER"; exit;}
if (isset($_POST['gruppenid'])) {$gruppenid = $_POST['gruppenid'];} else {echo "FEHLER"; exit;}
if (isset($_SESSION['BENUTZERID'])) {$CMS_BENUTZERID = $_SESSION['BENUTZERID'];} else {echo "FEHLER"; exit;}
if (isset($_SESSION['BENUTZERART'])) {$CMS_BENUTZERART = $_SESSION['BENUTZERART'];} else {echo "FEHLER"; exit;}
if (isset($_SESSION['BENUTZERSCHULJAHR'])) {$CMS_BENUTZERSCHULJAHR = $_SESSION['BENUTZERSCHULJAHR'];} else {echo "FEHLER"; exit;}

$CMS_EINSTELLUNGEN = cms_einstellungen_laden();

if (!cms_check_ganzzahl($CMS_BENUTZERID)) {echo "FEHLER"; exit;}
if (($modus != 'eingang') && ($modus != 'ausgang') && ($modus != 'entwurf') && !(($modus == '') && (($aktion == "neu") || ($aktion == "vorgabe") || ($aktion == "gruppe")))) {echo "FEHLER"; exit;}
if ((!cms_valide_gruppe($gruppe)) && ($gruppe != '-')) {echo "FEHLER"; exit;}

if (cms_angemeldet()) {
	$fehler = true;

	// temp-Ordner leeren
	cms_dateisystem_ordner_loeschen("../../../dateien/schulhof/personen/$CMS_BENUTZERID/postfach/temp");
	mkdir("../../../dateien/schulhof/personen/$CMS_BENUTZERID/postfach/temp", 0775);

	// Eigene Signatur laden
	$signatur = "";
	$dbs = cms_verbinden('s');
	include_once("../../schulhof/anfragen/nutzerkonto/postfach/vorbereiten.php");
	include_once("../../schulhof/anfragen/verwaltung/gruppen/initial.php");
	$POSTEMPFAENGERPOOL = cms_postfach_empfaengerpool_generieren($dbs);
	$_SESSION['POSTEMPFAENGERPOOL'] = $POSTEMPFAENGERPOOL;


	$sql = $dbs->prepare("SELECT AES_DECRYPT(signatur, '$CMS_SCHLUESSEL') AS signatur FROM personen_signaturen WHERE person = ?");
	$sql->bind_param("i", $CMS_BENUTZERID);

	if ($sql->execute()) {
	  $sql->bind_result($signatur);
	  $sql->fetch();
	}
	$sql->close();

	if (strlen($signatur) > 0) {$signatur = "<p class=\"cms_signatur\">$signatur</p>";}

	// Auf neue Nachricht vorbereiten - Alle gespeicherten Angaben löschen
	if ($aktion == "neu") {
		$POSTEMPFAENGER = '';
		$_SESSION['POSTBETREFF'] = '';
		$_SESSION['POSTNACHRICHT'] = "<p><br></p>".$signatur;
		$fehler = false;
	}
	else if ($aktion == "vorgabe") {
		$POSTEMPFAENGER = '|'.$empf;
		$_SESSION['POSTBETREFF'] = '';
		$_SESSION['POSTNACHRICHT'] = "<p><br></p>".$signatur;
		$fehler = false;
	}
	else if ($aktion == "gruppe") {
		$gk = cms_textzudb($gruppe);
		$POSTEMPFAENGER = "";
		// LADEN MEHRERER RESULTATE
		$sql = $dbs->prepare("SELECT DISTINCT * FROM ((SELECT person FROM $gk"."mitglieder WHERE gruppe = ?) UNION (SELECT person FROM $gk"."aufsicht WHERE gruppe = ?)) AS x");
		$sql->bind_param("ii", $gruppenid, $gruppenid);
		if ($sql->execute()) {
			$sql->bind_result($sperson);
	    while($sql->fetch()) {
	      $POSTEMPFAENGER .= '|'.$sperson;
	    }
		}
		$sql->close();
		$_SESSION['POSTBETREFF'] = '';
		$_SESSION['POSTNACHRICHT'] = "<p><br></p>".$signatur;
		$fehler = false;
	}
	else if ($aktion == "weiterleiten") {
		$db = cms_verbinden('ü');

		$sql = $db->prepare("SELECT AES_DECRYPT(betreff, '$CMS_SCHLUESSEL') AS betreff, AES_DECRYPT(nachricht, '$CMS_SCHLUESSEL') AS nachricht, zeit, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel, erstellt FROM $CMS_DBP_DB.post$modus"."_$CMS_BENUTZERID JOIN $CMS_DBS_DB.personen ON absender = $CMS_DBS_DB.personen.id LEFT JOIN $CMS_DBS_DB.nutzerkonten ON absender = $CMS_DBS_DB.nutzerkonten.id WHERE $CMS_DBP_DB.post$modus"."_$CMS_BENUTZERID.id = ?");
		$sql->bind_param("i", $id);

		if ($sql->execute()) {
		  //$id = "";
		  $sql->bind_result($wbetreff, $wnachricht, $wzeit, $wvorname, $wnachname, $wtitel, $werstellt);
		  if ($sql->fetch()) {
				$POSTEMPFAENGER = '';
				$_SESSION['POSTBETREFF'] = "WL: ".$wbetreff;
				if ($wzeit > $werstellt) {$absender = cms_generiere_anzeigename($wvorname, $wnachname, $wtitel);}
				else {$absender = "<i>existiert nicht mehr</i>";}
				$nachricht = "<div class=\"cms_originalnachricht\"><p class=\"cms_originalnachricht_meta\">";
				$nachricht .= "Originalnachricht von ".$absender." vom ".cms_tagnamekomplett(date('w', $wzeit)).", den ".date('d', $wzeit).". ";
				$nachricht .= cms_monatsnamekomplett(date('m', $wzeit))." ".date('Y', $wzeit)." um ".date('H:i', $wzeit)." mit dem Betreff <b>".$wbetreff."</b></p>".$wnachricht."</div>";
				$_SESSION['POSTNACHRICHT'] = "<p><br></p>".$signatur.$nachricht;
		  }
			else {$fehler = true;}
		}
		else {$fehler = true;}
		$sql->close();
		cms_trennen($db);
		$fehler = false;
	}
	else if ($aktion == "bearbeiten") {
		$dbp = cms_verbinden('p');
		if ($modus == 'eingang') {$spalte = "alle AS empfaenger";} else {$spalte = "empfaenger";}

		$sql = $dbp->prepare("SELECT AES_DECRYPT(betreff, '$CMS_SCHLUESSEL') AS betreff, AES_DECRYPT(nachricht, '$CMS_SCHLUESSEL') AS nachricht, $spalte FROM post$modus"."_$CMS_BENUTZERID WHERE id = ?");
		$sql->bind_param("i", $id);
		if ($sql->execute()) {
		  //$id = "";
		  $sql->bind_result($bbetreff, $bnachricht, $bempfaenger);
		  if ($sql->fetch()) {
				$empfaenger = substr(str_replace('|'.$CMS_BENUTZERID.'|', '|', $bempfaenger.'|'), 0, -1);
				$POSTEMPFAENGER = $empfaenger;
				$_SESSION['POSTBETREFF'] = $bbetreff;
				$_SESSION['POSTNACHRICHT'] = $bnachricht;
		  }
			else {$fehler = true;}
		}
		else {$fehler = true;}
		$sql->close();
		cms_trennen($dbp);
		$fehler = false;
	}
	else if ($aktion == "erneut") {
		$dbp = cms_verbinden('p');

		$sql = $dbp->prepare("SELECT AES_DECRYPT(betreff, '$CMS_SCHLUESSEL') AS betreff, AES_DECRYPT(nachricht, '$CMS_SCHLUESSEL') AS nachricht FROM post$modus"."_$CMS_BENUTZERID WHERE id = ?");
		$sql->bind_param("i", $id);
		if ($sql->execute()) {
		  //$id = "";
		  $sql->bind_result($ebetreff, $enachricht);
		  if ($sql->fetch()) {
				$POSTEMPFAENGER = '';
				$_SESSION['POSTBETREFF'] = $ebetreff;
				$_SESSION['POSTNACHRICHT'] = $enachricht;
		  }
			else {$fehler = true;}
		}
		else {$fehler = true;}
		$sql->close();
		cms_trennen($dbp);
		$fehler = false;
	}
	else if (($aktion == "antworten") && ($modus == 'eingang')) {
		$db = cms_verbinden('ü');
		$sql = $db->prepare("SELECT AES_DECRYPT(betreff, '$CMS_SCHLUESSEL') AS betreff, AES_DECRYPT(nachricht, '$CMS_SCHLUESSEL') AS nachricht, zeit, absender, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel, erstellt FROM $CMS_DBP_DB.post$modus"."_$CMS_BENUTZERID JOIN $CMS_DBS_DB.personen ON absender = $CMS_DBS_DB.personen.id LEFT JOIN $CMS_DBS_DB.nutzerkonten ON absender = $CMS_DBS_DB.nutzerkonten.id WHERE $CMS_DBP_DB.post$modus"."_$CMS_BENUTZERID.id = ?");
		$sql->bind_param("i", $id);

		if ($sql->execute()) {
		  //$id = "";
		  $sql->bind_result($abetreff, $anachricht, $azeit, $aabsender, $avorname, $anachname, $atitel, $aerstellt);
		  if ($sql->fetch()) {
				$POSTEMPFAENGER = '|'.$aabsender;
				$_SESSION['POSTBETREFF'] = "AW: ".$abetreff;
				if ($azeit > $aerstellt) {$absender = cms_generiere_anzeigename($avorname, $anachname, $atitel);}
				else {$absender = "<i>existiert nicht mehr</i>";}
				$nachricht = "<div class=\"cms_originalnachricht\"><p class=\"cms_originalnachricht_meta\">";
				$nachricht .= "Originalnachricht von ".$absender." vom ".cms_tagnamekomplett(date('w', $azeit)).", den ".date('d', $azeit).". ";
				$nachricht .= cms_monatsnamekomplett(date('m', $azeit))." ".date('Y', $azeit)." um ".date('H:i', $azeit)." mit dem Betreff <b>".$abetreff."</b></p>".$anachricht."</div>";
				$_SESSION['POSTNACHRICHT'] = "<p><br></p>".$signatur.$nachricht;
		  }
			else {$fehler = true;}
		}
		else {$fehler = true;}
		$sql->close();
		cms_trennen($db);
		$fehler = false;
	}
	else if (($aktion == "allenantworten") && ($modus == 'eingang')) {
		$db = cms_verbinden('ü');
		$sql = $db->prepare("SELECT AES_DECRYPT(betreff, '$CMS_SCHLUESSEL') AS betreff, AES_DECRYPT(nachricht, '$CMS_SCHLUESSEL') AS nachricht, zeit, alle, absender, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel, erstellt FROM $CMS_DBP_DB.post$modus"."_$CMS_BENUTZERID JOIN $CMS_DBS_DB.personen ON absender = $CMS_DBS_DB.personen.id LEFT JOIN $CMS_DBS_DB.nutzerkonten ON absender = $CMS_DBS_DB.nutzerkonten.id WHERE $CMS_DBP_DB.post$modus"."_$CMS_BENUTZERID.id = ?");
		$sql->bind_param("i", $id);

		if ($sql->execute()) {
		  //$id = "";
		  $sql->bind_result($abetreff, $anachricht, $azeit, $aalle, $aabsender, $avorname, $anachname, $atitel, $aerstellt);
		  if ($sql->fetch()) {
				$empfaenger = substr(str_replace('|'.$CMS_BENUTZERID.'|', '|', $aalle.'|'.$aabsender.'|'), 0, -1);
				$POSTEMPFAENGER = $empfaenger;
				$_SESSION['POSTBETREFF'] = "AW: ".$abetreff;
				if ($azeit > $aerstellt) {$absender = cms_generiere_anzeigename($avorname, $anachname, $atitel);}
				else {$absender = "<i>existiert nicht mehr</i>";}
				$nachricht = "<div class=\"cms_originalnachricht\"><p class=\"cms_originalnachricht_meta\">";
				$nachricht .= "Originalnachricht von ".$absender." vom ".cms_tagnamekomplett(date('w', $azeit)).", den ".date('d', $azeit).". ";
				$nachricht .= cms_monatsnamekomplett(date('m', $azeit))." ".date('Y', $azeit)." um ".date('H:i', $azeit)." mit dem Betreff <b>".$abetreff."</b></p>".$anachricht."</div>";
				$_SESSION['POSTNACHRICHT'] = "<p><br></p>".$signatur.$nachricht;
		  }
			else {$fehler = true;}
		}
		else {$fehler = true;}
		$sql->close();
		cms_trennen($db);
		$fehler = false;
	}

	if (!$fehler) {

		if (($aktion != 'neu') && ($aktion != 'vorgabe') && ($aktion != 'gruppe') && ($aktion != 'antworten') && ($aktion != 'allenantworten')) {
			// Anhang übernehmen
			if (is_dir("../../../dateien/schulhof/personen/$CMS_BENUTZERID/postfach/$modus/".$id)) {
				cms_dateisystem_ordner_kopieren("../../../dateien/schulhof/personen/$CMS_BENUTZERID/postfach/$modus/".$id, "../../../dateien/schulhof/personen/$CMS_BENUTZERID/postfach/temp");
			}
		}

		// Empfänger mit allen erlaubten abgleichen
		$EMPFAENGER = "";
		if (strlen($POSTEMPFAENGER) > 0) {
			$POSTEMPFAENGER = explode('|', substr($POSTEMPFAENGER, 1));
			foreach ($POSTEMPFAENGER AS $p) {
				if (in_array($p, $POSTEMPFAENGERPOOL)) {$EMPFAENGER .= '|'.$p;}
			}

		}
		$_SESSION['POSTEMPFAENGER'] = $EMPFAENGER;

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
