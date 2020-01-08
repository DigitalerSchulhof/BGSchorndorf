<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
include_once("../../schulhof/anfragen/verwaltung/personen/personloeschenfkt.php");
session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['klassen'])) {$klassen = $_POST['klassen'];} else {echo "FEHLER";exit;}
if (isset($_POST['stufen'])) {$stufen = $_POST['stufen'];} else {echo "FEHLER";exit;}
if (isset($_POST['gremien'])) {$gremien = $_POST['gremien'];} else {echo "FEHLER";exit;}
if (isset($_POST['fachschaften'])) {$fachschaften = $_POST['fachschaften'];} else {echo "FEHLER";exit;}
if (isset($_POST['arbeitsgemeinschaften'])) {$arbeitsgemeinschaften = $_POST['arbeitsgemeinschaften'];} else {echo "FEHLER";exit;}
if (isset($_POST['arbeitskreise'])) {$arbeitskreise = $_POST['arbeitskreise'];} else {echo "FEHLER";exit;}
if (isset($_POST['fahrten'])) {$fahrten = $_POST['fahrten'];} else {echo "FEHLER";exit;}
if (isset($_POST['wettbewerbe'])) {$wettbewerbe = $_POST['wettbewerbe'];} else {echo "FEHLER";exit;}
if (isset($_POST['ereignisse'])) {$ereignisse = $_POST['ereignisse'];} else {echo "FEHLER";exit;}
if (isset($_POST['sonstigegruppen'])) {$sonstigegruppen = $_POST['sonstigegruppen'];} else {echo "FEHLER";exit;}
if (isset($_POST['loeschen'])) {$loeschen = $_POST['loeschen'];} else {echo "FEHLER";exit;}
if (isset($_SESSION['SCHULJAHRFABRIKSCHULJAHRNEU'])) {$neuschuljahr = $_SESSION['SCHULJAHRFABRIKSCHULJAHRNEU'];} else {echo "FEHLER";exit;}
if (isset($_SESSION['SCHULJAHRFABRIKSCHULJAHR'])) {$altschuljahr = $_SESSION['SCHULJAHRFABRIKSCHULJAHR'];} else {echo "FEHLER";exit;}

cms_rechte_laden();

$dbs = cms_verbinden('s');

