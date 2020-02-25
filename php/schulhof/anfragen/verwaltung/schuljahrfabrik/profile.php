<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['profilegewaehlt'])) {$profilegewaehlt = $_POST['profilegewaehlt'];} else {echo "FEHLER";exit;}
if (isset($_SESSION['SCHULJAHRFABRIKSCHULJAHRNEU'])) {$neuschuljahr = $_SESSION['SCHULJAHRFABRIKSCHULJAHRNEU'];} else {echo "FEHLER";exit;}
if (isset($_SESSION['SCHULJAHRFABRIKSCHULJAHR'])) {$altschuljahr = $_SESSION['SCHULJAHRFABRIKSCHULJAHR'];} else {echo "FEHLER";exit;}


cms_rechte_laden();

$dbs = cms_verbinden('s');

if (cms_angemeldet() && cms_r("schulhof.planung.schuljahre.fabrik")) {
	$fehler = false;

	// Alte Profile laden
	$sql = $dbs->prepare("SELECT * FROM (SELECT profile.id, art, AES_DECRYPT(profile.bezeichnung, '$CMS_SCHLUESSEL') AS pbezeichnung, AES_DECRYPT(stufen.bezeichnung, '$CMS_SCHLUESSEL') AS sbezeichnung, reihenfolge, stufen.id AS sid FROM profile JOIN stufen ON profile.stufe = stufen.id WHERE profile.schuljahr = ?) AS x WHERE sbezeichnung IN (SELECT AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') FROM stufen WHERE schuljahr = ?) ORDER BY reihenfolge ASC, pbezeichnung ASC");

	$ALTEPROFILE = array();
	$sql->bind_param("ii", $altschuljahr, $neuschuljahr);
	if ($sql->execute()) {
		$sql->bind_result($pid, $part, $ppbez, $psbez, $preihenfolge, $pstufe);
		while ($sql->fetch()) {
			$PROFIL = array();
			$PROFIL['altid'] = $pid;
			$PROFIL['neuid'] = null;
			$PROFIL['art'] = $part;
			$PROFIL['profilbez'] = $ppbez;
			$PROFIL['altstufe'] = $pstufe;
			$PROFIL['neustufe'] = null;
			$PROFIL['stufenbez'] = $psbez;
			$PROFIL['stufenrei'] = $preihenfolge;
			$PROFIL['faecher'] = array();
			array_push($ALTEPROFILE, $PROFIL);
		}
	}
	$sql->close();

	// Fächer zu den alten Profilen laden
	$sql = $dbs->prepare("SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL'), AES_DECRYPT(kuerzel, '$CMS_SCHLUESSEL') FROM faecher JOIN profilfaecher ON faecher.id = profilfaecher.fach WHERE profilfaecher.profil = ?");
	for ($i=0; $i < count($ALTEPROFILE); $i++) {
		$sql->bind_param("i", $ALTEPROFILE[$i]['altid']);
		if ($sql->execute()) {
			$sql->bind_result($fid, $fbez, $fkur);
			while ($sql->fetch()) {
				$FACH = array();
				$FACH['altid'] = $fid;
				$FACH['neuid'] = null;
				$FACH['fachbez'] = $fbez;
				$FACH['fachkur'] = $fkur;
				array_push($ALTEPROFILE[$i]['faecher'], $FACH);
			}
		}
	}
	$sql->close();

	// Prüfen, welche der Fächer es im neuen Schuljahr gibt
	$sql = $dbs->prepare("SELECT id FROM faecher WHERE bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') AND kuerzel = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') AND schuljahr = ?");
	for ($i=0; $i < count($ALTEPROFILE); $i++) {
		for ($f=0; $f < count($ALTEPROFILE[$i]['faecher']); $f++) {
			$sql->bind_param("ssi", $ALTEPROFILE[$i]['faecher'][$f]['fachbez'], $ALTEPROFILE[$i]['faecher'][$f]['fachkur'], $neuschuljahr);
			if ($sql->execute()) {
				$sql->bind_result($fid);
				if ($sql->fetch()) {
					$ALTEPROFILE[$i]['faecher'][$f]['neuid'] = $fid;
				}
			}
		}
	}
	$sql->close();

	// Prüfen, welche der Stufen es im neuen Schuljahr gibt
	$sql = $dbs->prepare("SELECT id FROM stufen WHERE bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') AND schuljahr = ?");
	for ($i=0; $i < count($ALTEPROFILE); $i++) {
		$sql->bind_param("si", $ALTEPROFILE[$i]['stufenbez'], $neuschuljahr);
		if ($sql->execute()) {
			$sql->bind_result($sid);
			if ($sql->fetch()) {
				$ALTEPROFILE[$i]['neustufe'] = $sid;
			}
		}
	}
	$sql->close();

	// Nur gewählte Profile anlegen
	$profilegewaehlt = explode('|', substr($profilegewaehlt,1));
	$NEUEPROFILE = array();
	foreach ($ALTEPROFILE AS $p) {
		$profilok = true;

		if (is_null($p['neustufe'])) {$profilok = false;}

		foreach ($p['faecher'] AS $f) {
			if (is_null($f['neuid'])) {$profilok = false;}
		}

		if ($profilok && in_array($p['altid'], $profilegewaehlt)) {
			array_push($NEUEPROFILE, $p);
		}
	}


	if (!$fehler) {
		// Alte Profile löschen
		$sql = $dbs->prepare("DELETE FROM profile WHERE schuljahr = ?");
		$sql->bind_param("i", $neuschuljahr);
		$sql->execute();
		$sql->close();

		// NÄCHSTE FREIE ID SUCHEN
		$sql = $dbs->prepare("UPDATE profile SET schuljahr = ?, art = ?, bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), stufe = ? WHERE id = ?");
		for ($i=0; $i<count($NEUEPROFILE); $i++) {
			$id = cms_generiere_kleinste_id('profile');
			$NEUEPROFILE[$i]['neuid'] = $id;
			$sql->bind_param("issii", $neuschuljahr, $ALTEPROFILE[$i]['art'], $ALTEPROFILE[$i]['profilbez'], $ALTEPROFILE[$i]['neustufe'], $id);
			$sql->execute();
		}
		$sql->close();

		// Fächer zuordnen
		$sql = $dbs->prepare("INSERT INTO profilfaecher (profil, fach) VALUES (?, ?)");
		for ($i=0; $i<count($NEUEPROFILE); $i++) {
			foreach ($NEUEPROFILE[$i]['faecher'] AS $f) {
				$sql->bind_param("ii", $NEUEPROFILE[$i]['neuid'], $f['neuid']);
				$sql->execute();
			}
		}
		$sql->close();

		echo "ERFOLG";
	}
	else {
		echo "FEHLER";
	}
}
else {
	echo "BERECHTIGUNG";
}
cms_trennen($dbs);
?>
