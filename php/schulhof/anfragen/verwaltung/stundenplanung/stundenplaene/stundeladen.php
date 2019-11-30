<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/generieren.php");
include_once("../../schulhof/funktionen/meldungen.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/seiten/verwaltung/stundenplanung/stundenplaene/generieren.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['lehrer'])) {$lehrer = $_POST['lehrer'];} else {echo "FEHLER"; exit;}
if (isset($_POST['raum'])) {$raum = $_POST['raum'];} else {echo "FEHLER"; exit;}
if (isset($_POST['klasse'])) {$klasse = $_POST['klasse'];} else {echo "FEHLER"; exit;}
if (isset($_POST['kurs'])) {$kurs = $_POST['kurs'];} else {echo "FEHLER"; exit;}
if (isset($_POST['tag'])) {$tag = $_POST['tag'];} else {echo "FEHLER"; exit;}
if (isset($_POST['stunde'])) {$stunde = $_POST['stunde'];} else {echo "FEHLER"; exit;}
if (isset($_SESSION["STUNDENPLANZEITRAUM"])) {$zeitraum = $_SESSION["STUNDENPLANZEITRAUM"];} else {echo "FEHLER"; exit;}

$CMS_RECHTE = cms_rechte_laden();
$zugriff = $CMS_RECHTE['Planung']['Stunden anlegen'] || $CMS_RECHTE['Planung']['Stunden löschen'];

if (cms_angemeldet() && $zugriff) {
	$fehler = false;
	$code = "";

	$dbs = cms_verbinden('s');
	// Prüfe, ob die Klasse in diesen Zeitraum gehört
	$sql = "SELECT COUNT(*) AS anzahl FROM (SELECT id, klassenstufe FROM klassen WHERE klassen.id = ?) AS x JOIN klassenstufen ON x.klassenstufe = klassenstufen.id JOIN schuljahre ON klassenstufen.schuljahr = schuljahre.id JOIN zeitraeume ON zeitraeume.schuljahr = schuljahre.id WHERE zeitraeume.id = ?";
	$sql = $dbs->prepare($sql);
	$sql->bind_param("ii", $klasse, $zeitraum);
	if ($sql->execute()) {
		$sql->bind_result($anzahl);
		if ($daten = $anfrage->fetch_assoc()) {
			if ($anzahl != 1) {$fehler = true;}
		} else {$fehler = true;}
		$sql->close();
	} else {$fehler = true;}

	// Prüfen, ob der Kurs der Klasse zugeordnet ist
	if (!$fehler) {
		$sql = "SELECT COUNT(*) AS anzahl FROM kursklassen WHERE klasse = ? AND kurs = ?";
		$sql = $dbs->prepare($sql);
		$sql->bind_param("ii", $klasse, $kurs);
		if ($sql->execute()) {
			$sql->bind_result($anzahl);
			if ($sql->fetch()) {
				if ($anzahl != 1) {$fehler = true;}
			} else {$fehler = true;}
			$sql->close();
		} else {$fehler = true;}
	}

	// Weitere Klassen suchen, denen dieser Kurs zugeordnet ist
	$klassen = '|'.$klasse;
	if (!$fehler) {
		$sql = "SELECT DISTINCT klasse FROM kursklassen WHERE kurs = $kurs AND klasse != $klasse";
		if ($anfrage = $dbs->query($sql)) {	// Safe weil ID existiert
			while ($daten = $anfrage->fetch_assoc()) {
				$klassen .= '|'.$daten['klasse'];
			}
			$anfrage->free();
		} else {$fehler = true;}
	}

	$bestehend = array();
	$anzahl = 0;
	if (!$fehler) {
		$sql = "SELECT * FROM (SELECT kurs, lehrkraft, raum FROM stunden WHERE zeitraum = $zeitraum AND tag = $tag AND stunde = $stunde AND (kurs IN (SELECT kurs FROM kursklassen WHERE klasse IN (SELECT DISTINCT klasse FROM kursklassen WHERE kurs = $kurs)) OR raum = $raum OR lehrkraft = $lehrer)) AS x";
		if ($anfrage = $dbs->query($sql)) {	// TODO: Irgendwie safe machen
			while ($daten = $anfrage->fetch_assoc()) {
				$bestehend[$anzahl]['kurs'] = $daten['kurs'];
				$bestehend[$anzahl]['lehrkraft'] = $daten['lehrkraft'];
				$bestehend[$anzahl]['raum'] = $daten['raum'];
				$anzahl++;
			}
			$anfrage->free();
		} else {$fehler = true; echo 3;}
	}


	// Stundendetails laden
	$sql = "SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, AES_DECRYPT(beginnstd, '$CMS_SCHLUESSEL') AS bs, AES_DECRYPT(beginnmin, '$CMS_SCHLUESSEL') AS bm, AES_DECRYPT(endestd, '$CMS_SCHLUESSEL') AS es, AES_DECRYPT(endemin, '$CMS_SCHLUESSEL') AS em FROM schulstunden WHERE id = $stunde";
	if ($anfrage = $dbs->query($sql)) {	// Safe weil ID existiert, WENN OBEN SAFE
		if ($daten = $anfrage->fetch_assoc()) {
			$stunde = $daten;
		} else {$fehler = true;}
		$anfrage->free();
	} else {$fehler = true;}


	if (!$fehler) {
		$code = "";
		// Prüfen, ob genau diese Konstellation schon besteht - Falls nicht zur Anlage vorschlagen
		$gefunden = false;
		foreach ($bestehend AS $erg) {if (($erg['kurs'] == $kurs) && ($erg['lehrkraft'] == $lehrer) && ($erg['raum'] == $raum)) {$gefunden = true;}}
		if ((!$gefunden) && $CMS_RECHTE['Planung']['Stunden anlegen']) {
			// Neue Buchung anlegen
			$code .= "<h4>Neue Stunde</h4>";
			$code .= cms_stunde_ausgeben($dbs, $lehrer, $raum, $kurs, $tag, $stunde, 'a', count($bestehend));
		}
		// Bestehende Buchungen ausgeben
		if (count($bestehend) > 0) {$code .= "<h4>Bestehende Stunden</h4>";}
		foreach ($bestehend AS $erg) {
			$code .= cms_stunde_ausgeben($dbs, $erg['lehrkraft'], $erg['raum'], $erg['kurs'], $tag, $stunde, 'l', count($bestehend));
		}
	}

	cms_trennen($dbs);
	if ($fehler) {echo "ZUORDNUNG";}
	else {
		echo $code;
	}
}
else {
	echo "BERECHTIGUNG";
}


function cms_stunde_ausgeben($dbs, $lehrer, $raum, $kurs, $tag, $stunde, $modus, $anzahl) {
	global $CMS_SCHLUESSEL, $CMS_RECHTE;
	$fehler = false;

	// Lehrerdetails laden
	$sql = "SELECT personen.id AS id, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel, AES_DECRYPT(kuerzel, '$CMS_SCHLUESSEL') AS kuerzel FROM personen JOIN lehrer ON personen.id = lehrer.id WHERE personen.id = $lehrer";
	if ($anfrage = $dbs->query($sql)) {	// TODO: Irgendwie safe machen
		if ($daten = $anfrage->fetch_assoc()) {
			$lehrer = $daten;
		} else {$fehler = true;}
		$anfrage->free();
	} else {$fehler = true;}

	// Raumdetails laden
	$sql = "SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung FROM raeume WHERE id = $raum";
	if ($anfrage = $dbs->query($sql)) {	// TODO: Irgendwie safe machen
		if ($daten = $anfrage->fetch_assoc()) {
			$raum = $daten;
		} else {$fehler = true;}
		$anfrage->free();
	} else {$fehler = true;}


	// Klassenbezeichnungen laden
	$klassenbezeichnungen = "";
	$sql = "SELECT bezeichnung FROM (SELECT DISTINCT AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung FROM klassen JOIN kursklassen ON klassen.id = kursklassen.klasse WHERE kursklassen.kurs = ?) AS x ORDER BY bezeichnung";
	$sql = $dbs->prepare($sql);
	$sql->bind_param("i", $kurs);
	if ($sql->execute()) {
		$sql->bind_result($bez);
		while ($sql->fetch()) {
			$klassenbezeichnungen .= $bez;
		}
		$sql->close();
	} else {$fehler = true;}

	// Kurs laden
	$sql = "SELECT kurse.id AS id, AES_DECRYPT(kurse.bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, AES_DECRYPT(klassenstufen.bezeichnung, '$CMS_SCHLUESSEL') AS stufe FROM kurse JOIN klassenstufen ON kurse.klassenstufe = klassenstufen.id WHERE kurse.id = $kurs";
	if ($anfrage = $dbs->query($sql)) {	// TODO: Irgendwie safe machen
		if ($daten = $anfrage->fetch_assoc()) {
			$kurs = $daten;
		} else {$fehler = true;}
		$anfrage->free();
	} else {$fehler = true;}


	if (!$fehler) {
		$code = "";
		if ((($modus == 'a') && ($CMS_RECHTE['Planung']['Stunden anlegen'])) || $modus == 'l') {
			$code .= "<table class=\"cms_liste\">";
			$code .= "<tr><th>Tag:</th><td>".cms_tagnamekomplett($tag)."</td></tr>";
			$code .= "<tr><th>Stunde:</th><td>".$stunde['bezeichnung']." (".$stunde['bs'].":".$stunde['bm']." - ".$stunde['es'].":".$stunde['em'].")</td></tr>";
			$code .= "<tr><th>Lehrkraft:</th><td>".cms_generiere_anzeigename($lehrer['vorname'], $lehrer['nachname'], $lehrer['titel'])." (".$lehrer['kuerzel'].")</td></tr>";
			$code .= "<tr><th>Raum:</th><td>".$raum['bezeichnung']."</td></tr>";
			$code .= "<tr><th>Kurs:</th><td>".$kurs['bezeichnung']." (".$kurs['stufe']." ".$klassenbezeichnungen.")</td></tr>";
			if ($modus == 'a') {
				$code .= "<tr><td colspan=\"2\">";
				if ($anzahl > 0) {
					$code .= "".cms_meldung('warnung', '<h4>Überschneidung!</h4><p>Achtung, durch Anlegen dieser Stunde entstehen Überschneidungen!</p>');
				}
				$code .= "<input type=\"hidden\" name=\"cms_stundenplanung_tag\" id=\"cms_stundenplanung_tag\" value=\"$tag\">";
				$code .= "<input type=\"hidden\" name=\"cms_stundenplanung_stunde\" id=\"cms_stundenplanung_stunde\" value=\"".$stunde['id']."\">";
				$code .= "<span class=\"cms_button_ja\" onclick=\"cms_stundenplanung_stunde_neu_speichern();\">Anlegen</span></td></tr>";
			}
			else if (($modus == 'l') && ($CMS_RECHTE['Planung']['Stunden löschen'])) {
				$code .= "<tr><td colspan=\"2\"><span class=\"cms_button_nein\" ";
				$code .= "onclick=\"cms_stundenplanung_stunde_loeschen_vorbereiten('".$lehrer['id']."', '".$raum['id']."', '".$kurs['id']."', '".$tag."', '".$stunde['id']."');\">";
				$code .=  "Löschen</span></td></tr>";}
			$code .= "</table>";
		}
		return $code;
	}
	else {return false;}
}
?>