if (cms_angemeldet() && cms_r("schulhof.planung.schuljahre.fabrik"))) {
	$fehler = false;

	$gruppenids[0]['name']    = 'klassen';
	$gruppenids[0]['ids']     = $klassen;
	$gruppenids[0]['gruppen'] = array();
	$gruppenids[1]['name']    = 'stufen';
	$gruppenids[1]['ids']     = $stufen;
	$gruppenids[1]['gruppen'] = array();
	$gruppenids[2]['name']    = 'gremien';
	$gruppenids[2]['ids']     = $gremien;
	$gruppenids[2]['gruppen'] = array();
	$gruppenids[3]['name']    = 'fachschaften';
	$gruppenids[3]['ids']     = $fachschaften;
	$gruppenids[3]['gruppen'] = array();
	$gruppenids[4]['name']    = 'arbeitsgemeinschaften';
	$gruppenids[4]['ids']     = $arbeitsgemeinschaften;
	$gruppenids[4]['gruppen'] = array();
	$gruppenids[5]['name']    = 'arbeitskreise';
	$gruppenids[5]['ids']     = $arbeitskreise;
	$gruppenids[5]['gruppen'] = array();
	$gruppenids[6]['name']    = 'fahrten';
	$gruppenids[6]['ids']     = $fahrten;
	$gruppenids[6]['gruppen'] = array();
	$gruppenids[7]['name']    = 'wettbewerbe';
	$gruppenids[7]['ids']     = $wettbewerbe;
	$gruppenids[7]['gruppen'] = array();
	$gruppenids[8]['name']    = 'ereignisse';
	$gruppenids[8]['ids']     = $ereignisse;
	$gruppenids[8]['gruppen'] = array();
	$gruppenids[9]['name']    = 'sonstigegruppen';
	$gruppenids[9]['ids']     = $sonstigegruppen;
	$gruppenids[9]['gruppen'] = array();

	if (!cms_check_idfeld($loeschen)) {$fehler = true;}

	// Prüfen, ob die Gruppen im richtigen Schuljahr liegen
	$dbs = cms_verbinden('s');
	$gruppenfehler = false;
	foreach ($gruppenids as $g) {
		if (cms_check_idfeld($g['ids'])) {
			$ids = cms_generiere_sqlidliste($g['ids']);
			if (strlen($ids) > 2) {
				$sql = $dbs->prepare("SELECT COUNT(*) AS anzahl FROM ".$g['name']." WHERE id IN $ids AND schuljahr != ?");
				$sql->bind_param("i", $neuschuljahr);
				if ($sql->execute()) {
				  $sql->bind_result($anzahl);
				  if ($sql->fetch()) {if ($anzahl > 0) {$gruppenfehler = true;}}
				} else {$gruppenfehler = true;}
				$sql->close();
			}
		}
		else {$gruppenfehler = true;}
	}

	if ($gruppenfehler) {$fehler = true; echo "GRUPPEN";}

	if (!$fehler) {
		// Prüfen, ob alle Personen Schüler sind
		$personenids = "";
		$personenfehler = false;
		for ($g=0; $g<count($gruppenids); $g++) {
			$ids = explode('|', substr($gruppenids[$g]['ids'], 1));
			foreach ($ids as $i) {
				if (strlen($i) > 0) {
					if (!isset($_POST[$gruppenids[$g]['name']."_".$i])) {$personenfehler = true;}
					else {
						if (!cms_check_idfeld($_POST[$gruppenids[$g]['name']."_".$i])) {$personenfehler = true;}
						else {
							$gruppe = array();
							$gruppe['id'] = $i;
							$gruppe['schueler'] = $_POST[$gruppenids[$g]['name']."_".$i];
							array_push($gruppenids[$g]['gruppen'], $gruppe);
							$personenids .= $_POST[$gruppenids[$g]['name']."_".$i];
						}
					}
				}
			}
		}

		if ($personenfehler) {$fehler = true; echo "PERSONEN";}
		else {
			$ids = cms_generiere_sqlidliste($personenids);
			if (strlen($ids) > 2) {
				$sql = $dbs->prepare("SELECT COUNT(*) AS anzahl FROM personen WHERE id IN $ids AND art != AES_ENCRYPT('s', '$CMS_SCHLUESSEL')");
				if ($sql->execute()) {
				  $sql->bind_result($anzahl);
				  if ($sql->fetch()) {if ($anzahl > 0) {$personenfehler = true;}}
				} else {$personenfehler = true;}
				$sql->close();
			}
		}

		if ($personenfehler) {$fehler = true; echo "PERSONEN";}
	}

	if (!$fehler) {
		// Für jede Klasse zugehörige Stufe eintragen
		$sql = $dbs->prepare("SELECT id, stufe FROM klassen WHERE schuljahr = ?");
		$sql->bind_param("i", $neuschuljahr);
		if ($sql->execute()) {
			$sql->bind_result($kid, $kstufe);
			while ($sql->fetch()) {
				for ($i=0; $i<count($gruppenids[0]['gruppen']); $i++) {
					if ($gruppenids[0]['gruppen'][$i]['id'] == $kid) {
						$gruppenids[0]['gruppen'][$i]['stufe'] = $kstufe;
					}
				}
			}
		}
		$sql->close();

		// Personen aus jeder Klasse in die entsprechende Stufe eintragen
		for ($k=0; $k<count($gruppenids[0]['gruppen']); $k++) {
			$stufe = $gruppenids[0]['gruppen'][$k]['stufe'];
			$personen = $gruppenids[0]['gruppen'][$k]['schueler'];
			for ($s=0; $s<count($gruppenids[1]['gruppen']); $s++) {
				if ($stufe == $gruppenids[1]['gruppen'][$s]['id']) {
					$gruppenids[1]['gruppen'][$s]['schueler'] .= $personen;
				}
			}
		}

		// Personenarrays ohne Dopplungen
		for ($g=0; $g<count($gruppenids); $g++) {
			for ($p=0; $p<count($gruppenids[$g]['gruppen']); $p++) {
				$personen = $gruppenids[$g]['gruppen'][$p]['schueler'];
				if (strlen($personen) > 0) {
					$personen = explode('|', substr($personen, 1));
					$personen = array_unique($personen);
					$gruppenids[$g]['gruppen'][$p]['schueler'] = "|".implode('|', $personen);
				}
			}
		}
	}

	if (!$fehler) {

		$GRUPPENMART[0] = 'mitglieder';
		$GRUPPENMART[1] = 'vorsitz';
		$GRUPPENMART[2] = 'aufsicht';

		foreach ($gruppenids as $g) {
			foreach ($GRUPPENMART as $a) {
				$sql = $dbs->prepare("DELETE FROM ".$g['name'].$a." WHERE gruppe IN (SELECT id FROM ".$g['name']." WHERE schuljahr = ?)");
				$sql->bind_param("i", $neuschuljahr);
				$sql->execute();
				$sql->close();
			}
		}

		for ($g=0; $g<count($gruppenids); $g++) {
			$sql = $dbs->prepare("INSERT INTO ".$gruppenids[$g]['name']."mitglieder (gruppe, person, dateiupload, dateidownload, dateiloeschen, dateiumbenennen, termine, blogeintraege, chatten, nachrichtloeschen,nutzerstummschalten,chatbannbis, chatbannvon) VALUES (?, ?, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0)");
			for ($i=0; $i<count($gruppenids[$g]['gruppen']); $i++) {
				$personen = $gruppenids[$g]['gruppen'][$i]['schueler'];
				if (strlen($personen) > 0) {
					$personen = explode("|", substr($personen, 1));
					foreach ($personen as $p) {
						$sql->bind_param("ii", $gruppenids[$g]['gruppen'][$i]['id'], $p);
						$sql->execute();
					}
				}
			}
			$sql->close();
		}

		// Personen der Klassen in die jeweilige Stufen übernehmen
		$sql = $dbs->prepare("INSERT INTO stufenmitglieder (gruppe, person, dateiupload, dateidownload, dateiloeschen, dateiumbenennen, termine, blogeintraege, chatten, nachrichtloeschen, nutzerstummschalten, chatbannbis, chatbannvon) SELECT DISTINCT stufe, person, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0 FROM klassenmitglieder JOIN klassen ON klassenmitglieder.gruppe = klassen.id WHERE schuljahr = ? AND (stufe, person) NOT IN (SELECT stufe, person FROM stufenmitglieder JOIN stufen ON gruppe = stufen.id WHERE schuljahr = ?)");
		$sql->bind_param("ii", $neuschuljahr, $neuschuljahr);
		$sql->execute();
		$sql->close();

		// Personen löschen
		$loeschennamen = "";
		$loeschenfehler = "";
		if (strlen($loeschen)) {
			$dbp = cms_verbinden('p');
			$loeschen = explode('|', substr($loeschen, 1));
			$loeschenfehler = "";
			foreach ($loeschen as $l) {
				$ergebnis = cms_verwaltung_personloeschen ($dbs, $dbp, $l);
				if ($ergebnis != "ERFOLG") {
					$loeschenfehler .= "|".$l;
				}
			}
			cms_trennen($dbp);
		}

		if (strlen($loeschenfehler) > 0) {
			$ids = cms_generiere_sqlidliste($loeschenfehler);
			$sql = $dbs->prepare("SELECT * FROM (SELECT AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') FROM personen WHERE id IN $ids) AS x ORDER BY nachname ASC, vorname ASC");
			if ($sql->execute()) {
				$sql->bind_result($vorname, $nachname, $titel);
				while ($sql->fetch()) {
					$loeschennamen .= ";".cms_generiere_anzeigename($vorname, $nachname, $titel);
				}
			} else {$personenfehler = true;}
			$sql->close();
		}
		echo "ERFOLG".$loeschennamen;
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
